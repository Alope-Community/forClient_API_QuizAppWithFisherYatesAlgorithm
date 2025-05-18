<?php

// require_once __DIR__ . './../config/database.php';


class AuthController {
    public function login() {
        global $pdo;

        // request parameters
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
    
        header('Content-Type: application/json');

        try {
            $stmt = $pdo->prepare("SELECT * FROM accounts WHERE username = :username AND password = :password");
            $stmt->execute([
                'username' => $username,
                'password' => $password,
            ]);
            
            $account = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($account) {
                // Making Response Success Get Data
                http_response_code(200); // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'data' => [
                        'name' => $account['name'],
                        'username' => $account['username'],
                        'role' => $account['role']
                    ]
                ]);
            } else {
                // Making Response Error Get Data
                http_response_code(401); // status code Unauthorized
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Username atau password salah'
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

    public function register() {
        global $pdo;

        // request parameters
        $name       = $_POST['name'] ?? '';
        $username   = $_POST['username'] ?? '';
        $password   = $_POST['password'] ?? '';

        header('Content-Type: application/json');

        try {
            // Query Insert Data Account
            $stmt = $pdo->prepare("INSERT INTO accounts (name, username, password, role) VALUES (:name, :username, :password, 'user')");
            $result = $stmt->execute([
                'name'      => $name,
                'username'  => $username,
                'password'  => $password,
            ]);

            if ($result) {
                // Get inserted account
                $stmt = $pdo->prepare("SELECT * FROM accounts WHERE username = :username");
                $stmt->execute(['username' => $username]);
                $account = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Making Response Success Insert Account
                http_response_code(200);  // status code OK
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Registrasi berhasil',
                    'data' => [
                        'username' => $account['username'],
                        'role' => $account['role']
                    ]
                ]);
            } else {
                // Making Response Error Insert Account
                http_response_code(400);  // status code Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal melakukan registrasi'
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
