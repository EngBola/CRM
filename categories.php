<?php
ob_start();
include 'includes/functions/checksession.php';
$pageTitle = "Categories";


//Categories => [ MANAGE | EDITE | UPDATE | ADD | INSERT | DELETE | STATES ]

$categories = isset($_GET['categories']) ? $_GET['categories'] : 'Manage';

include "init.php";
// If the page is main page

if ($categories == 'Manage') {
	// echo message('theMsg' , $_GET['theMsg'] );
	$sort = 'ASC';
	$sort_array = array('ASC', 'DESC');
	if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
		$sort = $_GET['sort'];
	}
	$stmt = $con->prepare("SELECT * FROM categories ORDER BY catOrder $sort");
	$stmt->execute();
	$rows = $stmt->fetchAll();
?>

	<div class="container">
		<div class="category">
			<h4><i class="far fa-bookmark"></i> Manage Categories Page !</h4>
		</div>
	</div>
	<div class="container">
		<div class="option d-flex justify-content-between align-items-center">
			<a class=" btn btn-outline-primary btn-sm" href="?categories=Add">Add New</a>
			<div class="view-option mr-2">
				Sort:
				<a class="<?php if ($sort == 'ASC') {
								echo 'active';
							} ?>" href="?sort=ASC"><i class="fa fa-arrow-down"></i></a>
				<a class="<?php if ($sort == 'DESC') {
								echo 'active';
							} ?>" href="?sort=DESC"><i class="fa fa-arrow-up"></i></a>
				View:
				<span class=" ml-0" data-view="full"> Full <i class="fa fa-indent "></i></span>
				<span class="" data-view="calssic"> Classic <i class="fas fa-bars"></i></span>
			</div>
		</div>
		<div class="categories mt-2">
			<div class="row">
				<?php
				foreach ($rows as $row) {
					echo '
								<div class="col-sm-6 mb-2">
										<div class="card">
												<div class="card-header" >
																<div class="ctrl d-flex justify-content-end align-items-center ">
																		<a href="?categories=Edit&catid=' . $row['catId'] . '" class="badge badge-pill badge-primary mr-1" ><i class="fa fa-edit"></i> Edit</a>
																		<a href="?categories=Delete&catid=' . $row['catId'] . '" class="badge badge-pill badge-danger mr-1 confirm"><i class="fa fa-times"></i> Delet</a>
																</div>
														<h5 class="card-title">' . $row['catName'] . '</h5>

														<div class="viewAll">
																<p class="card-text " >' . $row['catDes'] . '.</p>
																<div>
																		<span class="badge badge-pill badge-success">Visibilty : ' . allowStatus($row['catVisible']) . '</span>
																		<span class="badge badge-pill badge-secondary">Comment : ' . allowStatus($row['catComment'])  . '</span>
																		<span class="badge badge-pill badge-info">Ads : ' . allowStatus($row['catAds']) . '</span>
																</div>
														</div>
												</div>
										</div>
								</div>
						';
				}
				?>
			</div>
		</div>
	</div>
	<?php

} elseif ($categories == 'Edit') {
	$catid = (isset($_GET['catid'])) && is_numeric($_GET['catid']) ? $_GET['catid'] : 0;
	$statement = $con->prepare("SELECT * FROM categories WHERE catId = $catid");
	$statement->execute(array($catid));
	$row = $statement->fetch();
	$count = $statement->rowCount();
	if ($count > 0) {
		echo '<h4 class="text-center">Edit categories !</h4>';
	?>
		<div class="container ">
			<div class="cat-form rounded ">
				<?php

				if (isset($_GET['success']) && $_GET['success'] == 1) :
					echo  '<div class="alert alert-success" role="alert"><strong> Success Update Categories ! </strong></div>';
				endif;
				?>
				<form class="form-group" action="?categories=Update" method="POST">
					<!--///////////// START INPUT NEW CATEGORY NAME /////////////////////////////-->
					<input type="hidden" name="catid" value="<?php echo $row['catId']; ?>">
					<div class="form-group row">
						<label for="cat-name" class="col-sm-3 col-form-label">Category Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="cat-name" name="catname" value="<?php echo $row['catName']; ?>" required="required">
							<?php
							if (isset($_GET['cnerr']) && $_GET['cnerr'] == 1) :
								echo '<div class="alert alert-danger" role="alert">category name Must Be More Than <strong>2 Characters</strong></div>';
							elseif (isset($_GET['cnerr']) && $_GET['cnerr'] == 2) :
								echo '<div class="alert alert-danger" role="alert">category name Must Be Less Than <strong>100 Characters</strong></div>';
							elseif (isset($_GET['cnerr']) && $_GET['cnerr'] == 3) :
								echo '<div class="alert alert-danger" role="alert">category name Can\'t Be <strong>Empty</strong></div>';
							elseif (isset($_GET['cnerr']) && $_GET['cnerr'] == 4) :
								echo '<div class="alert alert-danger" role="alert">category name already <strong>Exist</strong></div>';
							endif;
							?>
						</div>
					</div>
					<!--///////////// END INPUT NEW CATEGORY NAME //////////////////////////////////////
				///////////// START INPUT NEW CATEGORY DESCRIPTION /////////////////////////////-->
					<div class="form-group row">
						<label for="cat-des" class="col-sm-3 col-form-label">Description</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="cat-des" name="des" value="<?php echo $row['catDes']; ?>">
							<?php
							if (isset($_GET['deserr']) && $_GET['deserr'] == 1) :
								echo '<div class="alert alert-danger" role="alert">The Description Must Be More Than <strong>4 Characters</strong></div>';
							endif;
							?>
						</div>
					</div>
					<!--///////////// END INPUT CATEGORY DESCRIPTION //////////////////////////////////////
						///////////// START INPUT CATEGORY ORDERING //////////////////////////////////////-->
					<div class="form-group row">
						<label for="cat-order" class="col-sm-3 col-form-label">Ordering</label>
						<div class="col-sm-8">
							<input class="form-control" id="cat-order" type="number" name="order" min="1" value="<?php echo $row['catOrder']; ?>">
							<?php
							if (isset($_GET['oerr']) && $_GET['oerr'] == 1) :
								echo '<div class="alert alert-danger" role="alert">Order Can\'t Be <strong>Empty</strong></div>';
							endif;
							?>
						</div>
					</div>
					<!--///////////// END INPUT CATEGORY ORDERING //////////////////////////////////////
						//////////////// START INPUT visibility  //////////////////////////////////////-->
					<div class="form-group row">
						<label for="cat-order" class="col-sm-3 col-form-label">Visibilty</label>
						<div class="col-sm-8">
							<div class="custom-control custom-radio">
								<input type="radio" id="vis-yes" name="visibile" class="custom-control-input" value="0" <?php if ($row['catVisible'] == 0) {
																															echo 'checked';
																														} ?>>
								<label class="custom-control-label" for="vis-yes">YES</label>
							</div>
							<div class="custom-control custom-radio">
								<input type="radio" id="vis-no" name="visibile" class="custom-control-input" value="1" <?php if ($row['catVisible'] == 1) {
																															echo 'checked';
																														} ?>>
								<label class="custom-control-label" for="vis-no">NO</label>
							</div>
						</div>
					</div>
					<!--//////////////////// END INPUT visibility //////////////////////////////////////
						//////////////// START INPUT Allow Comment  //////////////////////////////////////-->
					<div class="form-group row">
						<label for="cat-order" class="col-sm-3 col-form-label">Allow Comment </label>
						<div class="col-sm-8">
							<div class="custom-control custom-radio">
								<input type="radio" id="com-yes" name="comment" class="custom-control-input" value="0" <?php if ($row['catComment'] == 0) {
																															echo 'checked';
																														} ?>>
								<label class="custom-control-label" for="com-yes">YES</label>
							</div>
							<div class="custom-control custom-radio">
								<input type="radio" id="com-no" name="comment" class="custom-control-input" value="1" <?php if ($row['catComment'] == 1) {
																															echo 'checked';
																														} ?>>
								<label class="custom-control-label" for="com-no">NO</label>
							</div>
						</div>
					</div>
					<!--///////////// END INPUT Allow Comment //////////////////////////////////////
						////////////// START INPUT Allow Ads  //////////////////////////////////////-->
					<div class="form-group row">
						<label for="cat-order" class="col-sm-3 col-form-label">Allow Comment </label>
						<div class="col-sm-8">
							<div class="custom-control custom-radio">
								<input type="radio" id="ads-yes" name="ads" class="custom-control-input" value="0" <?php if ($row['catAds'] == 0) {
																														echo 'checked';
																													} ?>>
								<label class="custom-control-label" for="ads-yes">YES</label>
							</div>
							<div class="custom-control custom-radio">
								<input type="radio" id="ads-no" name="ads" class="custom-control-input" value="1" <?php if ($row['catAds'] == 1) {
																														echo 'checked';
																													} ?>>
								<label class="custom-control-label" for="ads-no">NO</label>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-outline-primary btn-block ">Save</button>
				</form>
			</div>
		</div>
	<?php
	} else {
		$testMsg = "
						<div class='container'>
								<div class='alert alert-danger'>
										<h2>Sorry can\'t browse this page directly</h2>
										<p>You Will Be Redirect to Home Page</p>
								</div>
						</div>
						";
		redirectHome($testMsg, 'back');
	}
} elseif ($categories == 'Update') {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$catid = $_POST['catid'];
		/////////////////////////////Start Validate form ////////////////////////////////////////////
		function test_input($data)
		{
			$data = trim($data);
			$data = stripslashes($data);
			// $data = htmlspecialchars($data);
			return $data;
		}
		$matchname = true;
		$matchdes = true;
		$error_redirect_url = "location: ?categories=Edit&catid=$catid&";

		$catname = test_input($_POST['catname']);
		if (strlen($catname) < 2) {
			$matchname = false;
			$error_redirect_url .= "cnerr=1&";
		} elseif (strlen($catname) > 100) {
			$matchname = false;
			$error_redirect_url .= "cnerr=2&";
		} elseif (empty($catname)) {
			$matchname = false;
			$error_redirect_url .= "cnerr=3&";
		} else {
			$error_redirect_url .= "cnerr=0&";
		}

		$des = test_input($_POST['des']);
		if (isset($_POST['des']) && strlen($des) < 4 && !empty($des)) {
			$matchdes = false;
			$error_redirect_url .= "deserr=1&";
		} else {
			$matchdes = true;
			$error_redirect_url .= "deserr=0&";
		}

		$order = $_POST['order'];
		if (isset($_POST['order']) && empty($order)) {
			$matchname = false;
			$error_redirect_url .= "orerr=1&";
		} else {
			$error_redirect_url .= "orerr=0&";
		}

		$visibile = $_POST['visibile'];
		$comment = $_POST['comment'];
		$ads = $_POST['ads'];
		/////////////////////////////End Validate form ////////////////////////////////////////////

		if ($matchname == true && $matchdes == true) {
			$stmt = $con->prepare("UPDATE  categories SET catName = ? , catDes = ? , catOrder = ? , catVisible = ? , catComment = ? , catAds = ?  WHERE catId = ? ");
			$stmt->execute(array($catname, $des, $order, $visibile, $comment, $ads, $catid));

			header("location: ?categories=Edit&catid=$catid&success=1?");
		} else {
			header($error_redirect_url);
		}
	} else {
		$testMsg = "
								<div class='container'>
										<div class='alert alert-danger'>
												<h2>Sorry can\'t browse this page directly</h2>
												<p>You Will Be Redirect to Home Page</p>
										</div>
								</div>
								";
		redirectHome($testMsg, 'back');
	}
} elseif ($categories == 'Add') {
	echo '<h4 class="text-center">Add New categories !</h4>';
	?>
	<div class="container ">
		<div class="cat-form rounded ">
			<?php
			if (isset($_GET['success']) && $_GET['success'] == 1) :
				echo  '<div class="alert alert-success" role="alert"><strong> Success Add New Categories ! </strong></div>';
			endif; ?>
			<form class="form-group" action="?categories=Insert" method="POST">
				<!--///////////// START INPUT NEW CATEGORY NAME /////////////////////////////-->
				<div class="form-group row">
					<label for="cat-name" class="col-sm-3 col-form-label">Category Name</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="cat-name" name="catname" value="" placeholder="Name of the Category" required="required">
						<?php
						if (isset($_GET['cnerr']) && $_GET['cnerr'] == 1) :
							echo '<div class="alert alert-danger" role="alert">category name Must Be More Than <strong>2 Characters</strong></div>';
						elseif (isset($_GET['cnerr']) && $_GET['cnerr'] == 2) :
							echo '<div class="alert alert-danger" role="alert">category name Must Be Less Than <strong>100 Characters</strong></div>';
						elseif (isset($_GET['cnerr']) && $_GET['cnerr'] == 3) :
							echo '<div class="alert alert-danger" role="alert">category name Can\'t Be <strong>Empty</strong></div>';
						elseif (isset($_GET['cnerr']) && $_GET['cnerr'] == 4) :
							echo '<div class="alert alert-danger" role="alert">category name already <strong>Exist</strong></div>';
						endif;
						?>
					</div>
				</div>
				<!--///////////// END INPUT NEW CATEGORY NAME //////////////////////////////////////
						///////////// START INPUT NEW CATEGORY DESCRIPTION /////////////////////////////-->
				<div class="form-group row">
					<label for="cat-des" class="col-sm-3 col-form-label">Description</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="cat-des" name="des" value="" placeholder="The description">
						<?php
						if (isset($_GET['deserr']) && $_GET['deserr'] == 1) :
							echo '<div class="alert alert-danger" role="alert">The Description Must Be More Than <strong>4 Characters</strong></div>';
						endif;
						?>
					</div>
				</div>
				<!--///////////// END INPUT CATEGORY DESCRIPTION //////////////////////////////////////
								///////////// START INPUT CATEGORY ORDERING //////////////////////////////////////-->
				<div class="form-group row">
					<label for="cat-order" class="col-sm-3 col-form-label">Ordering</label>
					<div class="col-sm-8">
						<input class="form-control" id="cat-order" type="number" name="order" min="1" value="0">
						<?php
						if (isset($_GET['oerr']) && $_GET['oerr'] == 1) :
							echo '<div class="alert alert-danger" role="alert">Order Can\'t Be <strong>Empty</strong></div>';
						endif;
						?>
					</div>
				</div>

				<!--///////////// END INPUT CATEGORY ORDERING //////////////////////////////////////
								//////////////// START INPUT visibility  //////////////////////////////////////-->
				<div class="form-group row">
					<label for="cat-order" class="col-sm-3 col-form-label">Visibilty</label>
					<div class="col-sm-8">
						<div class="custom-control custom-radio">
							<input type="radio" id="vis-yes" name="visibile" class="custom-control-input" value="0" checked>
							<label class="custom-control-label" for="vis-yes">YES</label>
						</div>
						<div class="custom-control custom-radio">
							<input type="radio" id="vis-no" name="visibile" class="custom-control-input" value="1">
							<label class="custom-control-label" for="vis-no">NO</label>
						</div>
					</div>
				</div>
				<!--//////////////////// END INPUT visibility //////////////////////////////////////
								//////////////// START INPUT Allow Comment  //////////////////////////////////////-->
				<div class="form-group row">
					<label for="cat-order" class="col-sm-3 col-form-label">Allow Comment </label>
					<div class="col-sm-8">
						<div class="custom-control custom-radio">
							<input type="radio" id="com-yes" name="comment" class="custom-control-input" value="0" checked>
							<label class="custom-control-label" for="com-yes">YES</label>
						</div>
						<div class="custom-control custom-radio">
							<input type="radio" id="com-no" name="comment" class="custom-control-input" value="1">
							<label class="custom-control-label" for="com-no">NO</label>
						</div>
					</div>
				</div>
				<!--///////////// END INPUT Allow Comment //////////////////////////////////////
								////////////// START INPUT Allow Ads  //////////////////////////////////////-->
				<div class="form-group row">
					<label for="cat-order" class="col-sm-3 col-form-label">Allow Comment </label>
					<div class="col-sm-8">
						<div class="custom-control custom-radio">
							<input type="radio" id="ads-yes" name="ads" class="custom-control-input" value="0" checked>
							<label class="custom-control-label" for="ads-yes">YES</label>
						</div>
						<div class="custom-control custom-radio">
							<input type="radio" id="ads-no" name="ads" class="custom-control-input" value="1">
							<label class="custom-control-label" for="ads-no">NO</label>
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-outline-primary btn-block ">Save</button>
			</form>
		</div>
	</div>
<?php

} elseif ($categories == 'Insert') {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		/////////////////////////////Start Validate form ////////////////////////////////////////////
		function test_input($data)
		{
			$data = trim($data);
			$data = stripslashes($data);
			// $data = htmlspecialchars($data);
			return $data;
		}
		$matchname = true;
		$matchdes = true;
		$error_redirect_url = "location: categories.php?categories=Add&";

		$catname = test_input($_POST['catname']);
		$check = checkItem('catName', 'categories', $catname);
		if ($check === 1) {
			$matchname = false;
			$error_redirect_url .= "cnerr=4&";
		} elseif (strlen($catname) < 2) {
			$matchname = false;
			$error_redirect_url .= "cnerr=1&";
		} elseif (strlen($catname) > 100) {
			$matchname = false;
			$error_redirect_url .= "cnerr=2&";
		} elseif (empty($catname)) {
			$matchname = false;
			$error_redirect_url .= "cnerr=3&";
		} else {
			$error_redirect_url .= "cnerr=0&";
		}

		$des = test_input($_POST['des']);
		if (isset($_POST['des']) && strlen($des) < 4 && !empty($des)) {
			$matchdes = false;
			$error_redirect_url .= "deserr=1&";
		} else {
			$matchdes = true;
			$error_redirect_url .= "deserr=0&";
		}

		$order = $_POST['order'];
		if (isset($_POST['order']) && empty($order)) {
			$matchname = false;
			$error_redirect_url .= "orerr=1&";
		} else {
			$error_redirect_url .= "orerr=0&";
		}
		$visibile = $_POST['visibile'];
		$comment = $_POST['comment'];
		$ads = $_POST['ads'];
		/////////////////////////////End Validate form ////////////////////////////////////////////

		if ($matchname == true && $matchdes == true) {

			$stmt = $con->prepare("INSERT INTO categories (catName , catDes , catOrder , catVisible ,catComment, catAds) VALUES ('{$catname}','{$des}','{$order}' , '{$visibile}' ,'{$comment}','{$ads}') ");
			$stmt->execute();

			header("location: categories.php?categories=Add&success=1?");
		} else {
			header($error_redirect_url);
		}
	} else {
		$testMsg = "
								<div class='container'>
										<div class='alert alert-danger'>
												<h2>Sorry can\'t browse this page directly</h2>
												<p>You Will Be Redirect to Home Page</p>
										</div>
								</div>
								";
		redirectHome($testMsg, 'back');
	}
} elseif ($categories == 'Delete') {
	////Go To UPDATE PROFILE PAGES//////////////////////////////

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		// }

		$catid = (isset($_GET['catid'])) && is_numeric($_GET['catid']) ? $_GET['catid'] : 0;

		$stmt2 = $con->prepare("DELETE FROM categories WHERE catId = '{$catid}'");
		$stmt2->execute();

		$theMsg = "
								<div class='alert alert-success '>
								<h4>Successfull Delete Category</h4>
								<p>You Will Be Redirect to Home Page</p>
								</div>
								";
		header("location: categories.php?theMsg=Delete");
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
	redirectHome($theMsg, 'categories.php');
}
include $tpl . 'footer.php';
ob_end_flush();
?>