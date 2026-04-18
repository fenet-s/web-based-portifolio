<?php

require_once __DIR__ . '/config/bootstrap.php';

$_SESSION = [];

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

redirect('index.php');
