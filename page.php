<?php
ob_start();
include 'includes/functions/checksession.php';
$pageTitle="toto";


//Categories => [ MANAGE | EDITE | UPDATE | ADD | INSERT | DELETE | STATES ]

$do = isset($_GET['do']) ? $_GET['do'] :'Manage';

include "init.php";


// If the page is main pade
if ($do == 'Manage'){
    echo 'Welcome You Are In Manage Categories Page';
    echo '<a href="?do=Insert">Add New Categories +</a>';
}elseif ($do == 'Add'){
    echo 'Welcome to Add categories page';
}elseif ($do == 'Insert'){
    echo 'Welcome to Insert categories page';
}elseif ($do == 'Edit'){
    echo 'Welcome to Insert categories page';
}elseif ($do == 'Update'){
    echo 'Welcome to Insert categories page';
}elseif ($do == 'Delete'){
    echo 'Welcome to Insert categories page';
}elseif ($do == 'Approve'){
    echo 'Welcome to Insert categories page';
}else {
    echo 'Error There\'s No page with this name'; 
}


?>