<?php
session_start();
require_once 'config.php';

// Hibák megjelenítése
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    echo "Form elküldve<br>"; // Debug üzenet
    
    $username = $_POST['registerUsername'];
    $email = $_POST['registerEmail'];
    $password = $_POST['registerPassword'];
    $password_confirm = $_POST['registerPasswordConfirm'];
    
    // Debug: Kiíratjuk a kapott értékeket
    echo "Kapott adatok:<br>";
    echo "Username: " . $username . "<br>";
    echo "Email: " . $email . "<br>";
    
    $errors = [];

    // Validációk
    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $errors[] = "Minden mező kitöltése kötelező";
    }

    if ($password !== $password_confirm) {
        $errors[] = "A jelszavak nem egyeznek";
    }

    if (empty($errors)) {
        try {
            // Először próbáljuk meg közvetlenül beszúrni az adatokat
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            echo "SQL lekérdezés: " . $sql . "<br>"; // Debug üzenet
            
            $stmt = $conn->prepare($sql);
            
            // Az értékek bekötése és kiíratása
            $params = [
                ':username' => $username,
                ':email' => $email,
                ':password' => $password
            ];
            
            echo "Paraméterek:<br>";
            print_r($params);
            echo "<br>";
            
            $result = $stmt->execute($params);
            
            if ($result) {
                echo "Sikeres beszúrás! Utolsó beszúrt ID: " . $conn->lastInsertId() . "<br>";
                $_SESSION['success'] = "Sikeres regisztráció!";
                header("Location: login.php");
                exit();
            } else {
                echo "Hiba a beszúrás során. SQL hiba info:<br>";
                print_r($stmt->errorInfo());
                $errors[] = "Hiba történt a regisztráció során";
            }
            
        } catch (PDOException $e) {
            echo "PDO Hiba történt: " . $e->getMessage() . "<br>";
            echo "Hibakód: " . $e->getCode() . "<br>";
            $errors[] = "Adatbázis hiba történt: " . $e->getMessage();
        }
    }

    if (!empty($errors)) {
        echo "Hibák:<br>";
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --volan-blue: #004b93;
            --volan-yellow: #ffd800;
        }
        
        body {
            background: linear-gradient(135deg, #00008b 0%, #323232 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }
        
        .register-container {
            max-width: 450px;
            padding: 2.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .icon-container {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeIn 1s ease-in;
            position: relative;
        }
        
        .icon-container i {
            font-size: 3.5rem;
            color: var(--volan-blue);
            background: linear-gradient(45deg, var(--volan-blue), #0066cc);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .icon-container::after {
            content: '+';
            position: absolute;
            font-size: 2rem;
            color: var(--volan-yellow);
            font-weight: bold;
            right: 35%;
            top: -5px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        form div {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        label {
            color: var(--volan-blue);
            font-weight: 600;
        }
        
        #registerUsername,
        #registerEmail,
        #registerPassword,
        #registerPasswordConfirm {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        #registerUsername:focus,
        #registerEmail:focus,
        #registerPassword:focus,
        #registerPasswordConfirm:focus {
            border-color: var(--volan-blue);
            box-shadow: 0 0 0 0.2rem rgba(0,75,147,0.15);
            outline: none;
        }
        
        button[name="register"] {
            background: linear-gradient(45deg, orange, #FF4500);
            color: white;
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }
        
        button[name="register"]:hover {
            background: linear-gradient(45deg, #FF4500, orange);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,75,147,0.2);
        }
        
        .login-link {
            color: var(--volan-blue);
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .login-link:hover {
            color: #0066cc;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .text-center {
            text-align: center;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .me-1 {
            margin-right: 0.25rem;
        }
    </style>
</head>
<body>
    <?php
    if (isset($errors)) {
        foreach ($errors as $error) {
            echo '<div style="color: red;">' . $error . '</div>';
        }
    }
    ?>

 <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="register-container">
            <div class="icon-container">
            <svg xmlns="http://www.w3.org/2000/svg" style="max-width: 40%;" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M488 128h-8V80c0-44.8-99.2-80-224-80S32 35.2 32 80v48h-8c-13.3 0-24 10.7-24 24v80c0 13.3 10.8 24 24 24h8v160c0 17.7 14.3 32 32 32v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32v-32h192v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32v-32h6.4c16 0 25.6-12.8 25.6-25.6V256h8c13.3 0 24-10.8 24-24v-80c0-13.3-10.8-24-24-24zM112 400c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm16-112c-17.7 0-32-14.3-32-32V128c0-17.7 14.3-32 32-32h256c17.7 0 32 14.3 32 32v128c0 17.7-14.3 32-32 32H128zm272 112c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>            </div>
    <form action="register.php" method="POST">
        <div>
            <label for="registerUsername">Felhasználónév:</label>
            <input type="text" id="registerUsername" name="registerUsername" required>
        </div>
        
        <div>
            <label for="registerEmail">Email:</label>
            <input type="email" id="registerEmail" name="registerEmail" required>
        </div>
        
        <div>
            <label for="registerPassword">Jelszó:</label>
            <input type="password" id="registerPassword" name="registerPassword" required>
        </div>
        
        <div>
            <label for="registerPasswordConfirm">Jelszó megerősítése:</label>
            <input type="password" id="registerPasswordConfirm" name="registerPasswordConfirm" required>
        </div>
        
        <button type="submit" name="register">Regisztráció</button>
    </form>
<div class="text-center mt-4">
                <p class="mb-0">Már van fiókod? 
                    <a href="login.php" class="login-link">
                        <i class="fas fa-sign-in-alt me-1"></i>Bejelentkezés
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>