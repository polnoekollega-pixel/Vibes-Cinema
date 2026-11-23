<?php
/**
 * Vibes Cinema — выход из аккаунта
 */
require_once __DIR__ . '/includes/config.php';
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;