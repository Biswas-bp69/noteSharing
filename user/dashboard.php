<?php
include "../config/auth.php";
include "../config/db.php";
include "../includes/header.php";

checkLogin();
checkRole('student');

$user_id = $_SESSION['user_id'];

$search = $_GET['search'] ?? "";

/* ---------------------------
   📊 STATS
----------------------------*/

// total downloads
$totalDownloads = $conn->query("
SELECT COUNT(*) as c FROM downloads WHERE user_id='$user_id'
")->fetch_assoc()['c'];

// total public notes (index)
$totalPublic = $conn->query("
SELECT COUNT(*) as c FROM notes WHERE visibility='public'
")->fetch_assoc()['c'];

// favorites count
$totalFav = $conn->query("
SELECT COUNT(*) as c FROM favorites WHERE user_id='$user_id'
")->fetch_assoc()['c'];


/* ---------------------------
   📦 DOWNLOADS LIST
----------------------------*/

?>





<div class="dashboard-layout" id="dashboardLayout">

    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <button id="toggleBtn">
                <i class="fa-solid fa-angle-left"></i>
            </button>
        </div>

        <div>
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
                    <div class=" col-lg-4 p-3">
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

                    <div class=" col-lg-4 p-3">
                        <div class="card">
                            <div class="d-flex justify-content-between">
                                <h3 class=" m-0 ">
                                    <span><i class="fa-brands fa-leanpub"></i></span>
                                    <p class="m-0">Public Notes</p>
                                </h3>
                            </div>
                            <h2 class="fw-bold m-0 ms-3 mt-4 text-white">
                                <?php echo $totalPublic; ?>
                            </h2>
                        </div>
                    </div>

                    <div class=" col-lg-4 p-3">
                        <div class="card">
                            <div class="d-flex justify-content-between">
                                <h3 class=" m-0 ">
                                    <span><i class="fa-solid fa-heart"></i></span>
                                    <p class="m-0">Favorites</p>
                                </h3>
                            </div>
                            <h2 class="fw-bold m-0 ms-3 mt-4 text-white">
                                <?php echo $totalFav; ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </section>





        </div>

    </main>

</div>



<script src="../assets/js/custom.js"></script>










<?php include "../includes/footer.php"; ?>