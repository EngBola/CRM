
<?php
   $conn = mysqli_connect("localhost", "root", "", "so");
   if($conn == false){
	   die(mysqli_connect_error());
   }
   $select_sql = "SELECT * FROM users";
   $result = mysqli_query($conn, $select_sql);
   if($result->num_rows > 0){
   for($i=0;$i<$result->num_rows;$i++){
	   $row = $result->fetch_assoc();
	   echo "student id : " . $row['UserId'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
			 "student name : " . $row['UserName'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
			 "student email : " . $row['Email'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
			 "student phone : " . $row['FullName'] . "<br/>";
	 }
	}
	else{
		echo "there is no data in the students table yet";
	}	
?>
