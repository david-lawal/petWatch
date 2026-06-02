<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//load required class
require_once('models/UserDataSet.php');

//make view class
$view = new stdClass();
$view->pageTitle = "Login";
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    //store username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    //check if username is in database
    $userModel = new UserDataSet();
    $user =$userModel->findUser($username);

    if($user)
    {

        if (true)
        {
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['role'] = $user->getRole();

            //redirect
            header('Location: index.php');
            exit;
        }
        else
        {
            $error = 'Invalid username or password';
        }
    }
    else
    {
        $error = 'User not found';
    }
}

require_once('views/login.phtml');