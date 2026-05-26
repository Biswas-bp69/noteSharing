<?php
include "../config/auth.php";
include "../config/db.php";
include "../includes/header.php";

checkLogin();
checkRole('admin');


// ================= LIMIT + PAGINATION =================
$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


// ================= SEARCH =================
$search = isset($_GET['search']) ? $_GET['search'] : "";


// ================= ADD CATEGORY =================
if (isset($_POST['add'])) {

    $name = $_POST['name'];

    $sql = "INSERT INTO categories (name) VALUES ('$name')";
    $conn->query($sql);
}


// ================= MAIN QUERY =================
if ($search != "") {

    $sql = "SELECT * FROM categories 
            WHERE name LIKE '%$search%' 
            ORDER BY id DESC
            LIMIT $limit OFFSET $offset";

    $countSql = "SELECT COUNT(*) as total FROM categories 
                 WHERE name LIKE '%$search%'";

} else {

    $sql = "SELECT * FROM categories 
            ORDER BY id DESC 
            LIMIT $limit OFFSET $offset";

    $countSql = "SELECT COUNT(*) as total FROM categories";
}


// ================= EXECUTE =================
$result = $conn->query($sql);


// ================= TOTAL PAGES =================
$totalResult = $conn->query($countSql);
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);

?>

<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/sidebar.css">

<div class="dashboard-layout" id="dashboardLayout">

    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <button id="toggleBtn">
                <i class="fa-solid fa-angle-left"></i>
            </button>
        </div>
        <section class="mt-5 pt-3">


            <h1>📂 Manage Categories</h1>


            <!-- SEARCH -->
            <form method="GET" class="mb-3 d-flex gap-2">

                <input type="text" name="search" class="form-control" placeholder="Search category name"
                    value="<?= $search ?>">

                <input type="hidden" name="page" value="1">

                <button class="pageBtn">Search</button>

            </form>


            <!-- ADD FORM -->
            <form method="POST" class="mb-3 d-flex gap-2">

                <input type="text" name="name" class="ps-2" placeholder="Category name" required>

                <button type="submit" name="add" class="pageBtn">
                    <i class="fa-solid fa-plus"></i> Add
                </button>

            </form>
        </section>

        <!-- LIST -->
        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Time</th>
                <th>Action</th>
            </tr>

            <?php $key=1; while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td>
                    <?= $key++; ?>
                </td>
                <td>
                    <?= $row['name']; ?>
                </td>
                <td>
                    <?= date("d M Y", strtotime($row['created_at'] ?? 'now')); ?>
                </td>
                <td>
                    <a class="redBtn" href="delete_category.php?id=<?= $row['id']; ?>"
                        onclick="return confirm('Delete category?')">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </a>
                </td>
            </tr>
            <?php } ?>

        </table>


        <!-- PAGINATION -->

        <div class="text-center mt-5">

            <!-- PREVIOUS -->
            <?php if($page > 1) { ?>
            <a class="pageBtn" href="?page=<?= $page - 1 ?>&search=<?= $search ?>">
                ← Previous
            </a>
            <?php } ?>

            <!-- PAGE NUMBERS -->
            <?php for($i = 1; $i <= $totalPages; $i++) { ?>

            <a class="pageBtn <?= ($i == $page) ? 'active' : '' ?>" href="?page=<?= $i ?>&search=<?= $search ?>">
                <?= $i ?>
            </a>

            <?php } ?>

            <!-- NEXT -->
            <?php if($page < $totalPages) { ?>
            <a class="pageBtn" href="?page=<?= $page + 1 ?>&search=<?= $search ?>">
                Next →
            </a>
            <?php } ?>

        </div>

    </main>

</div>

<script src="../assets/js/custom.js"></script>

<?php include "../includes/footer.php"; ?>