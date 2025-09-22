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
    $audioFileName = null;

    // Handle Cover Image Upload
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['cover']['tmp_name'];
        $originalFileName = $_FILES['cover']['name'];
        $fileType = $_FILES['cover']['type'];

        // Validasi tipe file gambar
        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($fileType, $allowedImageTypes)) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Tipe file gambar tidak didukung. Hanya JPG dan PNG yang diperbolehkan.'
            ]);
            exit;
        }

        $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $imageFileName = uniqid('course_cover_') . '.' . $ext;

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

    // Handle Audio Upload
    if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['audio']['tmp_name'];
        $originalFileName = $_FILES['audio']['name'];
        $fileType = $_FILES['audio']['type'];
        $fileSize = $_FILES['audio']['size'];

        // Validasi tipe file audio
        $allowedAudioTypes = [
            'audio/mpeg', 
            'audio/mp3', 
            'audio/wav', 
            'audio/ogg', 
            'audio/m4a',
            'audio/mp4',
            'audio/aac'
        ];
        
        if (!in_array($fileType, $allowedAudioTypes)) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Tipe file audio tidak didukung. Format yang diperbolehkan: MP3, WAV, OGG, M4A, AAC.'
            ]);
            exit;
        }

        // Validasi ukuran file (misal maksimal 10MB)
        $maxFileSize = 10 * 1024 * 1024; // 10MB
        if ($fileSize > $maxFileSize) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Ukuran file audio terlalu besar. Maksimal 10MB.'
            ]);
            exit;
        }

        $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $audioFileName = uniqid('course_audio_') . '.' . $ext;

        $destPath = $uploadDir . $audioFileName;

        if (!move_uploaded_file($fileTmpPath, $destPath)) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan file audio.'
            ]);
            exit;
        }

        $audioFileName = "https://alope.id/quiz.alope.id/uploads/" . $audioFileName;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO
                courses (title, cover, audio, description, body, account_id)
            VALUES (:title, :cover, :audio, :description, :body, :account_id)
        ");

        $result = $stmt->execute([
            "title"         => $title,
            "cover"         => $imageFileName,
            "audio"         => $audioFileName,
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
                'data' => [
                    'course_id' => $pdo->lastInsertId(),
                    'cover_url' => $imageFileName,
                    'audio_url' => $audioFileName
                ]
            ]);
        } else {
            // Making Response Error Insert
            http_response_code(400);  // status code Bad Request
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal Tambah Course'
            ]);
        }
    }catch (PDOException $e) {
        // Hapus file yang sudah diupload jika terjadi error database
        if ($imageFileName && file_exists($uploadDir . basename($imageFileName))) {
            unlink($uploadDir . basename($imageFileName));
        }
        if ($audioFileName && file_exists($uploadDir . basename($audioFileName))) {
            unlink($uploadDir . basename($audioFileName));
        }
        
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada server.',
            'debug' => $e->getMessage() // Hapus ini di production
        ]);
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
    $audioFileName = null;
    $oldImageFile = null;
    $oldAudioFile = null;

    try {
        // Get existing file paths untuk cleanup nanti jika ada file baru
        $stmt = $pdo->prepare("SELECT cover, audio FROM courses WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $existingData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingData) {
            $oldImageFile = $existingData['cover'];
            $oldAudioFile = $existingData['audio'];
        }

        // Handle Cover Image Upload
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['cover']['tmp_name'];
            $originalFileName = $_FILES['cover']['name'];
            $fileType = $_FILES['cover']['type'];

            // Validasi tipe file gambar
            $allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($fileType, $allowedImageTypes)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tipe file gambar tidak didukung. Hanya JPG dan PNG yang diperbolehkan.'
                ]);
                exit;
            }

            $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $imageFileName = uniqid('course_cover_') . '.' . $ext;

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

        // Handle Audio Upload
        if (isset($_FILES['audio']) && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['audio']['tmp_name'];
            $originalFileName = $_FILES['audio']['name'];
            $fileType = $_FILES['audio']['type'];
            $fileSize = $_FILES['audio']['size'];

            // Validasi tipe file audio
            $allowedAudioTypes = [
                'audio/mpeg', 
                'audio/mp3', 
                'audio/wav', 
                'audio/ogg', 
                'audio/m4a',
                'audio/mp4',
                'audio/aac'
            ];
            
            if (!in_array($fileType, $allowedAudioTypes)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Tipe file audio tidak didukung. Format yang diperbolehkan: MP3, WAV, OGG, M4A, AAC.'
                ]);
                exit;
            }

            // Validasi ukuran file (misal maksimal 10MB)
            $maxFileSize = 10 * 1024 * 1024; // 10MB
            if ($fileSize > $maxFileSize) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Ukuran file audio terlalu besar. Maksimal 10MB.'
                ]);
                exit;
            }

            $ext = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $audioFileName = uniqid('course_audio_') . '.' . $ext;

            $destPath = $uploadDir . $audioFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan file audio.'
                ]);
                exit;
            }

            $audioFileName = "https://alope.id/quiz.alope.id/uploads/" . $audioFileName;
        }

        // Prepare update query berdasarkan file yang diupload
        $updateFields = [];
        $updateParams = [
            "id" => $id,
            "title" => $title,
            "description" => $description,
            "body" => $body,
            "account_id" => $account_id,
        ];

        // Build dynamic update query
        $updateFields[] = "title = :title";
        $updateFields[] = "description = :description";
        $updateFields[] = "body = :body";
        $updateFields[] = "account_id = :account_id";

        if ($imageFileName !== null) {
            $updateFields[] = "cover = :cover";
            $updateParams["cover"] = $imageFileName;
        }

        if ($audioFileName !== null) {
            $updateFields[] = "audio = :audio";
            $updateParams["audio"] = $audioFileName;
        }

        $sql = "UPDATE courses SET " . implode(", ", $updateFields) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute($updateParams);
        
        if ($result) {
            // Delete old files jika ada file baru yang diupload
            if ($imageFileName !== null && $oldImageFile) {
                $oldImagePath = $uploadDir . basename($oldImageFile);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            if ($audioFileName !== null && $oldAudioFile) {
                $oldAudioPath = $uploadDir . basename($oldAudioFile);
                if (file_exists($oldAudioPath)) {
                    unlink($oldAudioPath);
                }
            }

            // Making Response Success Update
            http_response_code(200);  // status code OK
            echo json_encode([
                'status' => 'success',
                'message' => 'Update Course berhasil',
                'data' => [
                    'course_id' => $id,
                    'cover_url' => $imageFileName ?: $oldImageFile,
                    'audio_url' => $audioFileName ?: $oldAudioFile
                ]
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
        // Hapus file baru yang sudah diupload jika terjadi error database
        if ($imageFileName) {
            $newImagePath = $uploadDir . basename($imageFileName);
            if (file_exists($newImagePath)) {
                unlink($newImagePath);
            }
        }
        if ($audioFileName) {
            $newAudioPath = $uploadDir . basename($audioFileName);
            if (file_exists($newAudioPath)) {
                unlink($newAudioPath);
            }
        }
        
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada server.',
            'debug' => $e->getMessage() // Hapus ini di production
        ]);
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
