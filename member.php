<?php
ob_start();
///Edit Profile///
include 'includes/functions/checksession.php';
////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////
include 'init.php';
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
//Start Manage Page
if ($do == 'Manage' &&  $_SESSION['States'] == 1) {
    $pageTitle = 'Manage Page'; //Manage Page
    $query = '';
    if (isset($_GET['reg']) && $_GET['reg'] == 'pending') {
        $query = 'WHERE GroupId != 1 AND RegStates = 0';
        echo '<div class="text-center"> <span style = " color :Red;">Pending Members</span></div>';
    }
?>
    <div class="container">
        <h4 class='text-center'>Manage Member !</h4>
        <div class="row mt-5">
            <div class="col-md-8 col-sm-3 mb-3">
                <a href='member.php?do=Add' class="btn btn-primary mr-5 "><i class="fas fa-user-plus"></i> Add User</a>
            </div>
            <div class="input-group col-md-4 col-sm-7 mr-0 mb-3">
                <div class="input-group-append">
                    <span class="input-group-text" id="search">Search </span>
                </div>
                <input type="text" class="form-control " placeholder="Search">
                <div class="input-group-append">
                    <span class="input-group-text" id="search"><i class="fas fa-search"></i> </span>
                </div>
            </div>
        </div>
        <div class="table-responsive ">
            <table class="table  table-hover table-bordered text-center maintable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">UserName</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Register Date</th>
                        <th scope="col">Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $con->prepare("SELECT * FROM users $query");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    $count = $stmt->rowCount();
                    if ($count > 0) {
                        for ($i = 1; $i < $count + 1; $i++) {
                            foreach ($rows as $row) {
                    ?>
                                <tr>
                                    <th scope="row"><?php echo $i++; ?></th>
                                    <td><?php echo $row['UserName']; ?></td>
                                    <td><?php echo $row['FullName']; ?></td>
                                    <td><?php echo $row['Email']; ?></td>
                                    <td><?php echo $row['regDate']; ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="member.php?do=Edit&userid=<?php echo sha1($row['UserId']); ?>" class="btn btn-outline-success btn-sm"><i class="fa fa-edit"></i></a>
                                            <a type="button" class="btn btn-outline-danger confirm" href="member.php?do=Delete&userid=<?php echo sha1($row['UserId']); ?>"><i class="fa fa-times"></i></a>
                                            <?php
                                            echo checkBox($row['RegStates'], 'do=echeck&userid=', sha1($row['UserId']));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                    <?php }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="container text-center member">
        <div class="row">
            <?php
            $stmt = $con->prepare("SELECT * FROM users $query");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $count = $stmt->rowCount();
            if ($count > 0) {
                foreach ($rows as $row) {
            ?>

                    <div class="mem col-6 col-sm-6 col-md-2 ">
                        <div class="memb text-center">
                            <div class="memhead ">
                                <div class="memimg text-center rounded-circle">
                                    <div class="text-center">
                                        <img src="./layout/images/IMG_20170614_180858.jpg" alt="pic-<?php echo $row['FullName']; ?>">
                                    </div>
                                </div>
                            </div>
                            <h5><?php echo $row['FullName']; ?></h5>
                            <div class="memcontant text-left">
                                <div>
                                    User Name : <span><?php echo $row['UserName']; ?></span>
                                </div>
                                <div>
                                    Email : <span><?php echo $row['Email']; ?></span>
                                </div>
                                <div>
                                    Join Date : <span><?php echo $row['regDate']; ?></span>
                                </div>
                                <div>
                                    Status : <span>
                                        <?php
                                        echo dashlist($row['RegStates'], 'member.php?do=echeck&userid=', sha1($row['UserId']));

                                        ?>
                                    </span>
                                </div>
                            </div>
                            <div class="memfooter ">
                                <div class="btn-group" role="group">
                                    <a class="btn btn-success btn-sm badge" href="member.php?do=Edit&userid=<?php echo sha1($row['UserId']); ?>">
                                        <i class="fa fa-edit"></i> Edit</a>
                                    <a class="btn btn-danger btn-sm badge confirm" href="member.php?do=Delete&userid=<?php echo sha1($row['UserId']); ?>">
                                        <i class="fa fa-times"></i> Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php
                }
            }
            ?>

        </div>
    </div>
<?php
} elseif ($do == 'Add' &&  $_SESSION['States'] == 1) {
    echo '
    <h4 class="text-center">Add New Member !</h4>
        ';
?>
    <div class="container ">
        <div class="profile-form rounded ">
            <?php
            if (isset($_GET['success']) && $_GET['success'] == 1) :
                echo  '<div class="alert alert-success" role="alert"><strong> Success Add Member </strong></div>';


            endif; ?>
            <form class="border border-white" action="?do=Insert" method="POST">
                <?php
                if (isset($_GET['unerr']) && $_GET['unerr'] == 1) :
                ?>
                    <div class="alert alert-danger" role="alert">Username Must Be More Than <strong>4 Characters</strong></div>
                <?php
                elseif (isset($_GET['unerr']) && $_GET['unerr'] == 2) :
                ?>
                    <div class="alert alert-danger" role="alert">Username Must Be Less Than <strong>20 Characters</strong></div>
                <?php
                elseif (isset($_GET['unerr']) && $_GET['unerr'] == 3) :
                ?>
                    <div class="alert alert-danger" role="alert">Username Can\'t Be <strong>Empty</strong></div>
                <?php
                elseif (isset($_GET['unerr']) && $_GET['unerr'] == 4) :
                ?>
                    <div class="alert alert-danger" role="alert">Username already <strong>Exist</strong></div>
                <?php
                endif;
                ?>
                <div class="input-group mb-5 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">Username</span>
                    </div>
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" type="text" name="username" value="" autocomplete="off" required="required">
                </div>
                <?php
                if (isset($_GET['fnerr']) && $_GET['fnerr'] == 1) :
                ?>
                    <div class="alert alert-danger" role="alert">Full Name Must Be More Than <strong>4 Characters</strong></div>
                <?php
                elseif (isset($_GET['fnerr']) && $_GET['fnerr'] == 2) :
                ?>
                    <div class="alert alert-danger" role="alert">Full Name Must Be Less Than <strong>20 Characters</strong></div>
                <?php
                elseif (isset($_GET['fnerr']) && $_GET['fnerr'] == 3) :
                ?>
                    <div class="alert alert-danger" role="alert">Full Name Can\'t Be <strong>Empty</strong></div>
                <?php
                endif;
                ?>
                <div class="input-group mb-5 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">Full Name</span>
                    </div>
                    <input type="text" class="form-control " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" type="text" name="full" value="" autocomplete="off" required="required">
                </div>
                <?php
                if (isset($_GET['emerr']) && $_GET['emerr'] == 1) :
                ?>
                    <div class="alert alert-danger" role="alert">Email Can\'t Be <strong>Empty</strong></div>
                <?php
                elseif (isset($_GET['emerr']) && $_GET['emerr'] == 2) :
                ?>
                    <div class="alert alert-danger" role="alert"> Invalid Email <strong>Format</strong></div>
                <?php
                endif;
                ?>
                <div class="input-group mb-5 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">Email</span>
                    </div>
                    <input type="text" class="form-control " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" type="email" name="email" value="" autocomplete="off" required="required">
                </div>
                <?php
                if (isset($_GET['passerr']) && $_GET['passerr'] == 2) :
                ?>
                    <div class="alert alert-danger" role="alert">Password must be mor Than <strong> 8 </strong>characters</div>
                <?php
                elseif (isset($_GET['passerr']) && $_GET['passerr'] == 1) :
                ?>
                    <div class="alert alert-danger" role="alert">Password Can\'t Be <strong>Empty</strong></div>
                <?php
                endif;
                ?>
                <div class="input-group mb-5 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">Password</span>
                    </div>
                    <input class="form-control password" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="pass" type="password" value="" autocomplete="new-passowrd">
                    <i class="showpass far fa-eye-slash"></i>
                </div>
                <?php
                if (isset($_GET['rpasserr']) && $_GET['rpasserr'] == 4) :
                ?>
                    <div class="alert alert-danger" role="alert">Re-Password <strong>Not matching</strong></div>
                <?php
                elseif (isset($_GET['rpasserr']) && $_GET['rpasserr'] == 3) :
                ?>
                    <div class="alert alert-danger" role="alert">Re-Password Can\'t Be <strong>Empty</strong></div>
                <?php
                endif;
                ?>
                <div class="input-group mb-3 ">

                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-default">Re-Password</span>
                    </div>
                    <input class="form-control password" name="repass" type="password" value="" autocomplete="new-passowrd">
                    <i class="showpass far fa-eye-slash"></i>
                </div>

                <button type="submit" class="btn btn-outline-primary btn-block ">Save</button>
            </form>
        </div>
    </div>
    <?php
} elseif ($do == 'Insert') {
    ////Go To Insert Member PAGES//////////////////////////////

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "<h4 class='text-center'>Inser Member !</h4>";
        /////////////////////////////Start Validate form ////////////////////////////////////////////
        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $matchname = true;
        $validmail = true;
        $matchphone = true;
        $validpaid = true;
        $validurl = true;
        $validfile = true;
        $validpass  = true;
        $error_redirect_url = "location: member.php?do=Add&";

        $user = test_input($_POST['username']);
        $check = checkItem('UserName', 'users', $user);
        if ($check === 1) {
            $matchname = false;
            $error_redirect_url .= "unerr=4&";
        } elseif (strlen($user) < 4) {
            $matchname = false;
            $error_redirect_url .= "unerr=1&";
        } elseif (strlen($user) > 20) {
            $matchname = false;
            $error_redirect_url .= "unerr=2&";
        } elseif (empty($user)) {
            $matchname = false;
            $error_redirect_url .= "unerr=3&";
        } else {
            $error_redirect_url .= "unerr=0&";
        }

        $name = test_input($_POST['full']);
        if (strlen($name) < 4) {
            $matchname = false;
            $error_redirect_url .= "fnerr=1&";
        } elseif (strlen($name) > 20) {
            $matchname = false;
            $error_redirect_url .= "fnerr=2&";
        } elseif (empty($name)) {
            $matchname = false;
            $error_redirect_url .= "fnerr=3&";
        } else {
            $error_redirect_url .= "fnerr=0&";
        }


        $email = test_input($_POST['email']);
        if (empty($email)) {
            $validmail = false;
            $error_redirect_url .= "emerr=1&";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validmail = false;
            $error_redirect_url .= "emerr=2&";
        } else {
            $error_redirect_url .= "emerr=0&";
        }

        $pass = test_input($_POST['pass']);
        if (empty($pass)) {
            $validpass = false;
            $error_redirect_url .= "passerr=1&";
        } elseif (strlen($pass) < 8) {
            $validpass = false;
            $error_redirect_url .= "passerr=2&";
        } else {
            $error_redirect_url .= "passerr=0&";
        }

        $repass = test_input($_POST['repass']);
        if (empty($_POST['repass'])) {
            $validpass = false;
            $error_redirect_url .= "rpasserr=3&";
        } elseif ($pass != $repass) {
            $validpass = false;
            $error_redirect_url .= "rpasserr=4&";
        } else {
            $error_redirect_url .= "rpasserr=0&";
        }
        /////////////////////////////End Validate form ////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////Passowrd update/////////////////////////////////////////////
        $pass = sha1($_POST['pass']); //////////////
        /////////////////////////////End password edit///////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////

        if ($matchname == true && $validmail == true && $validpass  == true) {

            $stmt = $con->prepare("INSERT INTO users (UserName , FullName , Passw , Email ,RegStates, regDate) VALUES ('{$user}','{$name}','{$pass}','{$email}', 1, now())");
            $stmt->execute();

            header("location: member.php?do=Add&success=1?");
        } else {
            header($error_redirect_url);
        }
    } else {
        $testMsg = 'Sorry Can\'t Found This Page';
        redirectHome($testMsg, 3);
    }
    ////////////////////////////End Insert//////////////////////////////////////////////////////////////////

} elseif ($do == 'Edit') {
    $pageTitle = 'Profile';
    $userid = (isset($_GET['userid'])) && !is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
    $id = wizardhash('UserId', 'users', $userid);
    $stmt = $con->prepare("SELECT * FROM users WHERE UserId = ? LIMIT 1");
    $stmt->execute(array($id));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) {
        /*
    }

    $userid = (isset($id)) && is_numeric($id) ? intval($id) : 0;
    $stmt = $con->prepare("SELECT * FROM users WHERE UserId = ? LIMIT 1");
    $stmt->execute(array($id));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    // if count > 0 this mean the database contain record about username
    if ($count > 0) {*/
    ?>

        <h4 class="text-center">Edit Profile !</h4>


        <div class="container ">
            <div class="profile-form rounded ">

                <?php
                if (isset($_GET['success']) && $_GET['success'] == 1) :
                ?>
                    <div id="sMessage" class="alert alert-success" role="alert"><strong> Success Update </strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <form class="border border-white" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $_GET['userid']; ?>" autocomplete="off">
                    <?php
                    if (isset($_GET['unerr']) && $_GET['unerr'] == 1) :
                    ?>
                        <div id="sMessage" class="alert alert-danger" role="alert">Username Must Be More Than <strong>4 Characters</strong></div>
                    <?php
                    elseif (isset($_GET['unerr']) && $_GET['unerr'] == 2) :
                    ?>
                        <div class="alert alert-danger" role="alert">Username Must Be Less Than <strong>20 Characters</strong></div>
                    <?php
                    elseif (isset($_GET['unerr']) && $_GET['unerr'] == 3) :
                    ?>
                        <div id="sMessage" class="alert alert-danger" role="alert">Username Can\'t Be <strong>Empty</strong></div>
                    <?php
                    elseif (isset($_GET['unerr']) && $_GET['unerr'] == 4) :
                    ?>
                        <div class="alert alert-danger" role="alert">Username already <strong>Exist</strong></div>
                    <?php
                    endif;
                    ?>
                    <div class="input-group mb-5 col-md-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">Username</span>
                        </div>
                        <input type="text" class="form-control " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" type="text" name="username" value="<?php echo $row['UserName']; ?>" autocomplete="off" required="required">

                    </div>

                    <?php
                    if (isset($_GET['emerr']) && $_GET['emerr'] == 1) :
                    ?>
                        <div class="alert alert-danger" role="alert">Email Can\'t Be <strong>Empty</strong></div>
                    <?php
                    elseif (isset($_GET['emerr']) && $_GET['emerr'] == 2) :
                    ?>
                        <div class="alert alert-danger" role="alert"> Invalid Email <strong>Format</strong></div>
                    <?php
                    endif;
                    ?>
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
                        <input type="password" class="form-control " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" name="newpass" type="password" value="" autocomplete="new-passowrd">
                    </div>
                    <?php
                    if (isset($_GET['fnerr']) && $_GET['fnerr'] == 1) :
                    ?>
                        <div class="alert alert-danger" role="alert">Full Name Must Be More Than <strong>4 Characters</strong></div>
                    <?php
                    elseif (isset($_GET['fnerr']) && $_GET['fnerr'] == 2) :
                    ?>
                        <div class="alert alert-danger" role="alert">Full Name Must Be Less Than <strong>20 Characters</strong></div>
                    <?php
                    elseif (isset($_GET['fnerr']) && $_GET['fnerr'] == 3) :
                    ?>
                        <div class="alert alert-danger" role="alert">Full Name Can\'t Be <strong>Empty</strong></div>
                    <?php
                    endif;
                    ?>
                    <div class="input-group mb-5 col-md-12">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-default">Full Name</span>
                        </div>
                        <input type="text" class="form-control " aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" type="text" name="full" value="<?php echo $row['FullName']; ?>" autocomplete="off" required="required">
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-block ">UPDATE</button>
                </form>
            </div>
        </div>
    <?php


    } else {
        $theMsg = "
            <div class='container'>
            <div class='alert alert-danger'>
            <h2>! Not Found Any Data For this Account</h2>
            <p>You Will Be Redirect to Home Page</p>
            </div>
            </div>";
        redirectHome($theMsg, 'back');
    }
} elseif ($do == 'Update') {
    ////Go To UPDATE PROFILE PAGES//////////////////////////////

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $userid = (isset($_POST['userid'])) && !is_numeric($_POST['userid']) ? $_POST['userid'] : 0;
        // $check = wizardhash('UserId' , 'users' , $userid);
        $id = wizardhash('UserId', 'users', $userid);
        echo "<h4 class='text-center'>Update Profile !</h4>";
        /////////////////////////////Start Validate form ////////////////////////////////////////////
        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $matchname = true;
        $validmail = true;
        $matchphone = true;
        $validpaid = true;
        $validurl = true;
        $validfile = true;
        $userid = $_POST['userid'];
        $error_redirect_url = "location: member.php?do=Edit&userid=$userid&";

        $user = test_input($_POST['username']);
        $statement = $con->prepare("SELECT * FROM users WHERE UserName = '{$user}' AND UserId != '{$id}' ");
        $statement->execute();
        $row = $statement->fetchAll();
        $count = $statement->rowCount();
        if ($count > 0) {
            $matchname = false;
            $error_redirect_url .= "unerr=4&";
        } elseif (strlen($user) < 4) {
            $matchname = false;
            $error_redirect_url .= "unerr=1&";
        } elseif (strlen($user) > 20) {
            $matchname = false;
            $error_redirect_url .= "unerr=2&";
        } elseif (empty($user)) {
            $matchname = false;
            $error_redirect_url .= "unerr=3&";
        } else {
            $error_redirect_url .= "unerr=0&";
        }

        $name = test_input($_POST['full']);
        if (strlen($name) < 4) {
            $matchname = false;
            $error_redirect_url .= "fnerr=1&";
        } elseif (strlen($name) > 20) {
            $matchname = false;
            $error_redirect_url .= "fnerr=2&";
        } elseif (empty($name)) {
            $matchname = false;
            $error_redirect_url .= "fnerr=3&";
        } else {
            $error_redirect_url .= "fnerr=0&";
        }


        $email = test_input($_POST['email']);
        if (empty($email)) {
            $validmail = false;
            $error_redirect_url .= "emerr=1&";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validmail = false;
            $error_redirect_url .= "emerr=2&";
        } else {
            $error_redirect_url .= "emerr=0&";
        }
        /////////////////////////////End Validate form ////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////Passowrd update/////////////////////////////////////////////
        $pass = empty($_POST['newpass']) ? $_POST['oldpass'] : sha1($_POST['newpass']); //////////////
        /////////////////////////////End password edit///////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////

        if ($matchname == true && $validmail == true) {

            $stmt = $con->prepare("UPDATE users SET UserName =? , FullName =? , Passw=? , Email =? WHERE UserId = ? ");
            $stmt->execute(array($user, $name, $pass, $email, $id));
            // Success Massege
            //echo ' <div class="alert alert-success"><strong>' . $stmt->rowCount() . ' Success Update</strong>';
            ///////////////////////////////////////////////////////////////////////////////////////////////
            //echo  $id . ' - ' . $user . ' - ' . $email . ' - ' . $name;
            $userid = $_POST['userid'];
            //echo $userid;

            header("location: member.php?do=Edit&userid=$userid&success=1?");
        } else {
            header($error_redirect_url);
        }
    } else {
        $theMsg = "
            <div class='container'>
            <div class='alert alert-danger'>
            <h2>Sorry Can\'t Found This Page</h2>
            <p>You Will Be Redirect to Home Page</p>
            </div>
            </div>";
        redirectHome($theMsg, 'back');
    }
    ////////////////////////////End Update//////////////////////////////////////////////////////////////////
} elseif ($do == 'Delete') {
    ////Go To UPDATE PROFILE PAGES//////////////////////////////

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // }

        $userid = (isset($_GET['userid'])) && !is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
        $id = wizardhash('UserId', 'users', $userid);
        $stmt = $con->prepare("DELETE FROM users WHERE UserId = $id ");
        $stmt->execute();

        $theMsg = "
            <div class='container text-center'>
            <div class='alert alert-success '>
            <h2>Successfull Delete User</h2>
            <p>You Will Be Redirect to Home Page</p>
            </div>
            </div>";
        $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'member.php';

        redirectHome($theMsg,  $url);
        // header("location: member.php?");
    } else {
        $theMsg = "
    <div class='container'>
    <div class='alert alert-danger'>
    <h2>Sorry Can\'t Found This Page</h2>
    <p>You Will Be Redirect to Home Page</p>
    </div>
    </div>";
        redirectHome($theMsg, 'back');
    }
} elseif ($do == 'echeck') {
    $regstates = $_GET['reg'];
    $userid = (isset($_GET['userid'])) && !is_numeric($_GET['userid']) ? $_GET['userid'] : 0;
    $id = wizardhash('UserId', 'users', $userid);

    /* 
    $stmt = $con->prepare("SELECT UserId FROM users WHERE sha1(UserId) = $userid");
    $stmt->execute(array($userid));
    $row = $stmt->fetch();
    $counts = $stmt->rowCount();
    if ($counts > 0) {
        $id = $row['UserId'];*/
    $stmt = $con->prepare("UPDATE users SET RegStates = $regstates WHERE UserId = $id ");
    $stmt->execute();

    $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'member.php';
    header("location:$url");
} elseif ($do == 'new') {
    ?>
    <div class="container">
        <form action="signsubmit.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class=" col-md-6">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="Fname" class="form-control" required placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="Lname" class="form-control" required placeholder="Last Name">
                    </div>
                    <div class="form-group">
                        <label>User Name</label>
                        <input type="text" name="username" class="form-control" required placeholder="User Name">
                    </div>
                    <div class="form-group">
                        <label>Date of birth</label>
                        <input type="date" name="bdate" class="form-control" required placeholder="Date of Birth">
                    </div>
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" class="form-control" placeholder="Email" required name="email">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password" required name="password">
                    </div>
                    <div class="form-group">
                        <input type="checkbox"><span> Agree the terms and policy</span>
                    </div>
                </div>
                <div class=" col-md-6">
                    <div class="form-group">
                        <label>Mobile Number 1</label>
                        <input type="test" class="form-control" placeholder=" First Mobile Number" required name="Pmobile">
                    </div>
                    <div class="form-group">
                        <label>Mobile Number 2</label>
                        <input type="test" class="form-control" placeholder="Second Mobile Number" required name="Smobile">
                    </div>
                    <div class="form-group">
                        <label>Title or Designations</label>
                        <input type="text" name="Title or Designations" class="form-control" required placeholder="User Name">
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <input type="select" class="form-control" placeholder="Country" required name="Country">
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="select" class="form-control" placeholder="City" required name="City">
                    </div>
                    <div class="form-group">
                        <label>Photo</label>
                        <input type="file" required name="photo">

                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Register</button>
                </div>
            </div>
        </form>
    </div>
<?php
} else {
    $theMsg = "
    <div class='container'>
    <div class='alert alert-danger'>
    <h2>Sorry Can\'t Found This Page</h2>
    <p>You Will Be Redirect to Home Page</p>
    </div>
    </div>";
    redirectHome($theMsg, 'back');
}
include $tpl . 'footer.php';
ob_end_flush();
?>