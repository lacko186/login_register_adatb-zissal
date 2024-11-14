<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];
    
    try {
        // Debug információ
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Debug: Ellenőrizzük a lekért adatokat
        echo "Bejelentkezési kísérlet:<br>";
        echo "Megadott email: " . $email . "<br>";
        echo "Felhasználó található: " . ($user ? 'Igen' : 'Nem') . "<br>";
        
        if ($user && $password === $user['password']) {  // Egyszerű jelszó ellenőrzés
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            echo "Sikeres bejelentkezés!";
            header("Location: index.php"); // vagy ahova szeretnéd irányítani
            exit();
        } else {
            $_SESSION['error'] = "Helytelen email vagy jelszó!";
            echo "Sikertelen bejelentkezés - jelszó nem egyezik";
        }
    } catch(PDOException $e) {
        echo "Adatbázis hiba: " . $e->getMessage();
        $_SESSION['error'] = "Rendszerhiba történt!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --volan-blue: #004b93;
            --volan-yellow: #ffd800;
        }
        
        body {
            background: linear-gradient(135deg, #00008b 0%, #323232 100%);
            height: 100vh;
        }
        
        .login-container {
            max-width: 400px;
            padding: 2.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .icon-container {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeIn 1s ease-in;
        }
        
        .icon-container i {
            font-size: 3.5rem;
            color: var(--volan-blue);
            background: linear-gradient(45deg, var(--volan-blue), #0066cc);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .login-title {
            color: var(--volan-blue);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .btn-volan {
            background: linear-gradient(45deg, orange, #FF4500);
            color: white;
            padding: 0.8rem;
            border: none;
            transition: all 0.3s;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-volan:hover {
	    backgroud-color: yellow;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,75,147,0.2);
            color: white;
        }
        
        .form-control {
            border-left: none;
            padding: 0.8rem;
            border-radius: 0 8px 8px 0;
        }
        
        .form-control:focus {
            border-color: var(--volan-blue);
            box-shadow: 0 0 0 0.2rem rgba(0,75,147,0.15);
        }
        
        .input-group-text {
            background-color: white;
            border-right: none;
            border-radius: 8px 0 0 8px;
            padding: 0.8rem;
        }
        
        .registration-link {
            color: var(--volan-blue);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .registration-link:hover {
            color: #0066cc;
            text-decoration: none;
        }
        
        .form-check-input:checked {
            background-color: var(--volan-blue);
            border-color: var(--volan-blue);
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
        
        .input-group:focus-within {
            box-shadow: 0 0 0 0.2rem rgba(0,75,147,0.15);
            border-radius: 8px;
        }
        
        .form-floating {
            margin-bottom: 1rem;
        }
        
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
                        if (isset($_SESSION['error'])) {
                            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                            unset($_SESSION['error']);
                        }
                        if (isset($_SESSION['success'])) {
                            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                            unset($_SESSION['success']);
                        }
                        ?>
                        <div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-container">
        <div class="icon-container">
        <svg xmlns="http://www.w3.org/2000/svg" style="max-width: 40%" viewBox="0 0 512 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M488 128h-8V80c0-44.8-99.2-80-224-80S32 35.2 32 80v48h-8c-13.3 0-24 10.7-24 24v80c0 13.3 10.8 24 24 24h8v160c0 17.7 14.3 32 32 32v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32v-32h192v32c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32v-32h6.4c16 0 25.6-12.8 25.6-25.6V256h8c13.3 0 24-10.8 24-24v-80c0-13.3-10.8-24-24-24zM112 400c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm16-112c-17.7 0-32-14.3-32-32V128c0-17.7 14.3-32 32-32h256c17.7 0 32 14.3 32 32v128c0 17.7-14.3 32-32 32H128zm272 112c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32z"/></svg>
        </div>
        <form method="POST" action="login.php">
            <div>
                <label for="loginEmail" class="form-label">Email cím:</label>
                <input type="email" id="loginEmail" name="loginEmail" class="form-control" required>
            </div>
            <div>
                <label for="loginPassword" class="form-label">Jelszó:</label>
                <input type="password" id="loginPassword" name="loginPassword" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Bejelentkezés</button>
        </form>
        <div class="text-center mt-4">
            <p class="mb-0">Még nincs fiókod?
                <a href="register.php" class="register-link">
                    <i class="fas fa-user-plus me-1"></i>Regisztrálj!
                </a>
            </p>
        </div>
    </div>
</div>

                    </div>
    
     <!-- Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>