<?php
include 'includes/header.php';
require_once '../config/database.php';

$total_posts = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$published_posts = $pdo->query("SELECT COUNT(*) FROM posts WHERE status='published'")->fetchColumn();
$total_comments = $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
$pending_comments = $pdo->query("SELECT COUNT(*) FROM comments WHERE status='pending'")->fetchColumn();
$total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

$latest_posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

$latest_comments = $pdo->query("SELECT * FROM comments ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="row">

        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="py-4">

                <div class="mb-4">
                    <h3>Welcome, Admin</h3>
                    <p class="text-muted">Here’s an overview of your blog statistics.</p>
                </div>


                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-file-text"></i> Latest Posts</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>img</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($latest_posts as $post): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                                        <td>
                                            <?php if ($post['status'] == 'published'): ?>
                                                <span class="badge bg-success">Published</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Draft</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                        <td>
                                            <?php if ($post['image']): ?>
                                                <img src="../uploads/<?php echo $post['image']; ?>" width="60">
                                            <?php else: ?>
                                                <span class="text-muted">No Image</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-chat-dots"></i> Latest Comments</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Comment</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($latest_comments as $comment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($comment['name']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($comment['comment'], 0, 50)) . '...'; ?></td>
                                        <td>
                                            <?php if ($comment['status'] == 'approved'): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php elseif ($comment['status'] == 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </main>
    </div>
</div>

<?php include 'includes/footer.php'; ?>