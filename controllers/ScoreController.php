<?php

class ScoreController {
    public function scores() {
        global $pdo;

        // request parameters
        $difficulty     = $_GET['difficulty'] ?? '';
    
        header('Content-Type: application/json');

        try {
            if ($difficulty) {
                $stmt = $pdo->prepare("SELECT * FROM scores WHERE difficulty = :difficulty ORDER BY score DESC");
                $stmt->execute(['difficulty' => $difficulty]);
            } else {
                $stmt = $pdo->prepare("SELECT * FROM scores ORDER BY score DESC");
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
}
