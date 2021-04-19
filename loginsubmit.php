<?php
	// Chick session
	session_start();
	if(isset($_SESSION['UserName'])){
		header('Location: dashbord.php');
	}else {
		header('Location: index.php');
	}

include 'init.php';


	$username = $_POST['user'];
	$password = $_POST['pass'];
	$hashpass = sha1($password);
	$groupid='';

	


	// check if the user exist in database

	$stmt = $con->prepare("SELECT UserId, UserName, Passw , GroupId FROM users WHERE UserName = ? AND passw = ? AND RegStates = 1 LIMIT 1  ");
	$stmt ->execute(array($username, $hashpass));
	$row =$stmt->fetch();
	$count = $stmt -> rowCount();
	$groupid = $row['GroupId'];

	// if count > 0 this mean the database contain record about username

	if ($count > 0 && $groupid ==1 ){
		
		if(isset($_POST['remember']) && $_POST['remember'] == 'true')
			{
				setcookie('UserName', $username, time() + 180);
			}
		$_SESSION['UserName'] = $username; //Register session Name
		$_SESSION['ID'] = $row['UserId']; //Register session \ID	
		$_SESSION['States'] = $row['GroupId']; //Register session \GroupID	
		
				
		header("location: dashbord.php");
		
	}elseif ($count > 0 && $groupid ==2){
		
		if(isset($_POST['remember']) && $_POST['remember'] == 'true')
			{
				setcookie('UserName', $username, time() + 180);
			}
		$_SESSION['UserName'] = $username; //Register session name
		$_SESSION['ID'] = $row['UserId']; //Register session ID
		$_SESSION['States'] = $row['GroupId']; //Register session \GroupID	
				
		echo 'you are not admin';
		header("location: dashbord.php");
	}elseif ($count > 0 && $groupid == 0){	
		if(isset($_POST['remember']) && $_POST['remember'] == 'true')
			{
				setcookie('UserName', $username, time() + 180);
			}
		$_SESSION['UserName'] = $username; //Register session name
		$_SESSION['ID'] = $row['UserId']; //Register session ID
		$_SESSION['States'] = $row['GroupId']; //Register session \GroupID			
		echo 'you are not admin';
		header("location:  dashbord.php");
		
	}else{
		header("location: index.php?error=3");
	}	


		/*$select_sql = "SELECT password FROM user WHERE username = '{$user}'";
		$result = mysqli_query($conn, $select_sql);
		if($result->num_rows != 0){
			$row = $result->fetch_assoc();
			if($row['password'] == $password ) {
				if(isset($_POST['remember']) && $_POST['remember'] == 'true')
				{
					setcookie('user', $user, time() + 180);
				}
				session_start();
				$_SESSION['user'] = $user;
				header("location: index.php");
			}
			else{
				header("location: login.php?error=1");
			}	
		}
		else{
			header("location: login.php?error=2");
		}*/
		/*
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashpass = sha1($password);

    // check if the user exist in database

    $stmt = $con->prepare("SELECT UserName, Passw FROM users WHERE UserName = ? AND Passw = ? AND GroupId = 1 ");
    $stmt ->execute(array($username, $hashpass));
    $count = $stmt -> rowCount();

    // if count > 0 this mean the database contain record about username

    if ($count > 0 ){
        
        echo 'Welcome ' . $username;
    }
}*/
//<?php echo $_SERVER['PHP_SELF']
