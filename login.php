<?php
include "config/db.php";
session_start();

$message = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // 🔍 user खोज्ने
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        // 🔐 password verify
        if (password_verify($password, $user['password'])) {

            // ✅ SESSION SET (NO CHANGE)
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // 🍪 COOKIE (REMEMBER ME - ONLY ADDITION)
            if(isset($_POST['remember'])){
                setcookie("user_id", $user['id'], time() + (7 * 24 * 60 * 60), "/");
                setcookie("user_role", $user['role'], time() + (7 * 24 * 60 * 60), "/");
            }

            // 🔁 ROLE BASE REDIRECT (NO CHANGE)
            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            }
            elseif ($user['role'] == 'teacher') {
                header("Location: teacher/dashboard.php");
            }
            else {
                header("Location: user/dashboard.php");
            }

            exit;

        } else {
            $message = "❌ Wrong password!";
        }

    } else {
        $message = "❌ User not found!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }

        .container {
            width: 400px;
            background: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        input[type="checkbox"]{
           height: 20px;
           width: 20px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #4facfe;
            color: white;
        }

        button:hover {
            background: #008cff;
        }
    </style>

</head>

<body>

    <div class="container">

        <h2 style="text-align:center;">Login</h2>

        <p style="color:red;text-align:center;">
            <?php echo $message; ?>
        </p>

        <form method="POST">

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <!-- ONLY ADD (NO KEY CHANGE) -->
            <div class="d-flex gap-2 align-items-center mb-3">
                <input type="checkbox" name="remember">
                Remember Me
            </div>

            <button type="submit" name="login">Login</button>

        </form>

        <p style="text-align:center;margin-top:15px;">
            No account? <a href="register.php">Register here</a>
        </p>

        <a href="index.php">Back To Home</a>

    </div>

</body>

</html>