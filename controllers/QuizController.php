<?php
require_once 'tool/fisherYatesShuffle.php';

class QuizController {
    public function questions() {
        global $pdo;

        try {
            // $stmt = $pdo->prepare("SELECT * FROM questions ORDER BY RAND() LIMIT 10");

            $stmt = $pdo->prepare("SELECT * FROM questions");
            $stmt->execute();
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
}