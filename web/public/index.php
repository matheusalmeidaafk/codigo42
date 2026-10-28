<?php
$query = $_SERVER['QUERY_STRING'] ?? '';
header('Location: /pages/home.php' . ($query !== '' ? '?' . $query : ''), true, 302);
exit;
