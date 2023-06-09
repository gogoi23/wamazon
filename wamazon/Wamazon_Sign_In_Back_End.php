<!-- Author: Anand Gogoi -->
<!-- This is the backend page that handles new users trying to sign up.  -->

<html>
<title>
Wamazon Home Page 
</title>

<body>


<?php
	// credentials for loggin into the database from a remote server
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
		//This is an sql query to get the user from the database
		$sqlquery = "Select * from Users where UserID = '" . $_POST['User_Name'] . "'";
		
		//this is the execution of the query
		$result = mysqli_query($conn,$sqlquery);
		
		//This pulls the results out of the query. 
		$users = mysqli_fetch_all($result,MYSQLI_ASSOC);
		
		//if the usernames all ready exits it prompts the user to back and select another user name. 
		if (count($users) >= 1){
			echo "Sorry user name all ready exists. Please go back and submit another one.";
		}
		else{
			//if the user enters a blank password it prompts the user to go back and enter a different password
			if ($_POST['Password'] == ''){
				echo "Password must be at least one character. Please go back and enter another password";
			}
			else {
				//if password is not blank and the user name isn't taken then a new user is added to the database
				$hashed_password = password_hash($_POST['Password'],PASSWORD_DEFAULT);	
				$insertStatement = "Insert into Users values ( '" . $_POST['User_Name'] . "', '', '', 0, '" . $hashed_password ."')";  
				$result = mysqli_query($conn,$insertStatement);
				$resultdisplay = mysqli_fetch_all($result,MYSQLI_ASSOC);
				echo "User added.";
				echo '<a href="Wamazon_Sign_In.php">Click here to go to the login screen</a> ';
			}
		}
		

	}
	
	pass
?>



</body>
</html> 
