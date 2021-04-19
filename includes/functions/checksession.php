<?php
/*
function startsession()
{
    global $session;
    if (isset($session)) {
        (echo 'CRM' . ' ' . $pageTitle;)
    } else {
        echo 'CRM';
    }
*/
session_start();
if(!isset($_SESSION['UserName']) || $_SESSION['UserName'] == ""){
    if(!isset($_COOKIE['UserName'])){
        header("location: index.php");
        exit();
    }
}
/*
if(isset($_SESSION['UserName'])){
    header('Location: dashbord.php');
    exit();
}else {

    header('Location: index.php');
    exit();
}
*/
?>