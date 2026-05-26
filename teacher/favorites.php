<?php

include "../config/auth.php";
include "../config/db.php";
include "../includes/header.php";

checkLogin();
checkRole('teacher');

$user_id = $_SESSION['user_id'];

$note_id = $_GET['id'] ?? 0;

/* ---------------------------
   TOGGLE FAVORITE (FIXED)
----------------------------*/
if($note_id){

    $check = $conn->query("
        SELECT * FROM favorites
        WHERE user_id='$user_id'
        AND note_id='$note_id'
    ");

    if ($check->num_rows == 0) {

        $conn->query("
            INSERT INTO favorites (user_id, note_id)
            VALUES ('$user_id', '$note_id')
        ");

    } else {

        $conn->query("
            DELETE FROM favorites
            WHERE user_id='$user_id'
            AND note_id='$note_id'
        ");
    }

    /* ✅ IMPORTANT FIX: prevent refresh re-toggle */
    $cleanUrl = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: $cleanUrl");
    exit;
}

/* ---------------------------
   FILTERS
----------------------------*/

$search = $_GET['search'] ?? "";
$category = $_GET['category'] ?? "";

/* pagination */
$limit = 8;
$page = $_GET['page'] ?? 1;
$start = ($page - 1) * $limit;

/* ---------------------------
   CATEGORY LIST
----------------------------*/
$catResult = $conn->query("SELECT * FROM categories");

/* ---------------------------
   MAIN QUERY
----------------------------*/

$favSql = "
SELECT notes.*, 
       categories.name AS category_name,
       users.name AS uploader_name
FROM favorites
JOIN notes ON favorites.note_id = notes.id
LEFT JOIN categories ON notes.category_id = categories.id
LEFT JOIN users ON notes.uploaded_by = users.id
WHERE favorites.user_id='$user_id'
";

if($search != ""){
    $favSql .= " AND notes.title LIKE '%$search%'";
}

if($category != ""){
    $favSql .= " AND notes.category_id='$category'";
}

$favSql .= " ORDER BY favorites.id DESC LIMIT $start, $limit";

$favResult = $conn->query($favSql);

/* ---------------------------
   COUNT
----------------------------*/

$countSql = "
SELECT COUNT(*) as total
FROM favorites
JOIN notes ON favorites.note_id = notes.id
WHERE favorites.user_id='$user_id'
";

if($search != ""){
    $countSql .= " AND notes.title LIKE '%$search%'";
}

if($category != ""){
    $countSql .= " AND notes.category_id='$category'";
}

$total = $conn->query($countSql)->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

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
            <h2>❤️ My Favorites</h2>

            <form method="GET" class="mb-3 d-flex gap-2">

                <input type="text" name="search" class="form-control" placeholder="Search favorites..."
                    value="<?= $search ?>">

                <select name="category" class="form-control" onchange="this.form.submit()">

                    <option value="">All Categories</option>

                    <?php while($c = $catResult->fetch_assoc()) { ?>
                    <option value="<?= $c['id']; ?>" <?php if($category==$c['id']) echo "selected" ; ?>>
                        <?= $c['name']; ?>
                    </option>
                    <?php } ?>

                </select>

                <button class="pageBtn">Search</button>

            </form>

            <div class="row">

                <?php if($favResult->num_rows > 0) { ?>

                <?php while($f = $favResult->fetch_assoc()) { ?>

                <div class="col-lg-4 mb-4">
                    <div class="noteCard">

                        <div class="subjectBadge">
                            Subject:
                            <?= $f['subject'] ?>
                        </div>

                        <div class="usersEmail text-end">
                            <?= date("d M Y", strtotime($f['created_at'] ?? 'now')); ?>
                        </div>

                        <div class="noteThumbnail">
                            <?php if($f['thumbnail']) { ?>
                            <img src="../uploads/<?= $f['thumbnail']; ?>">
                            <?php } else { ?>
                            <img src="../assets/default.png">
                            <?php } ?>
                        </div>

                        <div class="noteTopInfo">

                            <div class="d-flex align-items-center gap-2 categoryBadge">

                                <div class="categoryBadge">
                                    <?= strtoupper(substr($f['uploader_name'],0,1)); ?>
                                </div>

                                <div class="usersName">
                                    <?= $f['uploader_name']; ?>
                                </div>

                            </div>

                            <div class="categoryBadge">
                                <?= $f['category_name']; ?>
                            </div>

                        </div>

                        <div class="noteContent">

                            <h3>
                                Title:
                                <?= $f['title']; ?>
                            </h3>

                            <p class="noteDescription">
                                <?= substr($f['description'],0,100) ?>...
                            </p>

                        </div>

                        <!-- BUTTONS (UPDATED) -->
                        <div class="d-flex justify-content-between">

                            <!-- VIEW -->
                            <a class="pageBtn" href="../uploads/<?= $f['file']; ?>" target="_blank">
                                <i class="fa-solid fa-eye"></i> View
                            </a>

                            <!-- DOWNLOAD -->
                            <a class="pageBtn" href="../download.php?id=<?= $f['id']; ?>&file=<?= $f['file']; ?>">
                                <i class="fa-solid fa-download"></i> Download
                            </a>

                            <!-- UNFAVORITE -->
                            <a class="loveBtn" href="?id=<?= $f['id']; ?>">
                                <i class="fa-solid fa-heart-crack"></i>
                            </a>

                        </div>

                    </div>
                </div>

                <?php } ?>

                <?php } else { ?>

                <div class="col-12">
                    <div class="alert alert-warning">
                        No favorites found.
                    </div>
                </div>

                <?php } ?>

            </div>

            <!-- PAGINATION -->
            <div class="pagination mt-4 d-flex justify-content-center gap-2 flex-wrap">

                <!-- PREVIOUS -->
                <?php if($page > 1) { ?>

                <a class="pageBtn" href="?page=<?= $page-1; ?>&search=<?= $search; ?>&category=<?= $category; ?>">

                    <i class="fa-solid fa-angle-left"></i>
                </a>

                <?php } ?>

                <!-- PAGE NUMBERS -->
                <?php for($i = 1; $i <= $totalPages; $i++) { ?>

                <a class="pageBtn <?php echo ($i == $page) ? 'active' : ''; ?>"
                    href="?page=<?= $i; ?>&search=<?= $search; ?>&category=<?= $category; ?>">

                    <?= $i; ?>

                </a>

                <?php } ?>

                <!-- NEXT -->
                <?php if($page < $totalPages) { ?>

                <a class="pageBtn" href="?page=<?= $page+1; ?>&search=<?= $search; ?>&category=<?= $category; ?>">

                    <i class="fa-solid fa-angle-right"></i>

                </a>

                <?php } ?>

            </div>

        </section>

    </main>

</div>

<?php include "../includes/footer.php"; ?>