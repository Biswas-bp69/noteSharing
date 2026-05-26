<?php
include "../config/auth.php";
include "../config/db.php";
include "../includes/header.php";

checkLogin();
checkRole('admin');


// ================= LIMIT + PAGINATION =================
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;


// ================= SEARCH =================
$search = isset($_GET['search']) ? $_GET['search'] : "";


// ================= MAIN QUERY =================
if ($search != "") {

    $sql = "SELECT * FROM users 
            WHERE name LIKE '%$search%' 
            OR email LIKE '%$search%' 
            ORDER BY id DESC
            LIMIT $limit OFFSET $offset";

    $countSql = "SELECT COUNT(*) as total FROM users 
                 WHERE name LIKE '%$search%' 
                 OR email LIKE '%$search%'";

} else {

    $sql = "SELECT * FROM users 
            ORDER BY id DESC 
            LIMIT $limit OFFSET $offset";

    $countSql = "SELECT COUNT(*) as total FROM users";
}


// ================= EXECUTE USERS =================
$result = $conn->query($sql);


// ================= TOTAL COUNT =================
$totalResult = $conn->query($countSql);
$totalRow = $totalResult->fetch_assoc();
$totalPages = ceil($totalRow['total'] / $limit);

?>
<div class="dashboard-layout" id="dashboardLayout">

    <?php include "sidebar.php"; ?>

    <main class="main">
        <div class="topbar">
            <button id="toggleBtn">
                <i class="fa-solid fa-angle-left"></i>
            </button>
        </div>

        <section class="mt-5 pt-3">

            <h1>All Users</h1>
            <form method="GET" class="mb-3 d-flex gap-2">

                <input type="text" name="search" class="form-control" placeholder="Search by name or email"
                    value="<?= $search ?>">

                <button class="pageBtn">
                    Search
                </button>

            </form>

        </section>

        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Time</th>
                <th>Role</th>
                <th>Action</th>
            </tr>

            <?php $key=1; while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td>
                    <?php echo $key++; ?>
                </td>
                <td>
                    <?php echo $row['name']; ?>
                </td>
                <td>
                    <?php echo $row['email']; ?>
                </td>
                <td>
                    <?= date("d M Y", strtotime($row['created_at'] ?? 'now')); ?>
                </td>
                <td>
                    <?php echo $row['role']; ?>
                </td>
                <td>
                    <a class="redBtn" href="delete_user.php?id=<?php echo $row['id']; ?>"
                        onclick="return confirm('Delete this user?')">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>


        <div class="text-center mt-5">

            <!-- PREVIOUS -->
            <?php if($page > 1) { ?>
            <a class="pageBtn" href="?page=<?= $page - 1 ?>&search=<?= $search ?>">
                <i class="fa-solid fa-angle-left"></i>
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
                <i class="fa-solid fa-angle-right"></i>
            </a>
            <?php } ?>

        </div>
    </main>

</div>



<script src="../assets/js/custom.js"></script>

<?php include "../includes/footer.php"; ?>