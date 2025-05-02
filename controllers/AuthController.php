<?php

// require_once __DIR__ . './../config/database.php';

class AuthController {
    public function login() {
        global $pdo; // ambil koneksi dari file database

        // Misal kita terima data dari form (POST)
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Query ke database untuk cari user berdasarkan username
        $stmt = $pdo->prepare("SELECT * FROM accounts WHERE username = :username AND password = :password");
        $stmt->execute([
            'username' => $username,
            'password' => $password,
        ]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');

        if ($account) {
            // Login berhasil
            http_response_code(200);  // Set status code 200 OK
            echo json_encode([
                'status' => 'success',
                'message' => 'Login berhasil',
                'data' => [
                    'username' => $account['username'],
                    'role' => $account['role']
                ]
            ]);
        } else {
            // Login gagal
            http_response_code(401);  // Set status code 401 Unauthorized
            echo json_encode([
                'status' => 'error',
                'message' => 'Username atau password salah'
            ]);
        }

        exit;
    }
}
