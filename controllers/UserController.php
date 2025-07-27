<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController {
    public function users() {
        global $pdo;

        // request parameters
        $role     = $_GET['role'] ?? 'user';
    
        header('Content-Type: application/json');

        try {
            $stmt = $pdo->prepare("SELECT * FROM accounts WHERE role = :role");
            $stmt->execute([
                "role" => $role
            ]);
            
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Ambil data User berhasil',
                'data' => $users
            ]);
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }
    
        exit;
    } 

    public function deleteUser() {
        global $pdo;

        // request parameters
        $id     = $_POST['id'] ?? 0;
    
        header('Content-Type: application/json');

        try {
            $stmt = $pdo->prepare("DELETE FROM accounts WHERE id = :id");
            $stmt->execute([
                "id" => $id
            ]);
    
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Delete data User berhasil',
                'data' => []
            ]);
        } catch (PDOException $e) {
            resError('Terjadi kesalahan pada server.', $e->getMessage(), 500);
        }
    
        exit;
    } 
}
