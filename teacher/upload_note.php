<?php
include "../config/auth.php";
include "../config/db.php";
include "../config/security.php";
include "../includes/header.php";

checkLogin();
checkRole('teacher');

// thumbnail 
$thumbName = "default.png";

if (!empty($_FILES['thumbnail']['name'])) {

    $thumbName = time() . "_" . $_FILES['thumbnail']['name'];
    $tmp2 = $_FILES['thumbnail']['tmp_name'];

    move_uploaded_file($tmp2, "../uploads/" . $thumbName);
}

$message = "";

if (isset($_POST['upload'])) {

    $title = clean($_POST['title']);
    $subject = clean($_POST['subject']);
    $description = clean($_POST['description']);

    $category_id = $_POST['category_id'];
    $visibility = $_POST['visibility'];

    $file = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];

    // 📏 file size limit (2MB)
    if ($size > 2 * 1024 * 1024) {
        $message = "❌ File too large (Max 2MB)";
    }

    // 📁 file type check
    elseif (!allowedFile($file)) {
        $message = "❌ Invalid file type!";
    }

    else {

        $newName = time() . "_" . $file;
        $path = "../uploads/" . $newName;

        if (move_uploaded_file($tmp, $path)) {

            $sql = "INSERT INTO notes 
            (title, subject, description, file, thumbnail, category_id, uploaded_by, visibility)

            VALUES 
            ('$title', '$subject', '$description', '$newName', '$thumbName', '$category_id', '{$_SESSION['user_id']}', '$visibility')";

            $conn->query($sql);

            $message = "✅ Upload successful!";
        }
    }
}
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

            <h2 class="text-center mt-3 mb-0">Upload Note</h2>

            <p class="text-center">
                <?php echo $message; ?>
            </p>

            <form class="uploadNote" method="POST" enctype="multipart/form-data">

                <label for="title">Title</label>
                <input type="text" name="title" placeholder="Title" required><br><br>

                <label for="subject">Subject</label>
                <input type="text" name="subject" placeholder="Enter Subject" required><br><br>

                <label for="description">Description</label>
                <textarea name="description" placeholder="Write description here..." rows="5"
                    required></textarea><br><br>

                <label for="category">Category</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>

                    <?php
                    $cat = $conn->query("SELECT * FROM categories");

                    while ($c = $cat->fetch_assoc()) {
                    ?>

                    <option value="<?php echo $c['id']; ?>">
                        <?php echo $c['name']; ?>
                    </option>

                    <?php } ?>

                </select><br><br>

                <label for="thumbnail">Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/*">
                <p>Only image accepted</p>

                <label for="file">File:</label>
                <input type="file" name="file" required>
                <p>Only PDF available</p>

                <label>Visibility</label>

                <select name="visibility" required>
                    <option value="private">Private</option>
                    <option value="public">Public</option>
                </select><br><br>

                <div class="w-100 text-center">
                    <button name="upload" class="pageBtn">Upload Now</button>
                </div>

            </form>
        </section>

    </main>

</div>

<?php include "../includes/footer.php"; ?>