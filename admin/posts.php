<?php 
include 'includes/header.php';
require_once '../config/database.php';

$stmt = $pdo->prepare("
    SELECT posts.*, categories.name AS category_name 
    FROM posts 
    LEFT JOIN categories ON posts.category_id = categories.id
    ORDER BY posts.id DESC
");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="row">

        <?php include 'includes/sidebar.php'; ?>

        
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

<div class="py-4 d-flex justify-content-between align-items-center border-bottom">
    <h2><i class="bi bi-file-text"></i> Posts Management</h2>
    <a href="addpost.php" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Post
    </a>
</div>

<?php if(isset($_GET['success'])): ?>
<div class="alert alert-success mt-2 alert-dismissible fade show">
    ✅ Post added successfully!
    <button class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card mt-3">
    
    <div class="card-header">
        <h5 class="card-title mb-0">All Posts</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php 
                $i = 1;
                foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo $i++; ?></td>

                        <td>
                            <?php if($post['image']): ?>
                                <img src="../uploads/<?php echo $post['image']; ?>" width="60">
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </td>

                        <td><?php echo $post['title']; ?></td>
                        <td><?php echo $post['category_name'] ?? 'Uncategorized'; ?></td>

                        <td>
                            <?php if($post['status'] == 'published'): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </td>

                        <td><?php echo date("Y-m-d", strtotime($post['created_at'])); ?></td>

                        <td>
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete_post.php?id=<?php echo $post['id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>
</div>
</div>
</div>

</main>

<?php include 'includes/footer.php'; ?>
