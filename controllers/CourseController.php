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

        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $imageFileName = null;
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['cover']['tmp_name'];
            $originalFileName = $_FILES['cover']['name'];
            $fileType = $_FILES['cover']['type'];

            // Bisa tambahkan validasi tipe file (misal hanya jpg/png)
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($fileType, $allowedTypes)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tipe file tidak didukung. Hanya JPG dan PNG yang diperbolehkan.'
                ]);
                exit;
            }

            $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $imageFileName = uniqid('course_') . '.' . $ext;

            $destPath = $uploadDir . $imageFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan file gambar.'
                ]);
                exit;
            }

            $imageFileName = "https://alope.id/quiz.alope.id/uploads/" . $imageFileName;
        }

        try {
            $stmt = $pdo->prepare("
                INSERT INTO
                    courses (title, cover, description, body, account_id)
                VALUES (:title, :cover, :description, :body, :account_id)
            ");

            $result = $stmt->execute([
                "title"         => $title,
                "cover"         => $imageFileName,
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

    public function updateCourse() {
        global $pdo;

        // request parameters
        $id                 = $_POST['id'] ?? '';
        $title              = $_POST['title'] ?? '';
        $description        = $_POST['description'] ?? '';
        $body               = $_POST['body'] ?? '';
        $account_id         = $_POST['account_id'] ?? '';
    
        header('Content-Type: application/json');

        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $imageFileName = null;
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['cover']['tmp_name'];
            $originalFileName = $_FILES['cover']['name'];
            $fileType = $_FILES['cover']['type'];

            // Bisa tambahkan validasi tipe file (misal hanya jpg/png)
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($fileType, $allowedTypes)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tipe file tidak didukung. Hanya JPG dan PNG yang diperbolehkan.'
                ]);
                exit;
            }

            $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $imageFileName = uniqid('course_') . '.' . $ext;

            $destPath = $uploadDir . $imageFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan file gambar.'
                ]);
                exit;
            }

            $imageFileName = "https://alope.id/quiz.alope.id/uploads/" . $imageFileName;
        }

        try {
            
            if($imageFileName !== null) {
                $stmt = $pdo->prepare("
                    UPDATE
                        courses 
                    SET
                        title = :title,
                        cover = :cover,
                        description = :description,
                        body = :body,
                        account_id = :account_id
                    WHERE
                        id = :id
                ");

                $result = $stmt->execute([
                    "id"            => $id,
                    "title"         => $title,
                    "cover"         => $imageFileName,
                    "description"   => $description,
                    "body"          => $body,
                    "account_id"    => $account_id,
                ]);
            } else{
                $stmt = $pdo->prepare("
                    UPDATE
                        courses 
                    SET
                        title = :title,
                        description = :description,
                        body = :body,
                        account_id = :account_id
                    WHERE
                        id = :id
                ");

                $result = $stmt->execute([
                    "id"            => $id,
                    "title"         => $title,
                    "description"   => $description,
                    "body"          => $body,
                    "account_id"    => $account_id,
                ]);
            }

            
            if ($result) {
                // Making Response Success Update
                http_response_code(200);  // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Update Course berhasil',
                    'data' => []
                ]);
            } else {
                // Making Response Error Update
                http_response_code(400);  // status code Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal Update Course'
                ]);
            }
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }
    
        exit;
    } 

    public function deleteCourse(){
        global $pdo;

        header('Content-Type: application/json');

        // request parameters
        $id = $_POST["id"];

        try {
            $stmt = $pdo->prepare("
                DELETE FROM
                    courses
                WHERE
                    id = :id
            ");

            $result = $stmt->execute([
                "id" => $id,
            ]);
            
            if ($result) {
                // Making Response Success Delete
                http_response_code(200);  // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Delete Course berhasil',
                    'data' => []
                ]);
            } else {
                // Making Response Error Delete
                http_response_code(400);  // status code Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal Delete Course'
                ]);
            }
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }

    }
}
