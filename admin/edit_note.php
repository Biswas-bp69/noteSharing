<?php
include "../config/auth.php";
include "../config/db.php";
include "../includes/header.php";

checkLogin();
checkRole('admin');

$id = $_GET['id'];

// note fetch
$sql = "SELECT * FROM notes WHERE id='$id'";
$result = $conn->query($sql);
$note = $result->fetch_assoc();

// categories
$cats = $conn->query("SELECT * FROM categories");
?>

<div class="container">

<h1>✏️ Edit Note</h1>

<form action="update_note.php" method="POST">

    <input type="hidden" name="id" value="<?php echo $note['id']; ?>">

    <label>Title</label><br>
    <input type="text" name="title" value="<?php echo $note['title']; ?>" required><br><br>

    <label>Category</label><br>
    <select name="category_id">
        <?php while($c = $cats->fetch_assoc()) { ?>
            <option value="<?php echo $c['id']; ?>"
                <?php if($c['id'] == $note['category_id']) echo "selected"; ?>>
                <?php echo $c['name']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <button class="btn" type="submit">Update</button>

</form>

</div>

<?php include "../includes/footer.php"; ?>