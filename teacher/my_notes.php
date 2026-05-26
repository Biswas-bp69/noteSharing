<?php
include "../config/auth.php";
include "../config/db.php";
include "../includes/header.php";



checkLogin();
checkRole('teacher');




$user_id = $_SESSION['user_id'];

// 🔍 SEARCH + CATEGORY
$search = $_GET['search'] ?? "";
$category = $_GET['category'] ?? "";

// 📄 PAGINATION
$limit = 9;
$page = $_GET['page'] ?? 1;
$start = ($page - 1) * $limit;

/* =========================
MAIN QUERY
========================= */

$sql = "
SELECT notes.*, categories.name AS category_name
FROM notes
LEFT JOIN categories ON notes.category_id = categories.id
WHERE uploaded_by='$user_id'
";

if ($search != "") {
$sql .= " AND title LIKE '%$search%'";
}

if ($category != "") {
$sql .= " AND notes.category_id='$category'";
}

$sql .= " ORDER BY id DESC LIMIT $start, $limit";

$result = $conn->query($sql);

/* =========================
CATEGORY LIST
========================= */

$cat_sql = "SELECT * FROM categories ORDER BY name ASC";
$cat_result = $conn->query($cat_sql);

/* =========================
COUNT
========================= */

$countSql = "
SELECT COUNT(*) as total FROM notes
WHERE uploaded_by='$user_id'
";

if ($search != "") {
$countSql .= " AND title LIKE '%$search%'";
}

if ($category != "") {
$countSql .= " AND category_id='$category'";
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
            <h1>My Notes</h1>

            <!-- 🔍 SEARCH + CATEGORY -->
            <form method="GET" class="mb-3 d-flex gap-2">

                <input type="text" name="search" class="form-control" placeholder="Search notes..."
                    value="<?= $search ?>">

                <!-- CATEGORY -->
                <select name="category" class="form-control">

                    <option value="">All Categories</option>

                    <?php while($cat = $cat_result->fetch_assoc()) { ?>

                    <option value="<?= $cat['id'] ?>" <?php if($category==$cat['id']) echo "selected" ; ?>>

                        <?= $cat['name'] ?>

                    </option>

                    <?php } ?>

                </select>

                <button class="pageBtn">
                    Search
                </button>

            </form>

            <!-- 📦 CARDS -->
            <!-- NOTES -->
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
                            <?= $row['subject']; ?>
                        </div>

                        <!-- DATE -->
                        <div class="usersEmail text-end">
                            <?= date("d M Y", strtotime($row['created_at'])); ?>
                        </div>

                        <!-- THUMBNAIL -->
                        <div class="noteThumbnail">

                            <?php if($row['thumbnail']) { ?>

                            <img src="../uploads/<?= $row['thumbnail']; ?>">

                            <?php } else { ?>

                            <img src="../assets/default.png">

                            <?php } ?>

                        </div>

                        <!-- TOP INFO -->
                        <div class="noteTopInfo">

                            <div class="categoryBadge">

                                <?php if($row['visibility'] == 'public') { ?>

                                🟢 Public

                                <?php } else { ?>

                                🔴 Private

                                <?php } ?>

                            </div>

                            <div class="categoryBadge">
                                <?= $row['category_name'] ?? 'No Category'; ?>
                            </div>

                        </div>

                        <!-- CONTENT -->
                        <div class="noteContent">

                            <h3>
                                Title:
                                <?= $row['title']; ?>
                            </h3>

                            <p class="noteDescription">
                                <?= $description; ?>
                            </p>

                        </div>

                        <!-- BUTTONS -->
                        <div class="d-flex justify-content-between">

                            <!-- VIEW -->
                            <a class="pageBtn" href="../uploads/<?= $row['file']; ?>" target="_blank">

                                <i class="fa-solid fa-eye"></i> View

                            </a>

                            <!-- EDIT -->
                            <a class="pageBtn" href="edit_note.php?id=<?= $row['id']; ?>">

                                <i class="fa-solid fa-pen"></i> Edit

                            </a>

                            <!-- TOGGLE -->
                            <a class="pageBtn" href="toggle_visibility.php?id=<?= $row['id']; ?>">

                                <i class="fa-solid fa-lock"></i> Toggle

                            </a>



                        </div>
                        <!-- DELETE -->
                        <a class="redBtn w-100" href="delete_note.php?id=<?= $row['id']; ?>"
                            onclick="return confirm('Are you sure you want to delete this note?');">

                            <i class="fa-solid fa-trash"></i> Delete

                        </a>

                    </div>

                </div>

                <?php } ?>

                <?php } else { ?>

                <p class="text-center">No notes found.</p>

                <?php } ?>

            </div>

            <!-- 🔢 PAGINATION -->
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