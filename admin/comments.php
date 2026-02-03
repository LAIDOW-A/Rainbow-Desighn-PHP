<?php
include 'includes/header.php';
require_once '../config/database.php';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        $stmt = $pdo->prepare("UPDATE comments SET status = 'approved' WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Comment approved successfully!";
        $message_type = "success";
    } elseif ($action == 'reject') {
        $stmt = $pdo->prepare("UPDATE comments SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Comment rejected successfully!";
        $message_type = "warning";
    } elseif ($action == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Comment deleted successfully!";
        $message_type = "danger";
    }
}

$stmt = $pdo->query("SELECT comments.*, posts.title AS post_title FROM comments JOIN posts ON comments.post_id = posts.id ORDER BY created_at DESC");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Comments Management</h1>
            </div>

            <?php if (isset($message)): ?>
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php
            $total_comments = count($comments);
            $approved_comments = count(array_filter($comments, fn($c) => $c['status'] == 'approved'));
            $rejected_comments = count(array_filter($comments, fn($c) => $c['status'] == 'rejected'));
            ?>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card glass-card border-0 text-white h-100"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-0 fw-bold"
                                    style="-webkit-text-fill-color: #fff !important; color: #fff !important;">
                                    <?php echo $total_comments; ?></h3>
                                <small>Total Comments</small>
                            </div>
                            <i class="bi bi-chat-dots fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card glass-card border-0 text-white h-100"
                        style="background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-0 fw-bold"
                                    style="-webkit-text-fill-color: #fff !important; color: #fff !important;">
                                    <?php echo $approved_comments; ?></h3>
                                <small>Approved</small>
                            </div>
                            <i class="bi bi-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card glass-card border-0 text-white h-100"
                        style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="mb-0 fw-bold"
                                    style="-webkit-text-fill-color: #fff !important; color: #fff !important;">
                                    <?php echo $rejected_comments; ?></h3>
                                <small>Rejected</small>
                            </div>
                            <i class="bi bi-x-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card glass-card border-0">
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Author</th>
                                <th>Comment</th>
                                <th>Post</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($comments) > 0): ?>
                                <?php foreach ($comments as $comment): ?>
                                    <tr>
                                        <td><?php echo $comment['id']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($comment['name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($comment['email']); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars(substr($comment['comment'], 0, 50)) . '...'; ?></td>
                                        <td><?php echo htmlspecialchars($comment['post_title']); ?></td>
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
                                        <td>
                                            <?php if ($comment['status'] != 'approved'): ?>
                                                <a href="comments.php?action=approve&id=<?php echo $comment['id']; ?>"
                                                    class="btn btn-sm btn-success">Approve</a>
                                            <?php endif; ?>

                                            <?php if ($comment['status'] != 'rejected'): ?>
                                                <a href="comments.php?action=reject&id=<?php echo $comment['id']; ?>"
                                                    class="btn btn-sm btn-warning">Reject</a>
                                            <?php endif; ?>
                                            <a href="comments.php?action=delete&id=<?php echo $comment['id']; ?>"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No comments found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include 'includes/footer.php'; ?>