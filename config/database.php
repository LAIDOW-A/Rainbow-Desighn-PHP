<?php
$mongoUri = getenv('DATABASE_URL');

try {
    $manager = new MongoDB\Driver\Manager($mongoUri);
    $command = new MongoDB\Driver\Command(['ping' => 1]);
    $manager->executeCommand('db', $command);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
