<?php
require "Users.php";

session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Credentials', 'true');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$user = new Users;

$action = isset($_GET['action']) ? $_GET['action'] : NULL;
switch ($action) {
    case 'Login':
        $user ->login(isset($_POST['user']), isset($_POST['pass']));
        if(isset($_SESSION['logedIn'])) {
        //echo "Ulogovani ste";
       // echo "<br>";
        // vracanje usera
    }
    break;
    case 'Add':
        $user ->addUser();
        break;

        case 'AccessToken':
        $user ->checkAccessToken($_GET['user'], $_GET['accesstoken']);
        break;
    case 'Edit':
        $user ->editUser($_GET['id']);
        break;
    case 'Show':
        $user ->getUsers();
        break;

    case 'ShowID':
        $user ->getUser($_GET['id']);
        break;
    case 'Delete':
        $user ->deleteUser($_GET['id']);
        break;
}
?>