<?php
include 'includes/header.php';
include 'includes/sidebar.php';
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

if (!empty($post['image']) && file_exists("../uploads/".$post['image'])) {
    unlink("../uploads/".$post['image']);
}
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
if ($stmt->execute([$post_id])) {
    echo "<script>window.location='posts.php?success=deleted';</script>";
    exit;
} else {
    echo "<script>alert('Failed to delete post!'); window.location='posts.php';</script>";
    exit;
}
?>
