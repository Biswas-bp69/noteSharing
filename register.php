<?php
include "config/db.php";
session_start();

$message = "";
if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // force student role only
    $role = $_POST['role'];

    // password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // check duplicate email
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check);

    if ($result->num_rows > 0) {
        $message = "❌ Email already exists!";
    } else {

        $sql = "INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$hashedPassword', '$role')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                alert('Registration successful! Please login.');
                window.location.href = 'login.php';
            </script>";
            exit();
        }else {
            $message = "❌ Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <style>
        /* Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }

        /* Main Container */
        .container {
            width: 400px;
            background: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Heading */
        .container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        /* Labels */
        label {
            font-size: 14px;
            font-weight: 500;
            color: #444;
        }

        /* Inputs & Select */
        input,
        select {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }

        input:focus,
        select:focus {
            border-color: #4facfe;
            box-shadow: 0 0 8px rgba(79, 172, 254, 0.4);
        }

        /* Button */
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #4facfe;
            color: white;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #008cff;
        }

        /* Error Message */
        p {
            text-align: center;
            margin-bottom: 15px;
        }

        p[style] {
            font-size: 14px;
            font-weight: 500;
        }

        /* Links */
        a {
            text-decoration: none;
            color: #4facfe;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media(max-width: 450px) {
            .container {
                width: 90%;
                padding: 25px;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <h2>Register Page</h2>

        <p style="color:green;">
            <?php echo $message; ?>
        </p>

        <form method="POST">

            <label>Name:</label><br>
            <input type="text" name="name" required><br><br>

            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <label>Role:</label><br>
            <select name="role">
                <option value="student">Student</option>
                <option value="teacher" >Teacher</option>
            </select><br><br> 

            <button type="submit" name="register">Register</button>

            <p>Do You Have Account ? <a href="login.php">Login Now</a></p>

            <a href="index.php">Back To Home</a>
        </form>
    </div>

</body>

</html>