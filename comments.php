<?php
ob_start();
include 'includes/functions/checksession.php';
$pageTitle = "COMMENTS";


//Comments => [ MANAGE | EDITE | UPDATE | DELETE | STATES ]

$com = isset($_GET['com']) ? $_GET['com'] : 'Manage';

include "init.php";


// If the page is main pade
if ($com == 'Manage') {
    $query = '';
    if (isset($_GET['reg']) && $_GET['reg'] == 'Approve') {
        $query = 'AND Approve = 0';
        echo '<div class="text-center"> <span style = " color :Red;">Approve Items </span></div>';
    }
?>
    <div class="container">
        <h4 class='text-center'>Manage Comments !</h4>


        <div class="row mt-5">

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

        <?php
        $stmt = $con->prepare("SELECT comments.* , users.UserName , items_tb.itemName FROM comments
											INNER JOIN users ON users.UserId = comments.user_ID
											INNER JOIN items_tb ON items_tb.itemId = comments.item_ID ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();
        if ($count > 0) {
            echo '
			<div class="table-responsive ">
			<table class="table  table-hover table-bordered text-center maintable">
				<thead>
					<tr>
						<th scope="col">#</th>
						<th scope="col">Comment</th>
						<th scope="col">Status</th>
						<th scope="col">Member Name</th>
						<th scope="col">Item Name</th>
						<th scope="col">Action</th>

					</tr>
				</thead>
			';
            for ($i = 1; $i < $count + 1; $i++) {
                foreach ($rows as $row) {
        ?>

                    <tbody>
                        <tr>
                            <th scope="row"><?php echo $i++; ?></th>
                            <td><?php echo $row['comment']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['UserName']; ?></td>
                            <td><?php echo $row['itemName']; ?></td>

                            <td>
                                <div class="btn-group" role="group">
                                    <a href="?com=Edit&comId=<?php echo $row['comId']; ?>" class="btn btn-outline-success btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="?com=Delete&comId=<?php echo $row['comId']; ?>" type="button" class="btn btn-outline-danger confirm"><i class="fa fa-times"></i></a>
                                    <?php
                                    echo checkBox($row['status'], 'com=echeck&comId=', $row['comId']);
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
            <?php }
            }
            ?>
            </table>
    </div>
<?php
        } else {
            echo "
			<div class='container'>
            <div class='alert alert-danger text-center'>
            <h2>! Not Found Any Data</h2>
            </div>
            </div>";
        }
?>

</div>
<?php


} elseif ($com == 'Edit') {
    $comId = (isset($_GET['comId'])) && is_numeric($_GET['comId']) ? $_GET['comId'] : 0;
    $stmt = $con->prepare("SELECT * FROM comments WHERE comId = ? LIMIT 1");
    $stmt->execute(array($comId));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) {
?>
    <h4 class="text-center">Edit Items !</h4>'
    <div class="container ">
        <div class="cat-form rounded ">
            <?php
            if (isset($_GET['success']) && $_GET['success'] == 1) :
                echo  '<div class="alert alert-success" role="alert"><strong> Success Edit comment ! </strong></div>';
            endif; ?>
            <form class="form-group" action="?com=Update" method="POST">
                <!--///////////// START EDIT comment /////////////////////////////-->
                <input type="hidden" class="form-control" name="comid" value="<?php echo $_GET['comId']; ?>">
                <div class="form-group row">
                    <label for="com-name" class="col-sm-3 col-form-label">Comment</label>
                    <div class="col-sm-8">
                        <textarea type="text" class="form-control" id="com-name" name="comment" value="" placeholder="input your comment" required="required"><?php echo $row['comment']; ?></textarea>
                        <?php
                        if (isset($_GET['comerr']) && $_GET['comerr'] == 1) :
                            echo '<div class="alert alert-danger" role="alert">Item name Must Be More Than <strong>2 Characters</strong></div>';
                        elseif (isset($_GET['comerr']) && $_GET['comerr'] == 2) :
                            echo '<div class="alert alert-danger" role="alert">Item name Must Be More Than <strong>2 Characters</strong></div>';
                        endif;
                        ?>
                    </div>
                </div>
                <!--///////////// END EDIT comment //////////////////////////////////////-->
                <button type="submit" class="btn btn-outline-primary btn-block ">Save</button>
            </form>
        </div>
    </div>
<?php
    } else {
        $theMsg = "
            <div class='container'>
            <div class='alert alert-danger'>
            <h2>! Not Found Any Data For this comment'</h2>
            <p>You Will Be Redirect to Home Page</p>
            </div>
            </div>";
        redirectHome($theMsg, 'back');
    }
} elseif ($com == 'Update') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo 'Welcome to Update Items page';
        $comid = $_POST['comid'];
        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        $matchname = true;
        $error_redirect_url = "location: ?com=Edit&comId=$comid&";

        $comment = test_input($_POST['comment']);
        if (empty($comment)) {
            $matchname = false;
            $error_redirect_url .= "comerr=1&";
        } else {
            $error_redirect_url .= "comerr=0&";
        }
        if ($matchname == true) {

            $stmt = $con->prepare("UPDATE comments SET comment = '{$comment}' WHERE comId = $comid");
            $stmt->execute();

            header("location: comments.php?com=Edit&comId=$comid&success=1");
        } else {
            header($error_redirect_url);
        }
    } else {
        $testMsg = 'Sorry Can\'t Found This Page';
        redirectHome($testMsg, 3);
    }
} elseif ($com == 'Delete') {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $comid = (isset($_GET['comId'])) && is_numeric($_GET['comId']) ? $_GET['comId'] : 0;
        $stmt2 = $con->prepare("DELETE FROM comments WHERE comId = '{$comid}'");
        $stmt2->execute();

        $theMsg = "
            <div class='alert alert-success '>
            <h4>Successfull Delete comment</h4>
            <p>You Will Be Redirect to Home Page</p>
            </div>
            ";
        redirectHome($theMsg, 3);
    } else {

        $theMsg =
            "
			<div class='container'>
				<div class='alert alert-danger'>
					<h2>Sorry Can\'t Found This Page</h2>
					<p>You Will Be Redirect to Home Page</p>
				</div>
			</div>
			";
        redirectHome($theMsg, 'back');
    }
} elseif ($com == 'echeck') {
    $regstates = $_GET['reg'];
    $id = (isset($_GET['comId'])) && is_numeric($_GET['comId']) ? $_GET['comId'] : 0;
    $stmt = $con->prepare("UPDATE comments SET status = $regstates WHERE comId = $id ");
    $stmt->execute();
    $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'comments.php';
    header("location:$url");
} else {
    echo 'Error There\'s No page with this name';
}

include $tpl . 'footer.php';
ob_end_flush();
?>