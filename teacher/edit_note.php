<?php
include "../config/auth.php";
include "../config/db.php";
include "../config/security.php";
include "../includes/header.php";

checkLogin();
checkRole('teacher');

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

/* GET NOTE */
$sql = "SELECT * FROM notes WHERE id='$id' AND uploaded_by='$user_id'";
$result = $conn->query($sql);

if($result->num_rows == 0){
    echo "Note not found!";
    exit;
}

$note = $result->fetch_assoc();

/* =========================
   UPDATE LOGIC
========================= */

$message = "";

if(isset($_POST['update'])){

    $title = clean($_POST['title']);
    $category_id = $_POST['category_id'];
    $visibility = $_POST['visibility'];

    $fileName = $note['file'];
    $thumbName = $note['thumbnail'];

    /* NEW FILE */
    if(!empty($_FILES['file']['name'])){
        $file = $_FILES['file']['name'];
        $tmp = $_FILES['file']['tmp_name'];

        $fileName = time() . "_" . $file;
        move_uploaded_file($tmp, "../uploads/".$fileName);
    }

    /* NEW THUMBNAIL */
    if(!empty($_FILES['thumbnail']['name'])){
        $thumb = $_FILES['thumbnail']['name'];
        $tmp2 = $_FILES['thumbnail']['tmp_name'];

        $thumbName = time() . "_" . $thumb;
        move_uploaded_file($tmp2, "../uploads/".$thumbName);
    }

    /* UPDATE QUERY */
    $sql = "
    UPDATE notes SET
        title='$title',
        category_id='$category_id',
        visibility='$visibility',
        file='$fileName',
        thumbnail='$thumbName'
    WHERE id='$id' AND uploaded_by='$user_id'
    ";

    if($conn->query($sql)){
       echo "<script>
        alert('Update success');
        window.location.href = 'my_notes.php';
      </script>";
      exit;
    } else {
        $message = "❌ Update failed!";
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


    <div class="mt-3">



      <div class="row">

        <!-- ================= LEFT FORM ================= -->
        <div class="col-lg-6 p-3">
          <h3 class="text-center">Edit Note</h3>
          <form method="POST" enctype="multipart/form-data" class="border uploadNote">

            <!-- TITLE -->
            <label>Title</label>
            <input type="text" name="title" value="<?php echo $note['title']; ?>" class="form-control" required><br>

            <!-- CATEGORY -->
            <label>Category</label>
            <select name="category_id" class="form-control">

              <?php
        $cat = $conn->query("SELECT * FROM categories");
        while($c = $cat->fetch_assoc()) {
        ?>
              <option value="<?php echo $c['id']; ?>" <?php if($note['category_id']==$c['id']) echo "selected" ; ?>>
                <?php echo $c['name']; ?>
              </option>
              <?php } ?>

            </select><br>

            <!-- VISIBILITY -->
            <label>Visibility</label>
            <select name="visibility" class="form-control">

              <option value="public" <?php if($note['visibility']=='public' ) echo "selected" ; ?>>
                Public
              </option>

              <option value="private" <?php if($note['visibility']=='private' ) echo "selected" ; ?>>
                Private
              </option>

            </select><br>

            <!-- FILE -->
            <label>Replace File (optional)</label>
            <input type="file" name="file" class="form-control" onchange="previewFile(event)"><br>

            <!-- THUMBNAIL -->
            <label>Replace Thumbnail (optional)</label>
            <input type="file" name="thumbnail" class="form-control" onchange="previewThumb(event)"><br>

            <button name="update" class="btn btn-primary">
              Update Note
            </button>

          </form>

        </div>

        <!-- ================= RIGHT PREVIEW ================= -->
        <div class="col-lg-6 p-3">
          <h3 class="text-center">📌 Current File & Thumbnail</h3>
          <div class="border uploadNote">





            <!-- OLD THUMB -->
            <p><b>Old Thumbnail</b></p>
            <img src="../uploads/<?php echo $note['thumbnail']; ?>"
              style="width:100%; height:200px; object-fit:cover; border:1px solid #ccc;">

            <br><br>

            <!-- OLD FILE -->
            <p><b>Old File</b></p>
            <iframe src="../uploads/<?php echo $note['file']; ?>" style="width:100%; height:300px;"></iframe>

            <hr>

            <h4>🆕 New Preview</h4>

            <!-- NEW THUMB -->
            <img id="thumbPreview"
              style="width:100%; max-height:250px; object-fit:cover; display:none; border:2px solid green;">

            <!-- NEW FILE -->
            <iframe id="filePreview" style="width:100%; height:300px; display:none;"></iframe>

          </div>
        </div>
      </div>

    </div>

  </main>
</div>





















<?php include "../includes/footer.php"; ?>