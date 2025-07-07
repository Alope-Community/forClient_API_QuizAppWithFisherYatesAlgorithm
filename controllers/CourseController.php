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
            
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
    
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Ambil data Materi berhasil',
                'data' => $course
            ]);
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }
    
        exit;
    } 

    public function createCourse() {
        global $pdo;

        // request parameters
        $title              = $_POST['title'] ?? '';
        $description        = $_POST['description'] ?? '';
        $body               = $_POST['body'] ?? '';
        $account_id         = $_POST['account_id'] ?? '';
    
        header('Content-Type: application/json');

        try {
            $stmt = $pdo->prepare("
                INSERT INTO
                    courses (title, description, body, account_id)
                VALUES (:title, :description, :body, :account_id)
            ");

            $result = $stmt->execute([
                "title"         => $title,
                "description"   => $description,
                "body"          => $body,
                "account_id"    => $account_id,
            ]);
            
            if ($result) {
                // Making Response Success Insert
                http_response_code(200);  // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Tambah Course berhasil',
                    'data' => []
                ]);
            } else {
                // Making Response Error Insert
                http_response_code(400);  // status code Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal Tambah Course'
                ]);
            }
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }
    
        exit;
    } 
}
