<!-- Author: Anand Gogoi -->
<!-- This is the page that lets the user change passwords  -->
<html>
<title>
Wamazon Home Page 
</title>

<body>

<H3> Changed password successfully </H3>

<?php
	// credentials for loggin into the local data base. 
	$servername = "localhost";
	$username = "root";
	$password = "cs434";
	$db = "Auction_Data_Base";
	
	//Connecting to the data base. 
	$conn = mysqli_connect($servername, $username, $password, $db);

	// Check connection
	if (!$conn) {
  		echo "Error: Could not connect to data base." . mysqli_connect_error();
	}
	else{
		//This is the password when it goes through a hashing algorithm.
		$hashed_password = password_hash($_POST['New_Password'],PASSWORD_DEFAULT);	
		
		//this inserts the new password into the data base
		$sql = "update Users set password = '". $hashed_password ."' where UserID = '" . $_COOKIE['username'] . "'";
		//echo $sql;
		$result = mysqli_query($conn,$sql);
		$resultdisplay = mysqli_fetch_all($result,MYSQLI_ASSOC);
		echo "Password changed worked.";

	}
	
	
?>



</body>
</html> 
