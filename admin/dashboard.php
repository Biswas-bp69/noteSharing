<?php
include "../config/auth.php";
include "../config/db.php";
include "../includes/header.php";


checkLogin();
checkRole('admin');

// DATA
$totalUsers = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$totalNotes = $conn->query("SELECT COUNT(*) as c FROM notes")->fetch_assoc()['c'];
$totalCategories = $conn->query("SELECT COUNT(*) as c FROM categories")->fetch_assoc()['c'];

$recentUsers = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT 5");
$recentNotes = $conn->query("
SELECT notes.*, users.name AS uploader_name
FROM notes
LEFT JOIN users ON notes.uploaded_by = users.id
ORDER BY notes.id DESC
LIMIT 5
");

$topNotes = $conn->query("
SELECT notes.title,
       notes.file,
       users.name AS uploader_name,
       COUNT(downloads.id) AS total_downloads
FROM downloads
JOIN notes ON downloads.note_id = notes.id
LEFT JOIN users ON notes.uploaded_by = users.id
GROUP BY downloads.note_id
ORDER BY total_downloads DESC
LIMIT 10
");

?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../assets/css/style.css">



<div class="dashboard-layout" id="dashboardLayout">

    <?php include "sidebar.php"; ?>

    <main class="main">


        <div class="topbar">
            <button id="toggleBtn">
                <i class="fa-solid fa-angle-left"></i>
            </button>
        </div>




        <!-- यहाँ तिम्रो सबै content राख्ने -->
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
                <div class=" col-lg-4 p-3">
                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <h3 class=" m-0 ">
                                <span><i class="fa-solid fa-users"></i></span>
                                <p class="m-0">Total Users</p>
                            </h3>
                            <span class="cardBoxGraph"><i class="bi bi-graph-up-arrow"></i></span>
                        </div>

                        <h2 class="fw-bold m-0 ms-3 mt-4 text-white">
                            <?php echo $totalUsers; ?>
                        </h2>
                    </div>
                </div>

                <div class=" col-lg-4 p-3">
                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <h3 class=" m-0 ">
                                <span><i class="fa-solid fa-book"></i></span>
                                <p class="m-0">Notes</p>
                            </h3>
                            <span class="cardBoxGraph"><i class="bi bi-graph-up-arrow"></i></span>
                        </div>
                        <h2 class="fw-bold m-0 ms-3 mt-4 text-white">
                            <?php echo $totalNotes; ?>
                        </h2>
                    </div>
                </div>

                <div class=" col-lg-4 p-3">
                    <div class="card">
                        <div class="d-flex justify-content-between">
                            <h3 class=" m-0 ">
                                <span><i class="fa-solid fa-layer-group"></i></span>
                                <p class="m-0">Categories</p>
                            </h3>
                            <span class="cardBoxGraph"><i class="bi bi-graph-up-arrow"></i></span>
                        </div>
                        <h2 class="fw-bold m-0 ms-3 mt-4 text-white">
                            <?php echo $totalCategories; ?>
                        </h2>
                    </div>
                </div>
            </div>
        </section>


        <section class="row mt-5">

            <h3>Most Downloaded Notes</h3>
            <div class="col-lg-12">
                <table>

                    <tr>
                        <th>Rank</th>
                        <th>Title</th>
                        <th>Uploader</th>
                        <th>Downloads</th>
                        <th>File</th>
                    </tr>

                    <?php
                        $rank = 1;
                        while($row = $topNotes->fetch_assoc()) {
                    ?>

                    <tr>
                        <td>
                            <span><i class="fa-solid fa-trophy"></i></span> &ensp;
                            <?php echo $rank++; ?>
                        </td>

                        <td>
                            <?php echo $row['title']; ?>
                        </td>

                        <td>
                            <?php echo $row['uploader_name']; ?>
                        </td>

                        <td>
                            <?php echo $row['total_downloads']; ?>
                        </td>

                        <td>
                            <a class="primaryBtn" href="../uploads/<?php echo $row['file']; ?>" target="_blank">
                                <i class="fa-regular fa-eye"></i> View
                            </a>
                        </td>

                    </tr>

                    <?php } ?>
                </table>
            </div>
        </section>















        <section class="row mt-5">
            <div class="col-lg-12">
                <h3>Recent Users</h3>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>

                    <?php $key=1; while($u = $recentUsers->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <?php echo $key++; ?>
                        </td>
                        <td>
                            <?php echo $u['name']; ?>
                        </td>
                        <td>
                            <?php echo $u['email']; ?>
                        </td>
                        <td>
                            <?php echo $u['role']; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>

        </section>
        <!-- RECENT USERS -->


        <section class="row mt-5">
            <div class="col-lg-12">
                <h3>Recent Notes</h3>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>File</th>
                        <th>Time</th>
                        <th>Uploader</th>
                    </tr>

                    <?php $key=1; while($n = $recentNotes->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <?php echo $key++; ?>
                        </td>
                        <td>
                            <?php echo $n['title']; ?>
                        </td>
                        <td>
                            <?php echo $n['file']; ?>
                        </td>
                        <td>
                            <?= date("d M Y", strtotime($row['created_at'] ?? 'now')); ?>
                        </td>
                        <td>
                            <?php echo $n['uploader_name']; ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </section>

        <!-- RECENT NOTES -->


    </main>

</div>
<script src="../assets/js/custom.js"></script>

<?php include "../includes/footer.php"; ?>