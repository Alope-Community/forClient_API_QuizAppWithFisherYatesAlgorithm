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
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function createQuestion() {
        global $pdo;

        // request parameters
        $question       = $_POST['question'] ?? '';
        $image          = $_POST['image'] ?? '';
        $difficulty     = $_POST['difficulty'] ?? '';
        $answer         = $_POST['answer'] ?? '';

        header('Content-Type: application/json');

        try {
            // Query Insert Data Question
            $stmt = $pdo->prepare("INSERT INTO questions (question, image, difficulty, answer) VALUES (:question, :image, :difficulty, :answer)");
            $result = $stmt->execute([
                'question'      => $question,
                'image'         => $image,
                'difficulty'    => $difficulty,
                'answer'        => $answer,
            ]);

            if ($result) {
                // Get inserted Question
                $stmt = $pdo->prepare("SELECT * FROM questions WHERE question = :question");
                $stmt->execute(['question' => $question]);
                $question = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Making Response Success Insert
                http_response_code(200);  // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Tambah Soal berhasil',
                    'data' => [
                        'question_id' => $question['id']
                    ]
                ]);
            } else {
                // Making Response Error Insert
                http_response_code(400);  // status code Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal Tambah Soal'
                ]);
            }
        } catch (PDOException $e) {
            // Making Response Error Server
            http_response_code(500); // status code Internal Server Error
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
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
            // Making Response Error Server
            http_response_code(500); // status code Internal Server Error
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ]);
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
            // Making Response Error Server
            http_response_code(500); // status code Internal Server Error
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ]);
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
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
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
            // Making Response Error Server
            http_response_code(500); // status code Internal Server Error
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ]);
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
            // Making Response Error Server
            http_response_code(500); // status code Internal Server Error
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
            // Making Response Error Server
            http_response_code(500); // status code Internal Server Error
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage()
            ]);
        }

        exit;
    }
}