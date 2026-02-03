<?php
include 'includes/header.php';
require_once '../config/database.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location='posts.php';</script>";
    exit;
}

$post_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "<script>alert('Post not found!'); window.location='posts.php';</script>";
    exit;
}

$cats = $pdo->prepare("SELECT * FROM categories ORDER BY name ASC");
$cats->execute();
$categories = $cats->fetchAll(PDO::FETCH_ASSOC);
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = $_POST['title'];
    $content     = $_POST['content'];
    $category_id = $_POST['category_id'];
    $status      = $_POST['status'];

    $image = $post['image'];
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $image);
        if (!empty($post['image']) && file_exists("../uploads/".$post['image'])) {
            unlink("../uploads/".$post['image']);
        }
    }

    $sql = "UPDATE posts 
            SET title=?, content=?, category_id=?, status=?, image=? 
            WHERE id=?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$title, $content, $category_id, $status, $image, $post_id])) {
        echo "<script>window.location='posts.php?success=updated';</script>";
        exit;
    } else {
        $message = "❌ Failed to update post!";
        $message_type = "danger";
    }
}
?>
<div class="row">

        <?php include 'includes/sidebar.php'; ?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

<div class="py-4 d-flex justify-content-between align-items-center border-bottom">
    <h2><i class="bi bi-pencil"></i> Edit Post</h2>
    <a href="posts.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
</div>

<?php if($message): ?>
<div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show mt-2">
    <?php echo $message; ?>
    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Update Post</h5>
    </div>

    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Post Title</label>
                <input type="text" class="form-control" name="title"
                value="<?php echo $post['title']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea class="form-control" name="content" rows="6" required><?php echo $post['content']; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select class="form-select" name="category_id" required>
                    <?php foreach ($categories as $cat): ?>
                        <option style='background: black ;' value="<?php echo $cat['id']; ?>"
                        <?php echo ($cat['id'] == $post['category_id']) ? 'selected' : ''; ?>>
                            <?php echo $cat['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option style='background: black ;' value="draft" <?php echo ($post['status']=='draft')?'selected':''; ?>>Draft</option>
                    <option style='background: black ;' value="published" <?php echo ($post['status']=='published')?'selected':''; ?>>Published</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Current Image</label><br>
                <?php if ($post['image']): ?>
                    <img src="../uploads/<?php echo $post['image']; ?>" width="120">
                <?php else: ?>
                    <span class="text-muted">No image</span>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Change Image</label>
                <input type="file" class="form-control" name="image">
            </div>

            <button type="submit" class="btn btn-warning w-100">Update Post</button>

        </form>
    </div>
</div>
</main>
</div>
</div>

<?php include 'includes/footer.php'; ?>
