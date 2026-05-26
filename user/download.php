<?php
include "../config/auth.php";
include "../config/db.php";

checkLogin();

include "../includes/header.php";

$user_id = $_SESSION['user_id'];

$search = $_GET['search'] ?? "";

/* =========================
   📥 FILE DOWNLOAD
========================= */

if (isset($_GET['file'])) {

    $file = $_GET['file'];
    $path = "../uploads/" . $file;

    if (file_exists($path)) {

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($path) . '"');
        header('Content-Length: ' . filesize($path));

        readfile($path);
        exit;

    } else {
        echo "❌ File not found!";
    }
}

/* =========================
   📊 PAGINATION
========================= */

$limit = 6;

$page = $_GET['page'] ?? 1;
$page = ($page < 1) ? 1 : $page;

$start = ($page - 1) * $limit;

/* =========================
   BASE QUERY WITH JOIN
========================= */

$baseQuery = "
FROM downloads
JOIN notes ON downloads.note_id = notes.id
LEFT JOIN users ON notes.uploaded_by = users.id
LEFT JOIN categories ON notes.category_id = categories.id
WHERE downloads.user_id = '$user_id'
";

/* =========================
   COUNT QUERY
========================= */

$countSql = "SELECT COUNT(*) as total " . $baseQuery;

if ($search != "") {
    $countSql .= " AND notes.title LIKE '%$search%'";
}

$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

/* =========================
   MAIN QUERY
========================= */

$sql = "
SELECT 
    notes.*,
    downloads.downloaded_at,
    users.name AS uploader_name,
    categories.name AS category_name
" . $baseQuery;

if ($search != "") {
    $sql .= " AND notes.title LIKE '%$search%'";
}

$sql .= " ORDER BY downloads.id DESC LIMIT $start, $limit";

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

            <h2>📥 My Downloads</h2>

            <!-- SEARCH -->
            <form method="GET" class="m-0 d-flex gap-2 mb-3">

                <input type="text" class="form-control" name="search" placeholder="Search notes..."
                    value="<?php echo htmlspecialchars($search); ?>">

                <button class="pageBtn">Search</button>

            </form>

            <div class="row">

                <?php if ($result->num_rows > 0) { ?>

                <?php while ($row = $result->fetch_assoc()) { ?>

                <?php
                            $description = $row['description'] ?? '';
                            if (strlen($description) > 100) {
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
                            <?php if ($row['thumbnail']) { ?>
                            <img src="../uploads/<?php echo $row['thumbnail']; ?>">
                            <?php } else { ?>
                            <img src="../assets/default.png">
                            <?php } ?>
                        </div>

                        <!-- TOP INFO -->
                        <div class="noteTopInfo">

                            <div class="d-flex align-items-center gap-2 categoryBadge">

                                <div class="categoryBadge">
                                    <?php echo strtoupper(substr($row['uploader_name'] ?? 'U', 0, 1)); ?>
                                </div>

                                <div class="usersName">
                                    <?php echo $row['uploader_name'] ?? 'Unknown'; ?>
                                </div>

                            </div>

                            <div class="categoryBadge">
                                <?php echo $row['category_name'] ?? 'N/A'; ?>
                            </div>

                        </div>

                        <!-- CONTENT -->
                        <div class="noteContent">

                            <h3>
                                Title:
                                <?php echo $row['title']; ?>
                            </h3>

                            <p class="noteDescription">
                                <?php echo $description; ?>
                            </p>

                        </div>

                        <!-- BUTTONS -->
                        <div class="d-flex justify-content-between">

                            <a class="pageBtn w-100 flex-1" href="../uploads/<?php echo $row['file']; ?>"
                                target="_blank">

                                <i class="fa-solid fa-eye "></i>
                                View
                            </a>

                            <a class="loveBtn" href="toggle_favorite.php?id=<?php echo $row['id']; ?>">

                                <i class="fa-regular fa-heart"></i>
                            </a>

                        </div>

                    </div>

                </div>

                <?php } ?>

                <?php } else { ?>
                <p class="text-center">No downloads found.</p>
                <?php } ?>

            </div>

        </section>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1) { ?>

        <div class="d-flex justify-content-center gap-2 mt-4 flex-wrap">

            <?php if ($page > 1) { ?>
            <a class="pageBtn" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">
                <i class="fa-solid fa-angle-left"></i>
            </a>
            <?php } ?>

            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>

            <a class="pageBtn <?php if ($page == $i) echo 'activePage'; ?>"
                href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                <?php echo $i; ?>
            </a>

            <?php } ?>

            <?php if ($page < $totalPages) { ?>
            <a class="pageBtn" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">
                <i class="fa-solid fa-angle-right"></i>
            </a>
            <?php } ?>

        </div>

        <?php } ?>

    </main>

</div>

<script src="../assets/js/custom.js"></script>

<?php include "../includes/footer.php"; ?>