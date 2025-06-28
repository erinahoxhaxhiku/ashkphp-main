<?php
require_once 'config/database.php';
require_once 'classes/User.php';

session_start();

$user = new User($pdo);
$user->logout();

header('Location: index.php');
exit(); 