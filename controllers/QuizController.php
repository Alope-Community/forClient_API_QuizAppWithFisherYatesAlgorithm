<?php
require_once 'tool/fisherYatesShuffle.php';

class QuizController {

      // //////////////////////////////////////////////////////////////////////////////////////// 
    //      QUESTION SECTION                                                                   //
    //      This section contains methods for managing questions for quiz                      //
    //      Including: listing, creating, updating and deleting questions                      //
    // ////////////////////////////////////////////////////////////////////////////////////////

    public function questions() {
        global $pdo;

        $difficulty= $_GET["difficulty"];
        $for= $_GET["for"] ?? 'user';

        if($for == 'admin'){
            try {
                $stmt = $pdo->prepare("
                    SELECT 
                        q.id AS question_id,
                        q.question,
                        q.difficulty,
                        q.image,
                        q.answer,
                        MIN(o.id) AS option_id,
                        JSON_ARRAYAGG(o.value) AS value
                    FROM 
                        questions q
                    INNER JOIN 
                        options o ON o.question_id = q.id
                    WHERE 
                        q.difficulty = :difficulty
                    GROUP BY 
                        q.id, q.question, q.difficulty, q.image, q.answer;
                ");
                $stmt->execute(['difficulty' => $difficulty]);
                $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'data' => $questions
                ]);
            } catch (PDOException $e) {
                resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
            }
        } else{
            try {
                $stmt = $pdo->prepare("SELECT * FROM questions WHERE difficulty= :difficulty");
                $stmt->execute(['difficulty' => $difficulty]);
                $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                $shuffled = fisherYatesShuffle($questions);
    
                $selectedQuestions = array_slice($shuffled, 0, 10);
    
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'data' => $selectedQuestions
                ]);
            } catch (PDOException $e) {
                resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
            }
        }
    }
    
    public function createQuestion() {
        global $pdo;
    
        header('Content-Type: application/json');
    
        try {
            $question   = $_POST['question'] ?? '';
            $difficulty = $_POST['difficulty'] ?? '';
            $answer     = $_POST['answer'] ?? '';
    
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
    
            $imageFileName = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['image']['tmp_name'];
                $originalFileName = $_FILES['image']['name'];
                $fileSize = $_FILES['image']['size'];
                $fileType = $_FILES['image']['type'];
    
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
                $imageFileName = uniqid('img_') . '.' . $ext;
    
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
    
            $stmt = $pdo->prepare("INSERT INTO questions (question, image, difficulty, answer) VALUES (:question, :image, :difficulty, :answer)");
            $result = $stmt->execute([
                'question'   => $question,
                'image'      => $imageFileName,
                'difficulty' => $difficulty,
                'answer'     => $answer,
            ]);
    
            if ($result) {
                $questionId = $pdo->lastInsertId();
    
                http_response_code(200);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Tambah Soal berhasil',
                    'data' => [
                        'question_id' => $questionId
                    ]
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal Tambah Soal'
                ]);
            }
    
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage()
            ]);
        }
    
        exit;
    }    
    
    public function updateQuestion() {
        global $pdo;

        // request parameters
        $id             = $_POST['id'] ?? '';
        $question       = $_POST['question'] ?? '';
        $image          = $_POST['image'] ?? '';
        $difficulty     = $_POST['difficulty'] ?? '';
        $answer         = $_POST['answer'] ?? '';

        header('Content-Type: application/json');

        try {
            // Query Update Data Question
            $stmt = $pdo->prepare("UPDATE questions SET question=:question, image=:image, difficulty=:difficulty, answer=:answer WHERE id=:id");
            $result = $stmt->execute([
                'id'            => $id,
                'question'      => $question,
                'image'         => $image,
                'difficulty'    => $difficulty,
                'answer'        => $answer,
            ]);

            if ($result) {
                http_response_code(200);  // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Update Soal berhasil',
                    'data' => [
                        'question_id' => $id
                    ]
                ]);
            } else {
                // Making Response Error Insert
                http_response_code(400);  // status code Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal Update Soal'
                ]);
            }
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }

        exit;
    }
    
    public function deleteQuestion() {
        global $pdo;

        // request parameters
        $id = $_POST['id'] ?? '';

        header('Content-Type: application/json');

        try {
            // Query Delete Data Question
            $stmt = $pdo->prepare("DELETE FROM questions WHERE id=:id");
            $result = $stmt->execute([
                'id'            => $id,
            ]);

            if ($result) {
                http_response_code(200);  // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Hapus Soal berhasil',
                    'data' => [
                        'question_id' => $id
                    ]
                ]);
            } else {
                // Making Response Error Delete
                http_response_code(400);  // status code Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal Hapus Soal'
                ]);
            }
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }

        exit;
    }

    // //////////////////////////////////////////////////////////////////////////////////////// 
    //      OPTION SECTION                                                                     //
    //      This section contains methods for managing options/choices for quiz questions      //
    //      Including: listing, creating, updating and deleting options                        //
    // ////////////////////////////////////////////////////////////////////////////////////////

    public function options() {
        global $pdo;

        $question_id= $_GET["question_id"];

        try {
            $stmt = $pdo->prepare("SELECT * FROM options WHERE question_id= :question_id");
            $stmt->execute(['question_id' => $question_id]);
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $shuffled = fisherYatesShuffle($options);
 
            $selectedOptions = array_slice($shuffled, 0, 10);

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $selectedOptions
            ]);
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }
    }

    public function createOption() {
        global $pdo;

        // request parameters
        $question_id        = $_POST['question_id'] ?? '';
        $value              = $_POST['value'] ?? '';

        header('Content-Type: application/json');

        try {
            // Query Insert Data Account
            $stmt = $pdo->prepare("INSERT INTO options (question_id, value) VALUES (:question_id, :value)");
            $result = $stmt->execute([
                'question_id'   => $question_id,
                'value'         => $value,
            ]);

            if ($result) {
                // Making Response Success Insert
                http_response_code(200);  // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Tambah Opsi Jawaban berhasil',
                    'data' => []
                ]);
            } else {
                // Making Response Error Insert
                http_response_code(400);  // status code Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal Tambah Opsi Jawaban'
                ]);
            }
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }

        exit;
    }
    
    public function updateOption() {
        global $pdo;

        // request parameters
        $id        = $_POST['id'] ?? '';
        $value     = $_POST['value'] ?? '';

        header('Content-Type: application/json');

        try {
            // Query Update Data Account
            $stmt = $pdo->prepare("UPDATE options SET value=:value WHERE id=:id");
            $result = $stmt->execute([
                'id'        => $id,
                'value'     => $value,
            ]);

            if ($result) {
                // Making Response Success Update
                http_response_code(200);  // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Update Opsi Jawaban berhasil',
                    'data' => []
                ]);
            } else {
                // Making Response Error Update
                http_response_code(400);  // status code Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal Update Opsi Jawaban'
                ]);
            }
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }

        exit;
    }
    
    public function updateOptions() {
        global $pdo;

        $question_id = $_POST['question_id'] ?? '';
        $option_a    = $_POST['option_a'] ?? '';
        $option_b    = $_POST['option_b'] ?? '';
        $option_c    = $_POST['option_c'] ?? '';
        $option_d    = $_POST['option_d'] ?? '';

        header('Content-Type: application/json');

        try {
            if (empty($question_id)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'question_id tidak boleh kosong'
                ]);
                exit;
            }

            $pdo->beginTransaction();

            $stmtDelete = $pdo->prepare("DELETE FROM options WHERE question_id = :question_id");
            $stmtDelete->execute(['question_id' => $question_id]);

            $stmtInsert = $pdo->prepare("INSERT INTO options (value, question_id) VALUES (:value, :question_id)");

            $options = [
                ['key' => 'A', 'value' => $option_a],
                ['key' => 'B', 'value' => $option_b],
                ['key' => 'C', 'value' => $option_c],
                ['key' => 'D', 'value' => $option_d],
            ];

            foreach ($options as $opt) {
                $stmtInsert->execute([
                    'value' => $opt['value'],
                    'question_id' => $question_id,
                ]);
            }

            $pdo->commit();

            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Update opsi berhasil',
                'data' => ['question_id' => $question_id]
            ]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ]);
        }

        exit;
    }



    public function deleteOption() {
        global $pdo;

        // request parameters
        $id        = $_POST['id'] ?? '';

        header('Content-Type: application/json');

        try {
            // Query Delete Data Account
            $stmt = $pdo->prepare("DELETE FROM options WHERE id=:id");
            $result = $stmt->execute([
                'id'        => $id,
            ]);

            if ($result) {
                // Making Response Success Delete
                http_response_code(200);  // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Hapus Opsi Jawaban berhasil',
                    'data' => []
                ]);
            } else {
                // Making Response Error Delete
                http_response_code(400);  // status code Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal Hapus Opsi Jawaban'
                ]);
            }
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }

        exit;
    }
}