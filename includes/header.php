<?php

include_once __DIR__ . "/../config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ================= USER DATA ================= */

$userImage = "";
$userName = "";
$userEmail = "";
$userRole  = "";

if(isset($_SESSION['user_id'])){

    $id = $_SESSION['user_id'];

    $u = $conn->query("
        SELECT name, email, role, profile_image 
        FROM users 
        WHERE id='$id'
    ")->fetch_assoc();

    $userName  = $u['name'];
    $userEmail = $u['email'];
    $userImage = $u['profile_image'];
    $userRole  = $u['role'];
}


/* ================= ROLE BASED PROFILE LINK ================= */

$profileLink = "/notedemo/profile.php";

if($userRole == "admin"){
    $profileLink = "/notedemo/admin/profile.php";
}

if($userRole == "teacher"){
    $profileLink = "/notedemo/teacher/profile.php";
}

if($userRole == "student"){
    $profileLink = "/notedemo/user/profile.php";
}

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/notedemo/assets/css/style.css">

<header class="container-fluid">
    <div class="row m-0 align-items-center">

        <!-- LOGO -->
        <div class="col-lg-2 logo">
            <img src="/notedemo/assets/img/logo.png" alt="note bazar logo">
        </div>

        <!-- SEARCH -->
        <div class="col-lg-6">
            <div class="searchArea d-flex gap-2 align-items-center">
                <input type="search" name="search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>

        <!-- NAV -->
        <div class="navItems col-lg-4 d-flex align-items-center justify-content-end gap-4">

            <?php if(!isset($_SESSION['user_id'])) { ?>
            <a href="../notedemo/index.php">Home</a>
            <?php } ?>

            <?php if(isset($_SESSION['role'])) { ?>

            <?php if($_SESSION['role'] == 'admin') { ?>
            <a href="/notedemo/admin/dashboard.php"> Dashboard</a>
            <?php } ?>

            <?php if($_SESSION['role'] == 'teacher') { ?>
            <a href="/notedemo/teacher/dashboard.php"> Dashboard</a>
            <?php } ?>

            <?php if($_SESSION['role'] == 'student') { ?>
            <a href="/notedemo/user/dashboard.php"> Dashboard</a>
            <?php } ?>


            <!-- ================= PROFILE BUTTON ================= -->

            <a href="<?= $profileLink ?>" style="
                        display:flex;
                        align-items:center;
                        gap:10px;
                        padding:5px 12px;
                        border-radius:8px;
                        text-decoration:none;
                        background:white;
                   " onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">


                <!-- AVATAR -->
                <?php if(!empty($userImage)) { ?>

                <img src="/notedemo/uploads/profile/<?php echo $userImage; ?>" style="
                            width:38px;
                            height:38px;
                            border-radius:50%;
                            object-fit:cover;
                            border:2px solid #2563eb;
                        ">

                <?php } else { ?>

                <div style="
                            width:38px;
                            height:38px;
                            border-radius:50%;
                            background:linear-gradient(135deg,#2563eb,#1e3a8a);
                            color:white;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-weight:700;
                            font-size:15px;
                            text-transform:uppercase;
                        ">
                    <?= strtoupper(substr($userName,0,1)); ?>
                </div>

                <?php } ?>


                <!-- NAME + EMAIL -->
                <div style="display:flex;flex-direction:column;line-height:1.2;">

                    <!-- NAME -->
                    <span style="
                            font-size:15px;
                            font-weight:700;
                            color:#0f172a;
                            max-width:140px;
                            overflow:hidden;
                            text-overflow:ellipsis;
                            white-space:nowrap;
                        ">
                        <?= $userName ?>
                    </span>

                    <!-- EMAIL -->
                    <span style="
                            font-size:10px;
                            color:#94a3b8;
                            max-width:140px;
                            overflow:hidden;
                            text-overflow:ellipsis;
                            white-space:nowrap;
                        ">
                        <?= $userEmail ?>
                    </span>

                </div>

            </a>


            <a href="/notedemo/logout.php">Logout</a>

            <?php } else { ?>

            <a href="/notedemo/login.php">Login</a>
            <a href="/notedemo/register.php">Register</a>

            <?php } ?>

        </div>
    </div>
</header>