<?php
include "../config/auth.php";
include "../config/db.php";
include "../includes/header.php";



/* =========================
   SEARCH + CATEGORY
========================= */

$search = "";
$category = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if(isset($_GET['category'])){
    $category = mysqli_real_escape_string($conn, $_GET['category']);
}

/* =========================
   PAGINATION
========================= */

$limit = 8;

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
    $where .= " AND notes.title LIKE '%$search%'";
}

if($category != ""){
    $where .= " AND categories.id = '$category'";
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

<div class="dashboard-layout" id="dashboardLayout">

    <?php include "sidebar.php"; ?>

    <main class="main">

        <div class="topbar">
            <button id="toggleBtn">
                <i class="fa-solid fa-angle-left"></i>
            </button>
        </div>

        <div class="text-center">
            <h1 class="m-0">Public Notes</h1>
        </div>

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

            <div class="col-lg-3 p-3">

                <div class="noteCard">

                    <h3>
                        <?php echo $row['title']; ?>
                    </h3>

                    <?php if($row['thumbnail']) { ?>
                    <img src="../uploads/<?php echo $row['thumbnail']; ?>"
                        style="width:100%; height:150px; object-fit:cover; border-radius:8px;">
                    <?php } else { ?>
                    <img src="../assets/default.png"
                        style="width:100%; height:150px; object-fit:cover; border-radius:8px;">
                    <?php } ?>

                    <p>
                        <i class="fa-regular fa-user"></i>
                        <?php echo $row['uploader_name']; ?>
                    </p>

                    <p>
                        <i class="bi bi-calendar"></i>
                        <?php echo date("d M Y", strtotime($row['created_at'])); ?>
                    </p>

                    <p>
                        <b>Category:</b>
                        <?php echo $row['category_name']; ?>
                    </p>

                    <div class="d-flex justify-content-between">

                        <a class="primaryBtn w-50" href="../uploads/<?php echo $row['file']; ?>" target="_blank">

                            <i class="fa-solid fa-eye"></i> View

                        </a>

                        <a class="downloadNote"
                            href="../download.php?id=<?php echo $row['id']; ?>&file=<?php echo $row['file']; ?>">

                            <i class="fa-solid fa-download"></i>

                        </a>

                        <a class="downloadNote" href="toggle_favorite.php?id=<?php echo $row['id']; ?>">

                            <i class="fa-solid fa-heart"></i>

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
            <a class="primaryBtn"
                href="?search=<?php echo $search; ?>&category=<?php echo $category; ?>&page=<?php echo $page-1; ?>">
                Prev
            </a>
            <?php } ?>

            <?php for($i = 1; $i <= $total_pages; $i++) { ?>

            <a class="primaryBtn <?php echo ($i == $page) ? 'btn-primary' : ''; ?>"
                href="?search=<?php echo $search; ?>&category=<?php echo $category; ?>&page=<?php echo $i; ?>">
                <?php echo $i; ?>
            </a>

            <?php } ?>

            <?php if($page < $total_pages) { ?>
            <a class="primaryBtn"
                href="?search=<?php echo $search; ?>&category=<?php echo $category; ?>&page=<?php echo $page+1; ?>">
                Next
            </a>
            <?php } ?>

        </div>

    </main>

</div>

<script src="../assets/js/custom.js"></script>

<?php include "../includes/footer.php"; ?>