<?php

///Edit Profile///
include 'includes/functions/checksession.php';
$pageTitle = 'Edit Profile';
include 'init.php';

////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

//Start Manage Page
if ($do == 'Manage') {

    //Manage Page

} elseif ($do == 'Edit') {

    $userid = (isset($_GET['userid'])) && !is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
    //echo $_GET['userid'] .'/// ';
    $stmt = $con->prepare("SELECT UserId FROM users");
    $stmt->execute(array($userid));
    $rows = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) {
        if (sha1($rows['UserId']) == $userid) {

            $id = $rows['UserId'];
        }
    }

    $userid = (isset($id)) && is_numeric($id) ? intval($id) : 0;
    echo $id . 'Checked';
    $stmt = $con->prepare("SELECT * FROM users WHERE UserId = ? LIMIT 1");
    $stmt->execute(array($id));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    // if count > 0 this mean the database contain record about username

    if ($count > 0) {

?>


        <h2 class="text-center">Edit Profile !</h2>
        <div class="container ">
            <div class="profile-form">
                <form action="?do=Update" method="POST">
                    <input type="hidden" name="idn" value="<?php echo $_GET['userid']; ?>" autocomplete="off">
                    <div class="form-group">
                        <label class="col-md-4 ">User Name</label>
                        <input class="form-control col-md-6 " type="text" name="username" value="<?php echo $row['UserName']; ?>" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Email address</label>
                        <input class="form-control col-md-6 " type="email" name="email" value="<?php echo $row['Email']; ?>" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Password</label>
                        <input name="oldpass" type="hidden" value="<?php echo $row['Passw']; ?>">
                        <input class="form-control col-md-6 " name="newpass" type="password" value="" autocomplete="new-passowrd">
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 ">Full Name</label>
                        <input class="form-control col-md-6 " type="text" name="full" value="<?php echo $row['FullName']; ?>" autocomplete="off">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> Agree the terms and policy
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button>
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

       /* if (isset($_POST['idn'])) {
            $stmt = $con->prepare("SELECT UserId FROM users");
            $stmt->execute(array($id));
            $rows = $stmt->fetch();
            $count = $stmt->rowCount();
            if ($count > 0) {
                if (sha1($rows['UserId']) == $id) {

                    $id = $rows['UserId'];
                    echo $id;
                }
            }

        } else{ echo $_POST['idn'];}*/
        $id = (isset($_POST['idn'])) && !is_numeric($_POST['idn']) ? intval($_POST['idn']) : 5;
        //echo $_GET['userid'] .'/// ';
        $stmt = $con->prepare("SELECT UserId FROM users");
            $stmt->execute(array($id));
            $rows = $stmt->fetch();
            $count = $stmt->rowCount();
            if ($count > 0) {
                if (sha1($rows['UserId']) == $id) {

                    $id = $rows['UserId'];
                    
                }
            }
        

        //$id     = $_POST['id'];
        $user   = $_POST['username'];
        $email  = $_POST['email'];
        $name   = $_POST['full'];

        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////Passowrd update/////////////////////////////////////////////
        $pass = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']); //////////////
        /////////////////////////////End password edit///////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////
        //////////////////////////////////// Update the database/////////////////////////////////
        $stmt = $con->prepare("UPDATE users SET UserName =? , FullName =? , Passw=? , Email =? WHERE UserId = ? ");
        $stmt->execute(array($user , $name , $pass, $email, $id ));
        // Success Massege
        echo $stmt->rowCount() . ' Success Update';
        ///////////////////////////////////////////////////////////////////////////////////////////////
        //echo  $id. ' - ' . $user . ' - ' . $email . ' - ' . $name;
    } else {
        echo 'sorry';
    }
    ////////////////////////////End Update//////////////////////////////////////////////////////////////////
}



include $tpl . 'footer.php';
?>