<?php
ob_start();
include 'includes/functions/checksession.php';
$pageTitle = "ITEMS PAGE";


//ITEMS => [ MANAGE | EDITE | UPDATE | ADD | INSERT | DELETE | STATES ]

$item = isset($_GET['item']) ? $_GET['item'] : 'Manage';

include "init.php";


// If the page is main pade
if ($item == 'Manage') {
	$query = '';
	if (isset($_GET['reg']) && $_GET['reg'] == 'Approve') {
		$query = 'AND Approve = 0';
		echo '<div class="text-center"> <span style = " color :Red;">Approve Items </span></div>';
	}
?>
	<div class="container">
		<h4 class='text-center'>Manage Item !</h4>


		<div class="row mt-5">
			<div class="col-md-8 col-sm-3 mb-3">
				<a href='?item=Add' class="btn btn-primary mr-5 "><i class="fas fa-user-plus"></i> Add item</a>
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

		<?php
		$stmt = $con->prepare("SELECT items_tb.* , users.UserName , categories.catName FROM items_tb
											INNER JOIN users ON users.UserId = items_tb.user_ID
											INNER JOIN categories ON categories.CatId = items_tb.cat_ID ");
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
						<th scope="col">Item Name</th>
						<th scope="col">Category</th>
						<th scope="col">Description</th>
						<th scope="col">Price</th>
						<th scope="col">Country Made</th>
						<th scope="col">Add Date</th>
						<th scope="col">Last Update</th>
						<th scope="col">Status</th>
						<th scope="col">Member</th>
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
							<td><?php echo $row['itemName']; ?></td>
							<td><?php echo $row['catName']; ?></td>
							<td><?php echo $row['itemDes']; ?></td>
							<td><?php echo $row['currency'] . ' ' . $row['itemPrice']; ?></td>
							<td><?php echo $row['countryMade']; ?></td>
							<td><?php echo $row['addDate']; ?></td>
							<td><?php echo $row['LasDate']; ?></td>
							<td><?php echo $row['itemStatus']; ?></td>
							<td><?php echo $row['UserName']; ?></td>
							<td>
								<div class="btn-group" role="group">
									<a href="?item=Edit&itemId=<?php echo $row['itemId']; ?>" class="btn btn-outline-success btn-sm"><i class="fa fa-edit"></i></a>
									<a href="?item=Delete&itemId=<?php echo $row['itemId']; ?>" type="button" class="btn btn-outline-danger confirm"><i class="fa fa-times"></i></a>
									<?php
									echo checkBox($row['Approve'], 'item=echeck&itemId=', $row['itemId']);
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
} elseif ($item == 'Add') {
?>
	<h4 class="text-center">Add New Items !</h4>'
	<div class="container ">
		<div class="cat-form rounded ">
			<?php
			if (isset($_GET['success']) && $_GET['success'] == 1) :
				echo  '<div class="alert alert-success" role="alert"><strong> Success Add New Items ! </strong></div>';
			endif; ?>
			<form class="form-group" action="?item=Insert" method="POST">
				<!--///////////// START INPUT NEW ITEM NAME /////////////////////////////-->
				<div class="form-group row">
					<label for="item-name" class="col-sm-3 col-form-label">Item Name</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="item-name" name="itemname" value="" placeholder="Name of the Item" required="required">
						<?php
						if (isset($_GET['itnerr']) && $_GET['itnerr'] == 1) :
							echo '<div class="alert alert-danger" role="alert">Item name Must Be More Than <strong>2 Characters</strong></div>';
						elseif (isset($_GET['itnerr']) && $_GET['itnerr'] == 2) :
							echo '<div class="alert alert-danger" role="alert">Item name Must Be Less Than <strong>100 Characters</strong></div>';
						elseif (isset($_GET['itnerr']) && $_GET['itnerr'] == 3) :
							echo '<div class="alert alert-danger" role="alert">Item name Can\'t Be <strong>Empty</strong></div>';
						endif;
						?>
					</div>
				</div>
				<!--///////////// END INPUT NEW ITEM NAME //////////////////////////////////////-->
				<!--///////////// START INPUT ITEM DESCRIPTION /////////////////////////////-->
				<div class="form-group row">
					<label for="item-des" class="col-sm-3 col-form-label">Description</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="item-des" name="itemdes" value="" placeholder="The description" required="required">
						<?php
						if (isset($_GET['itdeserr']) && $_GET['itdeserr'] == 1) :
							echo '<div class="alert alert-danger" role="alert">The Description Must Be More Than <strong>4 Characters</strong></div>';
						elseif (isset($_GET['itdeserr']) && $_GET['itdeserr'] == 2) :
							echo '<div class="alert alert-danger" role="alert">Item Description Can\'t Be <strong>Empty</strong></div>';
						endif;
						?>
					</div>
				</div>
				<!--///////////// END INPUT CATEGORY DESCRIPTION //////////////////////////////////////-->
				<!--///////////// START INPUT ITEM Price /////////////////////////////-->
				<div class="form-group row">
					<label for="item-price" class="col-sm-3 col-form-label">Price</label>
					<div class="col-sm-2">
						<select name="itemcurr" required="required">
							<option value="EGP">EGP</option>
							<option value="$">USD</option>
							<option value="Euro">Euro</option>
						</select>
					</div>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="item-price" name="itemprice" value="" placeholder="Price of the Item" required="required">
						<?php
						if (isset($_GET['prerr']) && $_GET['prerr'] == 1) :
							echo '<div class="alert alert-danger" role="alert">The Price Must Be <strong>Number</strong> & Can\'t Be <strong>Empty</strong></div>';
						endif;
						?>
					</div>
				</div>
				<!--///////////// END INPUT Price DESCRIPTION //////////////////////////////////////-->
				<!--///////////// START INPUT ITEM Made Country /////////////////////////////-->
				<div class="form-group row">
					<label for="item-coun" class="col-sm-3 col-form-label">Country</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="item-coun" name="itemcoun" value="" placeholder="Country Made of the Item" required="required">
						<?php
						if (isset($_GET['coerr']) && $_GET['coerr'] == 1) :
							echo '<div class="alert alert-danger" role="alert">The Country Made Can\'t Be <strong>Empty</strong></div>';
						endif;
						?>
					</div>
				</div>
				<!--///////////// END INPUT ITEM Made Country //////////////////////////////////////-->
				<!--///////////// START SELECT Status /////////////////////////////-->
				<div class="form-group row">
					<label for="item-stat" class="col-sm-3 col-form-label">Status</label>
					<div class="col-sm-8">
						<select id="item-stat" name="itemstat" required="required">
							<option value="0">Select Statues</option>
							<option value="1">New</option>
							<option value="2">Like New</option>
							<option value="3"></option>
							<option value="4"></option>
						</select>
						<?php
						if (isset($_GET['sterr']) && $_GET['sterr'] == 1) :
							echo '<div class="alert alert-danger" role="alert">The Status Can\'t Be <strong>Empty</strong></div>';
						endif;
						?>
					</div>
				</div>
				<!--///////////// END SELECT Status //////////////////////////////////////-->
				<!--///////////// START SELECT Categories /////////////////////////////-->
				<div class="form-group row">
					<label for="item-cat" class="col-sm-3 col-form-label">Categories</label>
					<div class="col-sm-8">
						<select id="item-cat" name="itemcat" required="required">
							<option value="0">Select Category</option>
							<?php
							$cats = selectfrom('catId , catName', 'categories', '');
							foreach ($cats as $cat) {
								echo '<option value="' . $cat['catId'] . '">' . $cat['catName'] . '</option>';
							}
							?>
						</select>
						<?php
						if (isset($_GET['caterr']) && $_GET['caterr'] == 1) :
							echo '<div class="alert alert-danger" role="alert">The categories Can\'t Be <strong>Empty</strong></div>';
						endif;
						?>
					</div>
				</div>
				<!--///////////// END SELECT Categories //////////////////////////////////////-->
				<!--///////////// START SELECT MEMBERS /////////////////////////////-->


				<div class="form-group row">
					<label for="item-mem" class="col-sm-3 col-form-label">Member</label>
					<div class="col-sm-8">
						<select id="item-mem" name="itemmem" required="required">
							<option value="0">Select Member</option>
							<?php
							//$ask = 'WHERE GroupId =' . $_SESSION['States'];
							$ask = '';
							$rows = selectfrom('UserId , UserName', 'users', $ask);
							foreach ($rows as $row) {
								echo '<option value="' . sha1($row['UserId']) . '">' . $row['UserName'] . '</option>';
							}
							?>
						</select>
						<?php
						if (isset($_GET['memerr']) && $_GET['memerr'] == 1) :
							echo '<div class="alert alert-danger" role="alert">Member Can\'t Be <strong>Empty</strong></div>';
						endif;
						?>
					</div>
				</div>
				<!--///////////// END SELECT MEMBERS //////////////////////////////////////-->
				<button type="submit" class="btn btn-outline-primary btn-block ">Add Item</button>
			</form>
		</div>
	</div>
	<?php
} elseif ($item == 'Insert') {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		echo 'Welcome to Insert New Items page';
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
		$error_redirect_url = "location: items.php?item=Add&";

		$itemname = test_input($_POST['itemname']);
		if (strlen($itemname) < 4) {
			$matchname = false;
			$error_redirect_url .= "itnerr=1&";
		} elseif (strlen($itemname) > 20) {
			$matchname = false;
			$error_redirect_url .= "itnerr=2&";
		} elseif (empty($itemname)) {
			$matchname = false;
			$error_redirect_url .= "itnerr=3&";
		} else {
			$error_redirect_url .= "itnerr=0&";
		}

		$itemdes = test_input($_POST['itemdes']);
		if (strlen($itemdes) < 4) {
			$matchname = false;
			$error_redirect_url .= "itdeserr=1&";
		} elseif (empty($itemdes)) {
			$matchname = false;
			$error_redirect_url .= "itdeserr=2&";
		} else {
			$error_redirect_url .= "itdeserr=0&";
		}

		$itemprice = test_input($_POST['itemprice']);
		if (!is_numeric($itemprice)) {
			$matchname = false;
			$error_redirect_url .= "prerr=1&";
		} else {
			$error_redirect_url .= "prerr=0&";
		}
		$itemcurr = $_POST['itemcurr'];
		$itemcoun = test_input($_POST['itemcoun']);
		if (empty($itemcoun)) {
			$matchname = false;
			$error_redirect_url .= "coerr=1&";
		} else {
			$error_redirect_url .= "coerr=0&";
		}
		$itemstat = test_input($_POST['itemstat']);
		if (empty($itemstat) && $itemstat == 0) {
			$matchname = false;
			$error_redirect_url .= "sterr=1&";
		} else {
			$error_redirect_url .= "sterr=0&";
		}
		$itemcat = test_input($_POST['itemcat']);
		if (empty($itemcat) && $itemcat == 0) {
			$matchname = false;
			$error_redirect_url .= "caterr=1&";
		} else {
			$error_redirect_url .= "caterr=0&";
		}
		$itemmem = test_input($_POST['itemmem']);
		if (empty($itemmem) && $itemmem == 0) {
			$matchname = false;
			$error_redirect_url .= "memerr=1&";
		} else {
			$itemmem = wizardhash('UserId', 'users', $itemmem);
			$error_redirect_url .= "memerr=0&";
		}

		/////////////////////////////End Validate form ////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////////

		if ($matchname == true) {

			$stmt = $con->prepare("INSERT INTO items_tb (itemName , itemDes , currency ,  itemPrice , countryMade , itemStatus, addDate, cat_ID , user_ID) VALUES ('{$itemname}','{$itemdes}', '{$itemcurr}' ,'{$itemprice}','{$itemcoun}', '{$itemstat}', now() ,'{$itemcat}' , '{$itemmem}')");
			$stmt->execute();

			header("location: items.php?item=Add&success=1");
		} else {
			header($error_redirect_url);
		}
	} else {
		$testMsg = 'Sorry Can\'t Found This Page';
		redirectHome($testMsg, 3);
	}
} elseif ($item == 'Edit') {
	$itemId = (isset($_GET['itemId'])) && is_numeric($_GET['itemId']) ? $_GET['itemId'] : 0;
	$stmt = $con->prepare("SELECT * FROM items_tb WHERE itemId = ? LIMIT 1");
	$stmt->execute(array($itemId));
	$row = $stmt->fetch();
	$count = $stmt->rowCount();
	if ($count > 0) {
	?>
		<h4 class="text-center">Edit Items !</h4>'
		<div class="container ">
			<div class="cat-form rounded ">
				<?php
				if (isset($_GET['success']) && $_GET['success'] == 1) :
					echo  '<div class="alert alert-success" role="alert"><strong> Success Edit Items ! </strong></div>';
				endif; ?>
				<form class="form-group" action="?item=Update" method="POST">
					<!--///////////// START INPUT EDIT ITEM NAME /////////////////////////////-->
					<input type="hidden" class="form-control" name="itemid" value="<?php echo $_GET['itemId']; ?>">
					<div class="form-group row">
						<label for="item-name" class="col-sm-3 col-form-label">Item Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="item-name" name="itemname" value="<?php echo $row['itemName']; ?>" placeholder="Name of the Item" required="required">
							<?php
							if (isset($_GET['itnerr']) && $_GET['itnerr'] == 1) :
								echo '<div class="alert alert-danger" role="alert">Item name Must Be More Than <strong>2 Characters</strong></div>';
							elseif (isset($_GET['itnerr']) && $_GET['itnerr'] == 2) :
								echo '<div class="alert alert-danger" role="alert">Item name Must Be Less Than <strong>100 Characters</strong></div>';
							elseif (isset($_GET['itnerr']) && $_GET['itnerr'] == 3) :
								echo '<div class="alert alert-danger" role="alert">Item name Can\'t Be <strong>Empty</strong></div>';
							endif;
							?>
						</div>
					</div>
					<!--///////////// END INPUT EDIT ITEM NAME //////////////////////////////////////-->
					<!--///////////// START INPUT EDIT DESCRIPTION /////////////////////////////-->
					<div class="form-group row">
						<label for="item-des" class="col-sm-3 col-form-label">Description</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="item-des" name="itemdes" value="<?php echo $row['itemDes']; ?>" placeholder="The description" required="required">
							<?php
							if (isset($_GET['itdeserr']) && $_GET['itdeserr'] == 1) :
								echo '<div class="alert alert-danger" role="alert">The Description Must Be More Than <strong>4 Characters</strong></div>';
							elseif (isset($_GET['itdeserr']) && $_GET['itdeserr'] == 2) :
								echo '<div class="alert alert-danger" role="alert">Item Description Can\'t Be <strong>Empty</strong></div>';
							endif;
							?>
						</div>
					</div>
					<!--///////////// END INPUT CATEGORY DESCRIPTION //////////////////////////////////////-->
					<!--///////////// START INPUT ITEM Price /////////////////////////////-->
					<div class="form-group row">
						<label for="item-price" class="col-sm-3 col-form-label">Price</label>
						<div class="col-sm-2">
							<select name="itemcurr" required="required">
								<option value="EGP" <?php if ($row['currency'] == 'EGP') {
														echo 'selected';
													} ?>>EGP</option>
								<option value="$" <?php if ($row['currency'] == '$') {
														echo 'selected';
													} ?>>USD</option>
								<option value="Euro" <?php if ($row['currency'] == 'Euro') {
															echo 'selected';
														} ?>>Euro</option>
							</select>
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="item-price" name="itemprice" value="<?php echo $row['itemPrice']; ?>" placeholder="Price of the Item" required="required">
							<?php
							if (isset($_GET['prerr']) && $_GET['prerr'] == 1) :
								echo '<div class="alert alert-danger" role="alert">The Price Must Be <strong>Number</strong> & Can\'t Be <strong>Empty</strong></div>';
							endif;
							?>
						</div>
					</div>
					<!--///////////// END INPUT Price DESCRIPTION //////////////////////////////////////-->
					<!--///////////// START INPUT ITEM Made Country /////////////////////////////-->
					<div class="form-group row">
						<label for="item-coun" class="col-sm-3 col-form-label">Country</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="item-coun" name="itemcoun" value="<?php echo $row['countryMade']; ?>" placeholder="Country Made of the Item" required="required">
							<?php
							if (isset($_GET['coerr']) && $_GET['coerr'] == 1) :
								echo '<div class="alert alert-danger" role="alert">The Country Made Can\'t Be <strong>Empty</strong></div>';
							endif;
							?>
						</div>
					</div>
					<!--///////////// END INPUT ITEM Made Country //////////////////////////////////////-->
					<!--///////////// START SELECT Status /////////////////////////////-->
					<div class="form-group row">
						<label for="item-stat" class="col-sm-3 col-form-label">Status</label>
						<div class="col-sm-8">
							<select id="item-stat" name="itemstat" value="<?php echo $row['itemStatus']; ?>" required="required">
								<option value="0">Select Statues</option>
								<option value="1" <?php if ($row['itemStatus'] == '1') {
														echo 'selected';
													} ?>>New</option>
								<option value="2" <?php if ($row['itemStatus'] == '2') {
														echo 'selected';
													} ?>>Like New</option>
								<option value="3" <?php if ($row['itemStatus'] == '3') {
														echo 'selected';
													} ?>></option>
								<option value="4" <?php if ($row['itemStatus'] == '4') {
														echo 'selected';
													} ?>></option>
							</select>
							<?php
							if (isset($_GET['sterr']) && $_GET['sterr'] == 1) :
								echo '<div class="alert alert-danger" role="alert">The Status Can\'t Be <strong>Empty</strong></div>';
							endif;
							?>
						</div>
					</div>
					<!--///////////// END SELECT Status //////////////////////////////////////-->
					<!--///////////// START SELECT Categories /////////////////////////////-->
					<div class="form-group row">
						<label for="item-cat" class="col-sm-3 col-form-label">Categories</label>
						<div class="col-sm-8">
							<select id="item-cat" name="itemcat" required="required">
								<?php
								/*
								$ask = 'WHERE catId = ' . $row['cat_ID'] ;
								$rows = selectfrom('catId , catName', 'categories', $ask);
								foreach ($rows as $cat) {
									echo '<option value="' . $cat['catId'] . '">' . $cat['catName'] . '</option>';
								}*/
								$ask = '';
								$rows = selectfrom('catId , catName', 'categories', $ask);
								foreach ($rows as $cat) {
									echo '<option value="' . $cat['catId'] . '"';
									if ($row['cat_ID'] == $cat['catId']) {
										echo 'selected';
									}
									echo '>' . $cat['catName'] . '</option>';
								}
								/*

								$cats = selectfrom('catId , catName', 'categories', 'WHERE UserId = '. $row['user_ID']);
								foreach ($cats as $cat) {
									echo '<option value="' . $cat['catId'] . '">' . $cat['catName'] . '</option>';
								}
								*/
								?>
							</select>
							<?php
							if (isset($_GET['caterr']) && $_GET['caterr'] == 1) :
								echo '<div class="alert alert-danger" role="alert">The categories Can\'t Be <strong>Empty</strong></div>';
							endif;
							?>
						</div>
					</div>
					<!--///////////// END SELECT Categories //////////////////////////////////////-->
					<!--///////////// START SELECT MEMBERS /////////////////////////////-->


					<div class="form-group row">
						<label for="item-mem" class="col-sm-3 col-form-label">Member</label>
						<div class="col-sm-8">
							<select id="item-mem" name="itemmem" required="required">
								<?php
								$ask = '';
								$rows = selectfrom('UserId , UserName', 'users', $ask);
								foreach ($rows as $user) {
									echo '<option value="' . sha1($user['UserId']) . '"';
									if ($row['user_ID'] == $user['UserId']) {
										echo 'selected';
									}
									echo '>' . $user['UserName'] . '</option>';
								}
								?>
							</select>
							<?php
							if (isset($_GET['memerr']) && $_GET['memerr'] == 1) :
								echo '<div class="alert alert-danger" role="alert">Member Can\'t Be <strong>Empty</strong></div>';
							endif;
							?>
						</div>
					</div>
					<!--///////////// END SELECT MEMBERS //////////////////////////////////////-->
					<button type="submit" class="btn btn-outline-primary btn-block ">Save</button>
				</form>
			</div>
		</div>
<?php
	} else {
		$theMsg = "
            <div class='container'>
            <div class='alert alert-danger'>
            <h2>! Not Found Any Data For this Item'</h2>
            <p>You Will Be Redirect to Home Page</p>
            </div>
            </div>";
		redirectHome($theMsg, 'back');
	}
} elseif ($item == 'Update') {
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		echo 'Welcome to Update Items page';
		$itemid = $_POST['itemid'];
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
		$error_redirect_url = "location: ?item=Edit&itemId=$itemid&";

		$itemname = test_input($_POST['itemname']);
		if (strlen($itemname) < 4) {
			$matchname = false;
			$error_redirect_url .= "itnerr=1&";
		} elseif (strlen($itemname) > 20) {
			$matchname = false;
			$error_redirect_url .= "itnerr=2&";
		} elseif (empty($itemname)) {
			$matchname = false;
			$error_redirect_url .= "itnerr=3&";
		} else {
			$error_redirect_url .= "itnerr=0&";
		}

		$itemdes = test_input($_POST['itemdes']);
		if (strlen($itemdes) < 4) {
			$matchname = false;
			$error_redirect_url .= "itdeserr=1&";
		} elseif (empty($itemdes)) {
			$matchname = false;
			$error_redirect_url .= "itdeserr=2&";
		} else {
			$error_redirect_url .= "itdeserr=0&";
		}

		$itemprice = test_input($_POST['itemprice']);
		if (!is_numeric($itemprice)) {
			$matchname = false;
			$error_redirect_url .= "prerr=1&";
		} else {
			$error_redirect_url .= "prerr=0&";
		}
		$itemcurr = $_POST['itemcurr'];
		$itemcoun = test_input($_POST['itemcoun']);
		if (empty($itemcoun)) {
			$matchname = false;
			$error_redirect_url .= "coerr=1&";
		} else {
			$error_redirect_url .= "coerr=0&";
		}
		$itemstat = test_input($_POST['itemstat']);
		if (empty($itemstat) && $itemstat == 0) {
			$matchname = false;
			$error_redirect_url .= "sterr=1&";
		} else {
			$error_redirect_url .= "sterr=0&";
		}
		$itemcat = test_input($_POST['itemcat']);
		if (empty($itemcat) && $itemcat == 0) {
			$matchname = false;
			$error_redirect_url .= "caterr=1&";
		} else {
			$error_redirect_url .= "caterr=0&";
		}
		$itemmem = test_input($_POST['itemmem']);
		if (empty($itemmem) && $itemmem == 0) {
			$matchname = false;
			$error_redirect_url .= "memerr=1&";
		} else {
			$itemmem = wizardhash('UserId', 'users', $itemmem);
			$error_redirect_url .= "memerr=0&";
		}

		/////////////////////////////End Validate form ////////////////////////////////////////////
		/////////////////////////////////////////////////////////////////////////////////////////////

		if ($matchname == true) {

			$stmt = $con->prepare("UPDATE items_tb SET itemName = '{$itemname}', itemDes = '{$itemdes}', currency = '{$itemcurr}',  itemPrice = '{$itemprice}', countryMade = '{$itemcoun}' , cat_ID = '{$itemcat}' , user_ID = '{$itemmem}'WHERE itemId = $itemid");
			$stmt->execute();

			header("location: items.php?item=Edit&itemId=$itemid&success=1");
		} else {
			header($error_redirect_url);
		}
	} else {
		$testMsg = 'Sorry Can\'t Found This Page';
		redirectHome($testMsg, 3);
	}
} elseif ($item == 'Delete') {
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		// }

		$itemid = (isset($_GET['itemId'])) && is_numeric($_GET['itemId']) ? $_GET['itemId'] : 0;

		$stmt2 = $con->prepare("DELETE FROM items_tb WHERE itemId = '{$itemid}'");
		$stmt2->execute();

		$theMsg = "
								<div class='alert alert-success '>
								<h4>Successfull Delete Item</h4>
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
} elseif ($item == 'echeck') {
	$regstates = $_GET['reg'];
	$id = (isset($_GET['itemId'])) && is_numeric($_GET['itemId']) ? $_GET['itemId'] : 0;
	$stmt = $con->prepare("UPDATE items_tb SET Approve = $regstates WHERE itemId = $id ");
	$stmt->execute();
	$url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'items.php';
	header("location:$url");
} else {
	$theMsg = "
	<div class='container'>
	<div class='alert alert-danger'>
	<h2>! Error There\'s No page with this name</h2>
	<p>You Will Be Redirect to Home Page</p>
	</div>
	</div>";
	redirectHome($theMsg, 'back');
}

include $tpl . 'footer.php';
ob_end_flush();
?>