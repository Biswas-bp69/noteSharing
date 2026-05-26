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

    $sql = "
    SELECT notes.*, users.name AS uploader_name, users.role AS uploader_role
    FROM notes
    LEFT JOIN users ON notes.uploaded_by = users.id
    WHERE notes.title LIKE '%$search%'
       OR users.name LIKE '%$search%'
    ORDER BY notes.id DESC
    LIMIT $limit OFFSET $offset
    ";

    $countSql = "
    SELECT COUNT(*) as total
    FROM notes
    LEFT JOIN users ON notes.uploaded_by = users.id
    WHERE notes.title LIKE '%$search%'
       OR users.name LIKE '%$search%'
    ";

} else {

    $sql = "
    SELECT notes.*, users.name AS uploader_name, users.role AS uploader_role
    FROM notes
    LEFT JOIN users ON notes.uploaded_by = users.id
    ORDER BY notes.id DESC
    LIMIT $limit OFFSET $offset
    ";

    $countSql = "SELECT COUNT(*) as total FROM notes";
}


// ================= EXECUTE =================
$result = $conn->query($sql);


// ================= TOTAL PAGES =================
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

            <h1 class="mt-1">Manage Notes (Admin)</h1>

            <!-- SEARCH FORM -->
            <form method="GET" class="mb-3 d-flex gap-2">

                <input type="text" name="search" class="form-control" placeholder="Search by title or uploader"
                    value="<?= $search ?>">

                <input type="hidden" name="page" value="1">

                <button class="pageBtn">
                    Search
                </button>

            </form>

        </section>


        <table border="1" cellpadding="10" class="mt-3">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>File</th>
                <th>Uploaded By</th>
                <th>Role</th>
                <th>Time</th>
                <th>Action</th>
            </tr>

            <?php $key=1; while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td>
                    <?= $key++; ?>
                </td>

                <td>
                    <?= $row['title']; ?>
                </td>

                <td>
                    <a href="../uploads/<?= $row['file']; ?>" target="_blank">
                        View
                    </a>
                </td>

                <td>
                    <?= $row['uploader_name']; ?>
                </td>


                <td>
                    <?= ucfirst($row['uploader_role']); ?>
                </td>

                <td>
                    <?= date("d M Y", strtotime($row['created_at'] ?? 'now')); ?>
                </td>

                <td>
                    <!-- <a class="pageBtn" href="edit_note.php?id=<?= $row['id']; ?>">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a> &ensp; -->

                    <a class="redBtn" href="delete_note.php?id=<?= $row['id']; ?>"
                        onclick="return confirm('Delete this note?')">
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