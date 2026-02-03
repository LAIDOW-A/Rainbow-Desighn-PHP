<?php
require_once 'config/database.php';

$post_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND status='published'");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found!");
}

$pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?")->execute([$post_id]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Premium Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-journal-richtext me-2"></i>My Blog
            </a>
            <a class="btn btn-outline-light rounded-pill px-4" href="index.php">
                <i class="bi bi-arrow-left me-1"></i> Back to Home
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <article class="card glass-card border-0 mb-5 text-white">

                    <?php if (!empty($post['image']) && file_exists('uploads/' . $post['image'])): ?>
                        <img src="uploads/<?php echo $post['image']; ?>" class="card-img-top object-fit-cover"
                            style="height: 400px; border-radius: 16px 16px 0 0;"
                            alt="<?php echo htmlspecialchars($post['title']); ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/800x400?text=No+Image" class="card-img-top object-fit-cover"
                            style="height: 400px; border-radius: 16px 16px 0 0;" alt="No Image">
                    <?php endif; ?>

                    <div class="card-body p-4 p-md-5">
                        <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($post['title']); ?></h1>

                        <div class="d-flex align-items-center text-white-50 mb-4 border-bottom border-secondary pb-3">
                            <span class="me-3"><i class="bi bi-calendar3 me-1"></i>
                                <?php echo date('F j, Y', strtotime($post['created_at'])); ?></span>
                            <span><i class="bi bi-eye me-1"></i> <?php echo $post['views']; ?> Views</span>
                        </div>

                        <div class="post-content lh-lg">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>
                    </div>
                </article>

                <div class="card glass-card border-0 p-4 p-md-5 text-white">
                    <h3 class="fw-bold mb-4"><i class="bi bi-chat-dots me-2"></i>Comments</h3>

                    <form method="POST" class="mb-5">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                            </div>
                            <div class="col-12">
                                <textarea name="comment" class="form-control" rows="3"
                                    placeholder="Write your comment here..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                                    <i class="bi bi-send me-1"></i> Post Comment
                                </button>
                            </div>
                        </div>
                    </form>

                    <?php
                    if ($_POST) {
                        $name = $_POST['name'];
                        $email = $_POST['email'];
                        $comment = $_POST['comment'];
                        if ($name && $email && $comment) {
                            $pdo->prepare("INSERT INTO comments (post_id, name, email, comment, status, created_at) VALUES (?, ?, ?, ?, 'approved', NOW())")
                                ->execute([$post_id, $name, $email, $comment]);
                            echo "<script>window.location.href=window.location.href;</script>";
                        }
                    }

                    $comments_stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = ? AND status = 'approved' ORDER BY created_at DESC");
                    $comments_stmt->execute([$post_id]);
                    $comments = $comments_stmt->fetchAll();
                    ?>

                    <div class="comments-list">
                        <?php if (count($comments) > 0): ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="d-flex glass-card p-3 mb-3 border border-secondary rounded-3">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($comment['name'], 0, 1)); ?>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($comment['name']); ?></h6>
                                        <small
                                            class="text-white-50 d-block mb-2"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></small>
                                        <p class="mb-0 text-white-50">
                                            <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-white-50">Be the first to comment!</p>
                        <?php endif; ?>
                    </div>

                </div>



            </div>
        </div>
    </div>

    <footer class="text-white-50 text-center py-4 mt-5 border-top border-dark">
        <p class="mb-0">&copy; 2025 My Blog. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>