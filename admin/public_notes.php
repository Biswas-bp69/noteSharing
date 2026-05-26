<?php
include "../config/auth.php";
include "../config/db.php";
include "../includes/header.php";

/* =========================
   SEARCH + CATEGORY
========================= */

$search = "";
$category = "";
$uploader = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if(isset($_GET['category'])){
    $category = mysqli_real_escape_string($conn, $_GET['category']);
}

if(isset($_GET['uploader'])){
    $uploader = mysqli_real_escape_string($conn, $_GET['uploader']);
}

/* =========================
   PAGINATION
========================= */

$limit = 9;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$offset = ($page - 1) * $limit;

/* =========================
   CATEGORY LIST (DROPDOWN)
========================= */

$cat_sql = "SELECT * FROM categories ORDER BY name ASC";
$cat_result = $conn->query($cat_sql);

/* =========================
   WHERE CONDITION
========================= */

$where = "WHERE notes.visibility='public'";

if($search != ""){
    $where .= " AND (notes.title LIKE '%$search%' OR notes.subject LIKE '%$search%')";
}

if($category != ""){
    $where .= " AND categories.id = '$category'";
}

if($uploader != ""){
    $where .= " AND users.id = '$uploader'";
}

/* =========================
   COUNT QUERY
========================= */

$count_sql = "
SELECT COUNT(*) AS total
FROM notes
LEFT JOIN users ON notes.uploaded_by = users.id
LEFT JOIN categories ON notes.category_id = categories.id
$where
";

$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();

$total_notes = $count_row['total'];
$total_pages = ceil($total_notes / $limit);

/* =========================
   MAIN QUERY
========================= */

$sql = "
SELECT notes.*, users.name AS uploader_name, categories.name AS category_name
FROM notes
LEFT JOIN users ON notes.uploaded_by = users.id
LEFT JOIN categories ON notes.category_id = categories.id
$where
ORDER BY notes.id DESC
LIMIT $limit OFFSET $offset
";

$result = $conn->query($sql);
?>

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
            <div class="text-center">
                <h1 class="m-0">Public Notes</h1>
            </div>
        </section>

        <!-- =========================
             SEARCH + CATEGORY FORM
        ========================== -->

        <div class="container mt-4">

            <form method="GET" class="d-flex mb-4">

                <input type="text" name="search" class="form-control" placeholder="Search notes..."
                    value="<?php echo $search; ?>">

                <!-- CATEGORY -->
                <select name="category" class="form-control ms-2">

                    <option value="">All Categories</option>

                    <?php while($cat = $cat_result->fetch_assoc()) { ?>

                    <option value="<?php echo $cat['id']; ?>" <?php if($category==$cat['id']) echo "selected" ; ?>>

                        <?php echo $cat['name']; ?>

                    </option>

                    <?php } ?>

                </select>

                <!-- UPLOADER -->
                <select name="uploader" class="form-control ms-2">

                    <option value="">All Users</option>

                    <?php
                        $user_sql = "SELECT id, name FROM users ORDER BY name ASC";
                        $user_result = $conn->query($user_sql);

                        while($user = $user_result->fetch_assoc()) {
                    ?>

                    <option value="<?php echo $user['id']; ?>" <?php if($uploader==$user['id']) echo "selected" ; ?>>

                        <?php echo $user['name']; ?>

                    </option>

                    <?php } ?>

                </select>

                <button class="btn btn-primary ms-2">
                    Search
                </button>

            </form>

        </div>

        <!-- =========================
             NOTES
        ========================== -->

        <div class="row">

            <?php if($result->num_rows > 0) { ?>

            <?php while($row = $result->fetch_assoc()) { ?>

            <?php
                $description = $row['description'];

                if(strlen($description) > 100){
                    $description = substr($description, 0, 100) . "...";
                }
            ?>

            <div class="col-lg-4 p-3">

                <div class="noteCard">

                    <!-- SUBJECT -->
                    <div class="subjectBadge">
                        Subject:
                        <?php echo $row['subject']; ?>
                    </div>

                    <div class="usersEmail text-end">
                        <?php echo date("d M Y", strtotime($row['created_at'])); ?>
                    </div>

                    <!-- THUMBNAIL -->
                    <div class="noteThumbnail">

                        <?php if($row['thumbnail']) { ?>

                        <img src="../uploads/<?php echo $row['thumbnail']; ?>">

                        <?php } else { ?>

                        <img src="../assets/default.png">

                        <?php } ?>

                    </div>

                    <!-- TOP INFO -->
                    <div class="noteTopInfo">

                        <div class="d-flex align-items-center gap-2 categoryBadge">

                            <i class="fa-regular fa-user"></i>

                            <div>
                                <div class="usersName">
                                    <?php echo $row['uploader_name']; ?>
                                </div>
                            </div>

                        </div>

                        <div class="categoryBadge">
                            <?php echo $row['category_name']; ?>
                        </div>

                    </div>

                    <!-- CONTENT -->
                    <div class="noteContent">

                        <!-- TITLE -->
                        <h3>
                            Title:
                            <?php echo $row['title']; ?>
                        </h3>

                        <!-- DESCRIPTION -->
                        <p class="noteDescription">
                            <?php echo $description; ?>
                        </p>

                    </div>

                    <!-- BUTTONS -->
                    <div class="d-flex justify-content-between">

                        <a class="pageBtn" href="../uploads/<?php echo $row['file']; ?>" target="_blank">

                            <i class="fa-solid fa-eye"></i>
                            View

                        </a>

                        <a class="pageBtn"
                            href="../download.php?id=<?php echo $row['id']; ?>&file=<?php echo $row['file']; ?>">

                            <i class="fa-solid fa-download"></i>
                            Download

                        </a>

                        <a class="loveBtn" href="toggle_favorite.php?id=<?php echo $row['id']; ?>">

                            <i class="fa-regular fa-heart"></i>

                        </a>

                    </div>

                </div>

            </div>

            <?php } ?>

            <?php } else { ?>

            <p class="text-center">No notes found.</p>

            <?php } ?>

        </div>

        <!-- =========================
             PAGINATION
        ========================== -->

        <div class="pagination mt-4 d-flex justify-content-center gap-2">

            <?php if($page > 1) { ?>

            <a class="pageBtn"
                href="?search=<?php echo $search; ?>&category=<?php echo $category; ?>&uploader=<?php echo $uploader; ?>&page=<?php echo $page-1; ?>">

                <i class="fa-solid fa-angle-left"></i>

            </a>

            <?php } ?>

            <?php for($i = 1; $i <= $total_pages; $i++) { ?>

            <a class="pageBtn <?php echo ($i == $page) ? 'active' : ''; ?>"
                href="?search=<?php echo $search; ?>&category=<?php echo $category; ?>&uploader=<?php echo $uploader; ?>&page=<?php echo $i; ?>">

                <?php echo $i; ?>

            </a>

            <?php } ?>

            <?php if($page < $total_pages) { ?>

            <a class="pageBtn"
                href="?search=<?php echo $search; ?>&category=<?php echo $category; ?>&uploader=<?php echo $uploader; ?>&page=<?php echo $page+1; ?>">

                <i class="fa-solid fa-angle-right"></i>

            </a>

            <?php } ?>

        </div>

    </main>

</div>

<script src="../assets/js/custom.js"></script>

<?php include "../includes/footer.php"; ?>