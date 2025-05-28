<?php

class CourseController {
    public function courses() {
        global $pdo;
    
        header('Content-Type: application/json');

        try {
            $stmt = $pdo->prepare("
                SELECT 
                    c.*,
                    a.name AS account_name
                FROM 
                    courses c
                INNER JOIN
                    accounts a
                ON
                    a.id = c.account_id
                ORDER BY 
                    id 
                DESC
            ");

            $stmt->execute();
            
            $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Ambil data Materi berhasil',
                'data' => $scores
            ]);
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }
    
        exit;
    } 
    
    public function showCourse() {
        global $pdo;

        $id = $_GET["id"] ?? 1;
    
        header('Content-Type: application/json');

        try {
            $stmt = $pdo->prepare("
                SELECT 
                    c.*,
                    a.name AS account_name
                FROM 
                    courses c
                INNER JOIN
                    accounts a
                ON
                    a.id = c.account_id
                WHERE
                    c.id = :id
            ");

            $stmt->execute([
                "id" => $id
            ]);
            
            $scores = $stmt->fetch(PDO::FETCH_ASSOC);
    
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Ambil data Materi berhasil',
                'data' => $scores
            ]);
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }
    
        exit;
    } 
}
