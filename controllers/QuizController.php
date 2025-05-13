<?php
require_once 'tool/fisherYatesShuffle.php';

class QuizController {
    public function questions() {
        global $pdo;

        $difficulty= $_GET["difficulty"];

        try {
            // $stmt = $pdo->prepare("SELECT * FROM questions ORDER BY RAND() LIMIT 10");

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
}