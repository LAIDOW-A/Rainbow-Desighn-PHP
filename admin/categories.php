<?php
include '../config/database.php';
include 'includes/header.php';


$message = '';
$message_type = '';

if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    if ($name === '') {
        $message = "Category name is required!";
        $message_type = "danger";
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->execute([$name, $description]);
        $message = "Category added successfully!";
        $message_type = "success";
    }
}

if (isset($_GET['del_id'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_GET['del_id']]);
    header("Location: categories.php");
    exit();
}

if (isset($_POST['update_category'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $edit_id = $_GET['edit_id'];
    if ($name === '') {
        $message = "Category name is required!";
        $message_type = "danger";
    } else {
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $description, $edit_id]);
        header("Location: categories.php");
        exit();
    }
}
?>

<div class="container-fluid">
    <div class="row">

        <?php include 'includes/sidebar.php'; ?>

        <div class="col-md-9 col-lg-10 mt-3">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="bi bi-tags"></i> Categories Management</h3>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show">
                    <?php echo $message; ?>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header">Add New Category</div>
                <div class="card-body">
                    <form method="post" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Category Name *</label>
                            <input name="name" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <input name="description" class="form-control" />
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button name="add_category" class="btn btn-success w-100">Add</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Categories List</div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th><th>Name</th><th>Description</th><th>Created At</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM categories ORDER BY created_at DESC");
                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $i = 1;
                            foreach ($rows as $r):
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($r['name']); ?></td>
                                <td><?php echo htmlspecialchars($r['description']); ?></td>
                                <td><?php echo $r['created_at']; ?></td>
                                <td>
                                    <a href="categories.php?edit_id=<?php echo $r['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="categories.php?del_id=<?php echo $r['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if (isset($_GET['edit_id'])):
                $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
                $stmt->execute([$_GET['edit_id']]);
                $cat = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($cat):
            ?>
            <div class="card mt-4">
                <div class="card-header">Edit Category</div>
                <div class="card-body">
                    <form method="post" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Category Name *</label>
                            <input name="name" value="<?php echo htmlspecialchars($cat['name']); ?>" class="form-control" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <input name="description" value="<?php echo htmlspecialchars($cat['description']); ?>" class="form-control" />
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button name="update_category" class="btn btn-warning w-100">Update</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; endif; ?>

        </div>
    </div>
</div>


<?php include 'includes/footer.php'; ?>


