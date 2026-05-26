<?php
session_start();
include "config/db.php";
include "includes/header.php";

// =====================
// SEARCH + FILTER
// =====================
$search = $_GET['search'] ?? "";
$cat = $_GET['category'] ?? "";
$uploader = $_GET['uploader'] ?? "";

// =====================
// PAGINATION
// =====================
$limit = 6;
$page = $_GET['page'] ?? 1;
$start = ($page - 1) * $limit;

// =====================
// CATEGORY LIST
// =====================
$cat_result = $conn->query("SELECT * FROM categories ORDER BY name ASC");

// =====================
// MAIN QUERY
// =====================
$sql = "
SELECT notes.*, users.name AS uploader_name, categories.name AS category_name
FROM notes
LEFT JOIN users ON notes.uploaded_by = users.id
LEFT JOIN categories ON notes.category_id = categories.id
WHERE notes.visibility='public'
";

// SEARCH (title + subject)
if ($search != "") {
    $sql .= " AND (notes.title LIKE '%$search%' OR notes.subject LIKE '%$search%')";
}

// CATEGORY FILTER
if ($cat != "") {
    $sql .= " AND notes.category_id='$cat'";
}

// UPLOADER FILTER
if ($uploader != "") {
    $sql .= " AND notes.uploaded_by='$uploader'";
}

$sql .= " ORDER BY notes.id DESC LIMIT $start, $limit";

$result = $conn->query($sql);

// =====================
// COUNT QUERY
// =====================
$countSql = "
SELECT COUNT(*) as total
FROM notes
LEFT JOIN users ON notes.uploaded_by = users.id
LEFT JOIN categories ON notes.category_id = categories.id
WHERE notes.visibility='public'
";

if ($search != "") {
    $countSql .= " AND (title LIKE '%$search%' OR subject LIKE '%$search%')";
}

if ($cat != "") {
    $countSql .= " AND category_id='$cat'";
}

if ($uploader != "") {
    $countSql .= " AND uploaded_by='$uploader'";
}

$total = $conn->query($countSql)->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);
?>

<link rel="stylesheet" href="./assets/css/style.css">

<main class="main container">


    <section class="mt-5 pt-3">
        <div class="text-center">
            <h1 class="m-0">Public Notes</h1>
        </div>
    </section>

    <!-- SEARCH + FILTER -->
    <div class="container mt-4">

        <form method="GET" class="d-flex mb-4">

            <!-- SEARCH -->
            <input type="text" name="search" class="form-control" placeholder="Search notes..."
                value="<?php echo htmlspecialchars($search); ?>">

            <!-- CATEGORY -->
            <select name="category" class="form-control ms-2">
                <option value="">All Categories</option>

                <?php while($catRow = $cat_result->fetch_assoc()) { ?>
                <option value="<?php echo $catRow['id']; ?>" <?php if($cat==$catRow['id']) echo "selected" ; ?>>
                    <?php echo $catRow['name']; ?>
                </option>
                <?php } ?>
            </select>

            <!-- UPLOADER -->
            <select name="uploader" class="form-control ms-2">
                <option value="">All Users</option>

                <?php
                $user_result = $conn->query("SELECT id, name FROM users ORDER BY name ASC");
                while($user = $user_result->fetch_assoc()) {
                ?>
                <option value="<?php echo $user['id']; ?>" <?php if($uploader==$user['id']) echo "selected" ; ?>>
                    <?php echo $user['name']; ?>
                </option>
                <?php } ?>
            </select>

            <button class="btn btn-primary ms-2">Search</button>

        </form>

    </div>

    <!-- NOTES -->
    <div class="row">

        <?php if($result && $result->num_rows > 0) { ?>

        <?php while($row = $result->fetch_assoc()) { ?>

        <?php
                $description = $row['description'];
                if(strlen($description) > 100){
                    $description = substr($description, 0, 100) . "...";
                }
                ?>

        <div class="col-lg-4 p-3">

            <div class="noteCard">

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
                    <img src="uploads/<?php echo $row['thumbnail']; ?>">
                    <?php } else { ?>
                    <img src="assets/default.png">
                    <?php } ?>
                </div>

                <!-- TOP INFO -->
                <div class="noteTopInfo">

                    <div class="d-flex align-items-center gap-2 categoryBadge">
                        <div class="categoryBadge">
                            <?php echo strtoupper(substr($row['uploader_name'],0,1)); ?>
                        </div>

                        <div class="usersName">
                            <?php echo $row['uploader_name']; ?>
                        </div>
                    </div>

                    <div class="categoryBadge">
                        <?php echo $row['category_name']; ?>
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

                    <a class="pageBtn" href="uploads/<?php echo $row['file']; ?>" target="_blank">
                        <i class="fa-solid fa-eye"></i> View
                    </a>

                    <a class="pageBtn"
                        href="download.php?id=<?php echo $row['id']; ?>&file=<?php echo $row['file']; ?>">
                        <i class="fa-solid fa-download"></i> Download
                    </a>

                    <a class="loveBtn" href="toggle_favorite.php?id=<?php echo $row['id']; ?>">
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

    <!-- PAGINATION -->
    <div class="pagination mt-4 d-flex justify-content-center gap-2">

        <?php if($page > 1) { ?>
        <a class="primaryBtn"
            href="?search=<?php echo $search; ?>&category=<?php echo $cat; ?>&uploader=<?php echo $uploader; ?>&page=<?php echo $page-1; ?>">
            <i class="fa-solid fa-angle-left"></i> Prev
        </a>
        <?php } ?>

        <?php for($i = 1; $i <= $total_pages; $i++) { ?>
        <a class="primaryBtn <?php if($i == $page) echo 'btn-primary'; ?>"
            href="?search=<?php echo $search; ?>&category=<?php echo $cat; ?>&uploader=<?php echo $uploader; ?>&page=<?php echo $i; ?>">
            <?php echo $i; ?>
        </a>
        <?php } ?>

        <?php if($page < $total_pages) { ?>
        <a class="primaryBtn"
            href="?search=<?php echo $search; ?>&category=<?php echo $cat; ?>&uploader=<?php echo $uploader; ?>&page=<?php echo $page+1; ?>">
            Next <i class="fa-solid fa-angle-right"></i>
        </a>
        <?php } ?>

    </div>

</main><br>

<?php include "includes/footer.php"; ?>