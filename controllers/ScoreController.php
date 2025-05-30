<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ScoreController {
    public function scores() {
        global $pdo;

        // request parameters
        $difficulty     = $_GET['difficulty'] ?? '';
        $type           = $_GET['type'] ?? 'leaderboard';
    
        header('Content-Type: application/json');

        try {
            if ($difficulty) {
                $stmt = $pdo->prepare("
                    WITH ranked_scores AS (
                        SELECT 
                            s.id,
                            s.account_id,
                            s.score,
                            s.difficulty,
                            s.created_at,
                            a.name AS account_name,
                            ROW_NUMBER() OVER (
                                PARTITION BY s.account_id 
                                ORDER BY s.score DESC, s.created_at DESC
                            ) AS rn
                        FROM 
                            scores s
                        INNER JOIN accounts a ON a.id = s.account_id
                        WHERE 
                            s.difficulty = :difficulty
                    )
                    SELECT 
                        id,
                        account_id,
                        score,
                        difficulty,
                        created_at,
                        account_name
                    FROM 
                        ranked_scores
                    WHERE 
                        rn = 1
                    ORDER BY 
                        score DESC;

                ");
                $stmt->execute(['difficulty' => $difficulty]);
            } else {
                $stmt = $pdo->prepare("
                    SELECT 
                        s.*,
                        a.name AS account_name
                    FROM 
                        scores s
                    INNER JOIN
                        accounts a
                    ON
                        a.id = s.account_id
                    ORDER BY 
                        score 
                    DESC
                ");
                $stmt->execute();
            }
            
            $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Ambil data Skor berhasil',
                'data' => $scores
            ]);
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }
    
        exit;
    }  
    
    public function createScore() {
        global $pdo;

        // request parameters
        $account_id     = $_POST['account_id'] ?? '';
        $difficulty     = $_POST['difficulty'] ?? '';
        $score          = $_POST['score'] ?? '';
    
        header('Content-Type: application/json');

        try {
            $stmt = $pdo->prepare("INSERT INTO scores (account_id, score, difficulty) VALUES (:account_id, :score, :difficulty)");
            $stmt->execute([
                'account_id'    => $account_id,
                'difficulty'    => $difficulty,
                'score'         => $score,
            ]);
    
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Tambah data Skor berhasil',
            ]);
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }
    
        exit;
    }
    
    public function exportScores() {
        global $pdo;
        require __DIR__ . '/../vendor/autoload.php';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="scores.xlsx"');
        header('Cache-Control: max-age=0');

        // Ambil semua data, urutkan berdasarkan account_name lalu created_at
        $stmt = $pdo->prepare("
            SELECT 
                s.id,
                s.account_id,
                a.name AS account_name,
                s.score,
                s.difficulty,
                s.created_at
            FROM scores s
            JOIN accounts a ON a.id = s.account_id
            ORDER BY a.name ASC, s.created_at ASC
        ");
        $stmt->execute();
        $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Tingkat Kesulitan');
        $sheet->setCellValue('D1', 'Skor');
        $sheet->setCellValue('E1', 'Tanggal');

        $row = 2;
        $no = 1;
        $lastAccount = null;

        foreach ($scores as $score) {
            $isSameAccount = $lastAccount === $score['account_id'];

            // Tampilkan No & Nama hanya jika ini baris pertama dari akun tersebut
            $sheet->setCellValue('A' . $row, $isSameAccount ? '' : $no++);
            $sheet->setCellValue('B' . $row, $isSameAccount ? '' : $score['account_name']);
            $sheet->setCellValue('C' . $row, ucfirst($score['difficulty']));
            $sheet->setCellValue('D' . $row, $score['score']);
            $sheet->setCellValue('E' . $row, $score['created_at']);

            $lastAccount = $score['account_id'];
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
