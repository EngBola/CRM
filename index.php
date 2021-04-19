<?php
ob_start();

session_start();
$noNavbar = '';
$pageTitle = 'Login Page';
include 'init.php';
if (isset($_SESSION['UserName'])) {
    header("location: dashbord.php");
    exit();
}


?>
<form class="login" action="loginsubmit.php" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <?php
    if (isset($_GET['error']) && $_GET['error'] == 1) :
    ?>
        <span style="color:red">* user name or password is not found</span>
    <?php
    endif;
    ?>
    <input class="form-control" type="text" name="user" placeholder="User Name" autocomplete="off" />

    <div class="input-group ">
        <input class="form-control password" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="pass" type="password" placeholder="Password" autocomplete="new-passowrd">
        <i class="showpass far fa-eye-slash"></i>
    </div>
    <input class="remember" type="checkbox" name="remember" value="true" /><span style="color: #ecf0f1;"> Remember me</span>
    <input class="btn btn-primary btn-block" type="submit" value="login" />
</form>
<?php


include $tpl . 'footer.php';
ob_end_flush();
?>