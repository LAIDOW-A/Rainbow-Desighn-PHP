<?php
require 'vendor/autoload.php';
require_once 'config/database.php';

$posts = $pdo->query("SELECT * FROM posts WHERE status='published' ORDER BY created_at DESC")->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog - Premium</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-dark">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-journal-richtext me-2"></i>My Blog
            </a>
            <a class="btn btn-primary rounded-pill px-4" href="admin/index.php">
                <i class="bi bi-speedometer2 me-1"></i> Admin Panel
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-white">Latest Stories</h1>
            <p class="lead text-white-50">Discover amazing content from our community.</p>
        </div>

        <div class="row g-4">
            <?php if (count($posts) > 0): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 glass-card">

                            <div
                                style="height: 200px; overflow: hidden; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                                <?php if (!empty($post['image']) && file_exists("uploads/" . $post['image'])): ?>
                                    <img src="uploads/<?php echo $post['image']; ?>" class="w-100 h-100 object-fit-cover">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/400x200?text=No+Image"
                                        class="w-100 h-100 object-fit-cover">
                                <?php endif; ?>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-white fw-bold"><?php echo htmlspecialchars($post['title']); ?></h5>
                                <p class="card-text text-white-50 flex-grow-1">
                                    <?php
                                    $content = strip_tags($post['content']);
                                    echo substr($content, 0, 100) . '...';
                                    ?>
                                </p>

                                <div
                                    class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-secondary">
                                    <small class="text-white-50">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo date('M j, Y', strtotime($post['created_at'])); ?>
                                    </small>
                                    <a href="post.php?id=<?php echo $post['id']; ?>"
                                        class="btn btn-outline-light btn-sm rounded-pill px-3">
                                        Read More <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-white-50">
                    <p>No posts found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <footer class="text-white-50 text-center py-4 mt-5 border-top border-dark">
        <p class="mb-0">&copy; 2025 My Blog. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>
