<?php  
include 'includes/header.php'; 

require_once '../config/database.php'; 


$message = ''; 
$message_type = ''; 


if ($_SERVER['REQUEST_METHOD'] === 'POST') { 

    $title       = $_POST['title']; 
    $content     = $_POST['content']; 
    $category_id = $_POST['category_id']; 
    $status      = $_POST['status']; 


    $image = ''; 
    if (!empty($_FILES['image']['name'])) { 
        $fileName = time() . '_' . $_FILES['image']['name']; 
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $fileName); 
        $image = $fileName; 
    }     
    

    $stmt = $pdo->prepare("
        INSERT INTO posts (title, content, image, category_id, status, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    "); 
    
    if ($stmt->execute([$title, $content, $image, $category_id, $status])) {
        echo "<script>window.location.href='posts.php?success=1';</script>";
        exit;
    } else {
        $message = "❌ Error adding post!";
        $message_type = "danger";
    }
}
?> 
<div class="row">

        <?php include 'includes/sidebar.php'; ?>


<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

<div class="py-3">

<h2><i class="bi bi-plus-circle"></i> Add New Post</h2>
<hr>

<form method="POST" enctype="multipart/form-data" class="card p-3">

    <div class="mb-3">
        <label class="form-label">Post Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea name="content" class="form-control" rows="5" required></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select class="form-select" name="category_id" required>
            <option style='background: black ;'  disabled selected>Select Category</option>
            <?php 
            $cats = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
            while ($cat = $cats->fetch(PDO::FETCH_ASSOC)) {
                echo "<option style='background: black ;' value='{$cat['id']}'>{$cat['name']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select class="form-select" name="status">
            <option style='background: black ;' value="draft">Draft</option>
            <option style='background: black ;' value="published">Published</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Featured Image</label>
        <input type="file" name="image" class="form-control">
    </div>

    <button class="btn btn-primary w-100">Create Post</button>
</form>

</div>
</main>
</div>
</div>

<?php include 'includes/footer.php'; ?>
