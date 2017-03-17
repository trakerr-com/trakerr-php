<?php

require_once(__DIR__ . '/autoload.php');

// initialize the client
$trakerrClient = new \trakerr\TrakerrClient("db91c9dd3cf14c8ce437be1198665d2919713209848755", null);

// Option-2: catch and send error to Trakerr programmatically
try {
    throw new Exception("test exception");
} catch (Exception $e) {
    $trakerr_client->sendError("Error", $e);
}
