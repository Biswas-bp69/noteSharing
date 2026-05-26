<?php
include "../config/auth.php";
include "../config/db.php";

checkLogin();

$user_id = $_SESSION['user_id'];

$message = "";

/* ================= USER ================= */

$user = $conn->query("
SELECT * FROM users 
WHERE id='$user_id'
")->fetch_assoc();


/* ================= UPDATE PROFILE ================= */

if(isset($_POST['update_profile'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $checkEmail = $conn->query("
        SELECT * FROM users
        WHERE email='$email'
        AND id != '$user_id'
    ");

    if($checkEmail->num_rows > 0){

        $message = "
        <div class='alert alert-danger'>
            ❌ Email already exists
        </div>";

    }else{

        $conn->query("
            UPDATE users 
            SET name='$name',
                email='$email'
            WHERE id='$user_id'
        ");

        $_SESSION['name'] = $name;

        $message = "
        <div class='alert alert-success'>
            ✅ Profile Updated Successfully
        </div>";

        $user = $conn->query("
        SELECT * FROM users 
        WHERE id='$user_id'
        ")->fetch_assoc();
    }
}


/* ================= PROFILE IMAGE UPLOAD ================= */

if(isset($_POST['upload_image'])){

    if($_FILES['profile_image']['name'] != ""){

        $image = time() . "_" . $_FILES['profile_image']['name'];

        $tmp = $_FILES['profile_image']['tmp_name'];

        $folder = "../uploads/profile/";

        if(!is_dir($folder)){
            mkdir($folder, 0777, true);
        }

        move_uploaded_file($tmp, $folder . $image);

        $conn->query("
            UPDATE users 
            SET profile_image='$image'
            WHERE id='$user_id'
        ");

        $message = "
        <div class='alert alert-success'>
            ✅ Profile Image Uploaded
        </div>";

        $user['profile_image'] = $image;
    }
}


/* ================= CHANGE PASSWORD ================= */

if(isset($_POST['change_password'])){

    $current = $_POST['current_password'];

    $new = $_POST['new_password'];

    $confirm = $_POST['confirm_password'];

    if(password_verify($current, $user['password'])){

        if($new == $confirm){

            $hashed = password_hash($new, PASSWORD_DEFAULT);

            $conn->query("
                UPDATE users 
                SET password='$hashed'
                WHERE id='$user_id'
            ");

            $message = "
            <div class='alert alert-success'>
                ✅ Password Changed Successfully
            </div>";

        }else{

            $message = "
            <div class='alert alert-danger'>
                ❌ Password Not Match
            </div>";
        }

    }else{

        $message = "
        <div class='alert alert-danger'>
            ❌ Wrong Current Password
        </div>";
    }
}


/* ================= FORGOT PASSWORD ================= */

if(isset($_POST['forgot_password'])){

    $email = $_POST['email'] ?? '';

    $role = $_POST['role'] ?? '';

    $newpass = $_POST['newpass'] ?? '';

    $check = $conn->query("
        SELECT * FROM users
        WHERE email='$email'
        AND role='$role'
    ");

    if($check->num_rows > 0){

        $hashed = password_hash($newpass, PASSWORD_DEFAULT);

        $conn->query("
            UPDATE users 
            SET password='$hashed'
            WHERE email='$email'
            AND role='$role'
        ");

        $message = "
        <div class='alert alert-success'>
            ✅ Password Reset Successful
        </div>";

    }else{

        $message = "
        <div class='alert alert-danger'>
            ❌ User Not Found
        </div>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Profile</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="../assets/css/profile.css">

</head>

<body>

    <div class="container py-5">

        <div class="row g-4">

            <!-- PROFILE SECTION -->

            <div class="col-lg-6">

                <div class="box text-center h-100">

                    <?= $message; ?>

                    <!-- PROFILE INFO -->

                    <div class="profileInfoBox">

                        <!-- EDIT BUTTON -->

                        <div class="editProfileBtn" onclick="showEditProfile()">

                            <i class="fa-solid fa-pen"></i>

                        </div>

                           

                        <!-- IMAGE -->

                        <?php if($user['profile_image']) { ?>

                        <img src="../uploads/profile/<?php echo $user['profile_image']; ?>" class="profileImg">

                        <?php } else { ?>

                        <div class="avatar">
                            <?= strtoupper(substr($user['name'],0,1)); ?>
                        </div>

                        <?php } ?>

                        <!-- NAME -->

                        <h4>
                            <?= $user['name']; ?>
                        </h4>

                        <!-- EMAIL -->

                        <p>
                            📧
                            <?= $user['email']; ?>
                        </p>

                        <!-- ROLE -->

                        <p>
                            🎭 Role:
                            <b>
                                <?= ucfirst($user['role']); ?>
                            </b>
                        </p>

                    </div>



                    <!-- EDIT PROFILE SECTION -->

                    <div class="editProfileSection" id="editProfileSection" style="display:none;">

                        <h5 class="text-center">
                            Update Profile
                        </h5>

                        <form method="POST" onsubmit="return confirmUpdate()">

                            <input type="text" name="name" class="form-control mb-3" placeholder="Full Name"
                                value="<?= $user['name']; ?>">

                            <input type="email" name="email" class="form-control mb-3" placeholder="Email"
                                value="<?= $user['email']; ?>">

                            <button name="update_profile" class="btn btn-primary w-100">

                                Update Profile

                            </button>

                        </form>

                    </div>



                    <!-- IMAGE UPLOAD -->

                    <div class="mt-4">

                        <h5 class="text-center">
                            Upload Profile Image
                        </h5>

                        <form method="POST" enctype="multipart/form-data">

                            <input type="file" name="profile_image" class="form-control mb-3">

                            <button name="upload_image" class="btn btn-dark w-100">

                                Upload Image

                            </button>

                        </form>

                    </div>

                </div>

            </div>



            <!-- PASSWORD SECTION -->

            <div class="col-lg-6">

                <!-- CHANGE PASSWORD -->

                <div class="box h-100" id="changeBox">

                    <h5 class="text-center">
                        Change Password
                    </h5>

                    <form method="POST">

                        <!-- OLD PASSWORD -->

                        <label class="mb-2">
                            Old Password
                        </label>

                        <div style="position:relative;">

                            <input type="password" id="oldPassword" name="current_password" class="form-control"
                                placeholder="Current Password" style="padding-right:45px;">

                            <i class="fa-solid fa-eye" onclick="togglePass('oldPassword', this)" style="
                           position:absolute;
                           right:15px;
                           top:50%;
                           transform:translateY(-50%);
                           cursor:pointer;
                           color:#64748b;
                           ">
                            </i>

                        </div>



                        <!-- NEW PASSWORD -->

                        <label class="mb-2 mt-3">
                            New Password
                        </label>

                        <div style="position:relative;">

                            <input type="password" id="newPassword" name="new_password" class="form-control"
                                placeholder="New Password" style="padding-right:45px;">

                            <i class="fa-solid fa-eye" onclick="togglePass('newPassword', this)" style="
                           position:absolute;
                           right:15px;
                           top:50%;
                           transform:translateY(-50%);
                           cursor:pointer;
                           color:#64748b;
                           ">
                            </i>

                        </div>



                        <!-- CONFIRM PASSWORD -->

                        <label class="mb-2 mt-3">
                            Confirm Password
                        </label>

                        <div style="position:relative;">

                            <input type="password" id="confirmPassword" name="confirm_password" class="form-control"
                                placeholder="Confirm Password" style="padding-right:45px;">

                            <i class="fa-solid fa-eye" onclick="togglePass('confirmPassword', this)" style="
                           position:absolute;
                           right:15px;
                           top:50%;
                           transform:translateY(-50%);
                           cursor:pointer;
                           color:#64748b;
                           ">
                            </i>

                        </div>

                        <button name="change_password" class="btn btn-primary w-100 mt-4">

                            Change Password

                        </button>

                    </form>

                    <a href="#" onclick="showForgot()" class="d-block mt-3 text-center">

                        Forgot Password?

                    </a>

                </div>



                <!-- FORGOT PASSWORD -->

                <div class="box h-100" id="forgotBox" style="display:none;">

                    <h5 class="text-center">
                        Forgot Password
                    </h5>

                    <form method="POST">

                        <label class="mb-2">
                            Email
                        </label>

                        <input type="email" name="email" class="form-control mb-3" placeholder="Email">


                        <label class="mb-2">
                            Select Role
                        </label>

                        <select name="role" class="form-control mb-3">

                            <option value="">
                                Select Role
                            </option>

                            <option value="admin">
                                Admin
                            </option>

                            <option value="teacher">
                                Teacher
                            </option>

                            <option value="student">
                                Student
                            </option>

                        </select>


                        <label class="mb-2">
                            New Password
                        </label>

                        <div style="position:relative;">

                            <input type="password" id="forgotPass" name="newpass" class="form-control"
                                placeholder="New Password" style="padding-right:45px;">

                            <i class="fa-solid fa-eye" onclick="togglePass('forgotPass', this)" style="
                           position:absolute;
                           right:15px;
                           top:50%;
                           transform:translateY(-50%);
                           cursor:pointer;
                           color:#64748b;
                           ">
                            </i>

                        </div>

                        <button name="forgot_password" class="btn btn-danger w-100 mt-4">

                            Reset Password

                        </button>

                    </form>

                    <a href="#" onclick="showChange()" class="d-block mt-3 text-center">

                        Back to Change Password

                    </a>


                </div>

            </div>

        </div>

    </div>
    <div class="text-center">
        <a href="./dashboard.php" class="btn btn-primary">Back To Home</a>

    </div>







    <script>

        setTimeout(function () {

            let alertBox = document.querySelector('.alert');

            if (alertBox) {

                alertBox.style.transition = "0.7s ease";
                alertBox.style.opacity = "0";
                alertBox.style.transform = "translateY(-10px)";

                setTimeout(() => {

                    alertBox.style.display = "none";

                }, 250);

            }

        }, 2000);



        function showEditProfile() {

            let section =
                document.getElementById("editProfileSection");

            if (section.style.display === "none") {

                section.style.display = "block";

            } else {

                section.style.display = "none";
            }
        }


        function confirmUpdate() {

            return confirm(
                "Are you sure you want to update your profile?"
            );
        }


        function togglePass(id, icon) {

            let input =
                document.getElementById(id);

            if (input.type === "password") {

                input.type = "text";

                icon.classList.remove("fa-eye");

                icon.classList.add("fa-eye-slash");

            } else {

                input.type = "password";

                icon.classList.remove("fa-eye-slash");

                icon.classList.add("fa-eye");
            }
        }


        function showForgot() {

            document.getElementById("changeBox").style.display = "none";

            document.getElementById("forgotBox").style.display = "block";
        }


        function showChange() {

            document.getElementById("forgotBox").style.display = "none";

            document.getElementById("changeBox").style.display = "block";
        }

    </script>

</body>

</html>