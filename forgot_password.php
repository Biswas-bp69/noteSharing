<?php
include "config/db.php";

$message = "";

if(isset($_POST['reset'])){

    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // hash password
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);

    // check user
    $check = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check);

    if($result->num_rows > 0){

        // update password
        $sql = "UPDATE users 
                SET password='$hashed'
                WHERE email='$email'";

        if($conn->query($sql)){

            $message = "✅ Password Updated Successfully";

        }else{

            $message = "❌ Failed";
        }

    }else{

        $message = "❌ Email not found";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">


<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-lg-4">

            <div class="card p-4 shadow">

                <h3 class="mb-3 text-center">
                    Forgot Password
                </h3>

                <?php if($message != "") { ?>

                <div class="alert alert-info">
                    <?php echo $message; ?>
                </div>

                <?php } ?>

                <form method="POST">

                    <!-- EMAIL -->

                    <div class="mb-3">

                        <label>Email</label>

                        <input 
                            type="email"
                            name="email"
                            class="form-control"
                            required
                        >

                    </div>


                    <!-- NEW PASSWORD -->

                    <div class="mb-3">

                        <label>New Password</label>

                        <input 
                            type="password"
                            name="new_password"
                            class="form-control"
                            required
                        >

                    </div>


                    <!-- BTN -->

                    <button 
                        type="submit"
                        name="reset"
                        class="btn btn-primary w-100"
                    >
                        Change Password
                    </button>

                </form>


                <a href="login.php" class="mt-3 text-center d-block">
                    Back To Login
                </a>

            </div>

        </div>

    </div>

</div>

</body>
</html>