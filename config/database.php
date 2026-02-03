<?php
$database_url = getenv('DATABASE_URL');

if ($database_url) {
    $url = parse_url($database_url);
    $host = $url["host"];
    $port = $url["port"];
    $username = $url["user"];
    $password = $url["pass"];
    $dbname = ltrim($url["path"], '/');
} else {
    $host = "localhost";
    $dbname = "phpblog";
    $username = "root";
    $password = "";
}

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
