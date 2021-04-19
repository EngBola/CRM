<?php

///Edit Profile///
include 'includes/functions/checksession.php';
$pageTitle = 'Edit Profile';
include 'init.php';


$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

//Start Manage Page
if ($do == 'Manage') {
echo "<h2 class='text-center'>Manage Pages !</h2>";
    //Manage Page

} elseif ($do == 'Edit') {
    $userid = (isset($_GET['userid'])) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
    $stmt = $con->prepare("SELECT * FROM users WHERE UserId = ? LIMIT 1");
    $stmt->execute(array($userid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    // if count > 0 this mean the database contain record about username

    if ($count > 0) {
?>

        <h2 class="text-center">Edit Profile !</h2>
        <div class="container ">
            <div class="profile-form">
                <form action="?do=Update" method="POST">
                    <input class="form-control" type="hidden" name="id" value="<?php echo $row['UserId']; ?>" autocomplete="off">
                    <div class="form-group">
                        <label class="col-md-4 ">User Name</label>
                        <input class="form-control col-md-6 " type="text" name="username"  value="<?php echo $row['UserName']; ?>" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Email address</label>
                        <input class="form-control col-md-6 " type="email" name="email" value="<?php echo $row['Email']; ?>" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="col-md-4">Password</label>
                        <input  name="oldpass" type="hidden" value="<?php echo $row['Passw']; ?>">
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

    }elseif ($do == 'Update'){       ////Go To UPDATE PROFILE PAGES//////////////////////////////
        echo "<h2 class='text-center'>Update Profile !</h2>";

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //GET ALL VARIABLES FROM FORM
            $id     = $_POST['id'];
            $user   = $_POST['username'];
            $email  = $_POST['email'];
            $name   = $_POST['full'];
            /////////////////////////////////////////////////////////////////////////////////////////////
            /////////////////////////////////Passowrd update/////////////////////////////////////////////
            $pass = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']);//////////////
            /////////////////////////////End password edit///////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////
            //////////////////////////////////// Update the database/////////////////////////////////
            $stmt = $con->prepare("UPDATE users SET UserName =? , FullName =? , Passw=? , Email =? WHERE UserId =?");
            $stmt->execute(array($user , $name , $pass, $email , $id));
            // Success Massege
            echo $stmt->rowCount() . ' Success Update';
            ///////////////////////////////////////////////////////////////////////////////////////////////
        }else{
            echo 'sorry';
        }
        ////////////////////////////End Update//////////////////////////////////////////////////////////////////
    }


include $tpl . 'footer.php';
?>