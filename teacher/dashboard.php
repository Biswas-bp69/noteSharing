<?php
include "../config/auth.php";
include "../config/db.php";
include "../includes/header.php";

checkLogin();
checkRole('teacher');

$user_id = $_SESSION['user_id'];

/* ---------------------------
   📄 MY NOTES COUNT
----------------------------*/
$myNotes = $conn->query("
SELECT COUNT(*) as c FROM notes WHERE uploaded_by='$user_id'
")->fetch_assoc()['c'];

/* ---------------------------
   ⬇ TOTAL DOWNLOADS (MY NOTES)
----------------------------*/
$totalDownloads = $conn->query("
SELECT COUNT(downloads.id) as c
FROM downloads
JOIN notes ON downloads.note_id = notes.id
WHERE notes.uploaded_by='$user_id'
")->fetch_assoc()['c'];

/* ---------------------------
   👥 UNIQUE USERS (DOWNLOADERS)
----------------------------*/
$uniqueUsers = $conn->query("
SELECT COUNT(DISTINCT downloads.user_id) as c
FROM downloads
JOIN notes ON downloads.note_id = notes.id
WHERE notes.uploaded_by='$user_id'
")->fetch_assoc()['c'];

/* ---------------------------
   ❤️ FAVORITES COUNT (MY NOTES)
----------------------------*/
$favoriteCount = $conn->query("
SELECT COUNT(favorites.id) as c
FROM favorites
JOIN notes ON favorites.note_id = notes.id
WHERE notes.uploaded_by='$user_id'
")->fetch_assoc()['c'];
?>

<link rel="stylesheet" href="/notedemo/assets/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/style.css">



<div class="dashboard-layout" id="dashboardLayout">

    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <button id="toggleBtn">
                <i class="fa-solid fa-angle-left"></i>
            </button>
        </div>

        <section class="mt-5 pt-3">
            <div class="row">
                <div class="row welcomeBg m-0 p-0">
                    <div class="col-lg-6">
                        <div class="">
                            <div class="welcomeActive">
                                Session Active: Semester A-24
                                <h1 class="welcomeMessage ">Welcome Back
                                    <?= $_SESSION['name'] ?>
                                </h1>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- STATS -->
                <div class=" col-lg-3 p-3">
                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <h3 class=" m-0 ">
                                <span><i class="fa-solid fa-users"></i></span>
                                <p class="m-0">My Note</p>
                            </h3>
                        </div>
                        <h2 class="fw-bold m-0 ms-3 mt-4 text-white">
                            <?php echo $myNotes; ?>
                        </h2>
                    </div>
                </div>

                <div class=" col-lg-3 p-3">
                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <h3 class=" m-0 ">
                                <span><i class="fa-solid fa-download"></i></span>
                                <p class="m-0">Downloads</p>
                            </h3>
                        </div>
                        <h2 class="fw-bold m-0 ms-3 mt-4 text-white">
                            <?php echo $totalDownloads; ?>
                        </h2>
                    </div>
                </div>

                <div class=" col-lg-3 p-3">
                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <h3 class=" m-0 ">
                                <span><i class="fa-solid fa-user"></i></span>
                                <p class="m-0">Unique Users</p>
                            </h3>
                        </div>
                        <h2 class="fw-bold m-0 ms-3 mt-4 text-white">
                            <?php echo $uniqueUsers; ?>
                        </h2>
                    </div>
                </div>
                <div class=" col-lg-3 p-3">

                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <h3 class=" m-0 ">
                                <span><i class="fa-solid fa-heart"></i></span>
                                <p class="m-0">Favourite</p>
                            </h3>
                        </div>
                        <h2 class="fw-bold m-0 ms-3 mt-4 text-white">
                            <?php echo $favoriteCount; ?>
                        </h2>
                    </div>
                </div>
            </div>
        </section>













    </main>

</div>
















<?php include "../includes/footer.php"; ?>