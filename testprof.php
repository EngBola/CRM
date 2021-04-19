<?php

///Edit Profile///
include 'includes/functions/checksession.php';
$pageTitle = 'Edit Profile';
include 'init.php';

$userid = (isset($_GET['userid'])) && !is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
$stmt = $con->prepare("SELECT UserId FROM users WHERE sha1(UserId) = $userid");
$stmt->execute(array($userid));
$row = $stmt->fetch();
$counts = $stmt->rowCount();
if ($counts > 0) {
    $id = $row['UserId'];
}
////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

//Start Manage Page
if ($do == 'Manage') {

    //Manage Page

} elseif ($do == 'Edit') {

    //$userid = (isset($_GET['userid'])) && !is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
    //echo $_GET['userid'] .'/// ';
    $userid = (isset($id)) && is_numeric($id) ? intval($id) : 0;
    $stmt = $con->prepare("SELECT * FROM users WHERE UserId = ? LIMIT 1");
    $stmt->execute(array($id));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    // if count > 0 this mean the database contain record about username
    if ($count > 0) {
?>
        <h2 class="text-center">Edit Profile !</h2>
        <div class="container ">
            <div class="profile-form rounded ">
                <form class="border border-white" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $_GET['userid']; ?>" autocomplete="off">
                    <div class="input-group mb-5 col-md-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">Username</span>
                        </div>
                        <input type="text" class="form-control " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" type="text" name="username" value="<?php echo $row['UserName']; ?>" autocomplete="off" required="required">
                    </div>
                    <div class="input-group mb-5 col-md-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
                        </div>
                        <input type="text" class="form-control " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" type="email" name="email" value="<?php echo $row['Email']; ?>" autocomplete="off" required="required">
                    </div>
                    <div class="input-group mb-5 col-md-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">Password</span>
                        </div>
                        <input name="oldpass" type="hidden" value="<?php echo $row['Passw']; ?>">
                        <input type="text" class="form-control " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="newpass" type="password" value="" autocomplete="new-passowrd">
                    </div>
                    <div class="input-group mb-5 col-md-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">Full Name</span>
                        </div>
                        <input type="text" class="form-control " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" type="text" name="full" value="<?php echo $row['FullName']; ?>" autocomplete="off" required="required">
                    </div>

                    <!--
                    <div class="input-group mb-3">
                        <div class="input-group-prepend"><span class="input-group-text" id="inputGroup-sizing-default">User Name</span></div>
                        <input class="form-control col-md-6 " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" type="text" name="username" value="<?php echo $row['UserName']; ?>" autocomplete="off" required="required">
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Email address</label>
                        <input class="form-control col-md-6 " type="email" name="email" value="<?php echo $row['Email']; ?>" autocomplete="off" required="required">
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Password</label>
                        
                        <input class="form-control col-md-6 " name="newpass" type="password" value="" autocomplete="new-passowrd">
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 ">Full Name</label>
                        <input class="form-control col-md-6 " type="text" name="full" value="<?php echo $row['FullName']; ?>" autocomplete="off" required="required">
                    </div>
                    -->
                    <button type="submit" class="btn btn-outline-primary btn-block ">UPDATE</button>
                </form>
            </div>



        </div>
<?php
    } else {
        echo '<h3 class="text-center" style="color:red"> ! Not Found Any Data For this Account </h3>';
    }
} elseif ($do == 'Update') {       ////Go To UPDATE PROFILE PAGES//////////////////////////////
    echo "<h2 class='text-center'>Update Profile !</h2>";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //GET ALL VARIABLES FROM FORM

        $userid = (isset($_POST['userid'])) && !is_numeric($_POST['userid']) ? intval($_POST['userid']) : 0;
        $stmt = $con->prepare("SELECT UserId FROM users WHERE sha1(UserId) = $userid");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $counts = $stmt->rowCount();
        if ($counts > 0) {
            $id = $row['UserId'];
        }


        //$id     = $_GET['id'];
        $user   = $_POST['username'];
        $email  = $_POST['email'];
        $name   = $_POST['full'];

        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////Passowrd update/////////////////////////////////////////////
        $pass = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']); //////////////
        /////////////////////////////End password edit///////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////Start Validate form ////////////////////////////////////////////
        $formErrors = array();
        if (strlen($user) < 4) {
            $formErrors[] = '<div class="alert alert-danger" role="alert">Username Must Be More Than <strong>4 Characters</strong></div>';
        }
        if (strlen($user) > 20) {
            $formErrors[] = '<div class="alert alert-danger" role="alert">Username Must Be Less Than <strong>20 Characters</strong></div>';
        }
        if (empty($user)) {
            $formErrors[] = '<div class="alert alert-danger" role="alert">Username Can\'t Be <strong>Empty</strong></div>';
        }
        if (empty($name)) {
            $formErrors[] = '<div class="alert alert-danger" role="alert">Full Name Can\'t Be <strong>Empty</strong></div>';
        }
        if (empty($email)) {
            $formErrors[] = '<div class="alert alert-danger" role="alert">Email Can\'t Be <strong>Empty</strong></div>';
        }
        foreach ($formErrors as $error ) {
            $do= 'Edit';
            //header("location: testprof.php?do=Edit&error=$error");
            echo $error;
        }
        if (empty($formErrors)) {


            //////////////////////////////////// Update the database/////////////////////////////////
            $stmt = $con->prepare("UPDATE users SET UserName =? , FullName =? , Passw=? , Email =? WHERE UserId = ? ");
            $stmt->execute(array($user, $name, $pass, $email, $id));
            // Success Massege
            echo ' <div class="alert alert-success"><strong>' . $stmt->rowCount() . ' Success Update</strong>';
            ///////////////////////////////////////////////////////////////////////////////////////////////
            echo  $id . ' - ' . $user . ' - ' . $email . ' - ' . $name;
        }
    } else {
        echo 'sorry';
    }
    ////////////////////////////End Update//////////////////////////////////////////////////////////////////

}

include $tpl . 'footer.php';
?>