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
		echo "<br><h3> Wamazon Home Screen </h3>";
		echo '	<form action= "search_page.php" method = "post">
      				<input type="text" placeholder="Search for Item" name="search">
      				<button type="submit">Enter</i></button>
      				<p>Please select which fields you want to check.</p>
  				
  				<input type="radio" id="age1" name="field"  checked="checked" value = "Description">
  				<label for="age1">Description</label><br>
  				
  				<input type="radio" id="age2" name= "field" value = "SellerID">
  				<label for="age2">Seller</label><br>  
  				
  				<input type="radio" id="age3" name="field" value = "Category">
  				<label for="age3">Category</label><br>
    			</form>';
    		echo '<br>';
    		echo '<a href="sellItemFrontEnd.php">Click here to go to sell an item</a> ';
    		echo '<br>';
		if ( !isset($_POST["User_Name"]) and !isset($_COOKIE['username']) ) {
		
			echo "You are not logged in. Click the link below to go to the sign up screen. <br>";
			
		}
		else{
			//This is an sql query to get the user from the database
			$sqlquery = "Select * from Users where UserID = '" . $_POST['User_Name'] . "'";
		
			//this is the execution of the query
			$result = mysqli_query($conn,$sqlquery);
		
			//This pulls the results out of the query. 
			$users = mysqli_fetch_all($result,MYSQLI_ASSOC);
			
			if (isset($_COOKIE['username']) && !isset($_POST["User_Name"]) ){
				echo '
					<br><h6> You can change your password here </h6>
					<form action = "changePassword.php" method = "post">
					New Password <input type="text" name="New_Password"><br>
					<input type = "hidden" name = "User_Name" value = ' . $_COOKIE['User_Name'] .' />
					<input type="submit" value = "Submit"> 
					</form>';
					
				
			}
			
			else if (count($users) == 0 ){
				echo "Error: User Name not found. Please go back and try again.";
				setcookie("username", null, time() + 86400 );
				setcookie("location", null, time() + 86400 );
				setcookie("country", null, time() + 86400 );
				setcookie("rating", null , time() + 86400 );
			}
			else { 
				//print_r($users[0]);
			
				if ( password_verify( $_POST['Password'], $users[0]['password'])  || is_null($users[0]['password'])){
					setcookie("username", $_POST['User_Name'] , time() + 86400 );
					setcookie("location", $users[0]['location'] , time() + 86400 );
					setcookie("country", $users[0]['country'] , time() + 86400 );
					setcookie("rating", $users[0]['rating'] , time() + 86400 );
					
					
					
					if ( is_null($users[0]['password']) ){
						echo "Warning: you have no password. Any one who knows your user name can log into your account.";
						echo " You can sumbit a password below.";
					}
					
					
					echo '
						<br><h6> You can change your password here </h6>
						<form action = "changePassword.php" method = "post">
						New Password <input type="text" name="New_Password"><br>
						<input type = "hidden" name = "User_Name" value = ' . $_COOKIE['username'] .' />
						<input type="submit" value = "Submit">
						</form>';	
				}
			
				else {
					echo "This user exists but the password is invalid. Please go back and try again.";
					setcookie("username", null, time() + 86400 );
					setcookie("location", null, time() + 86400 );
					setcookie("country", null, time() + 86400 );
					setcookie("rating", null , time() + 86400 );
					
				}
				
			}
		}
		echo '<a href="Wamazon_Sign_In.php">Click here to go to the login screen</a> ';
		#if (isset($_COOKIE['username'])){
		
		#	echo '<br><br><br> Username:  '. $_COOKIE['username'];
		#}
		#header("Refresh:0; url=main.php");
	}
	
	
?>



</body>
</html> 
