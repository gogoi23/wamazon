<!-- Author: Anand Gogoi -->
<!-- This page is the home page. Here the user can search for and also go the sell item and login page. -->

<html>
<title>
Wamazon Home Page 
</title>

<body>

<?php
	
	
	
	// credentials for loggin into the local data base
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
		
		//this is search bar form. The user can search by category,description, and seller id 
		echo '	<form action= "search_page.php" method = "post">
      				<input type="text" placeholder="Search for Item" name="search">
      				<button type="submit">Enter</i></button>
      				<p>Please select which fields you want to check.</p>
  				
				<!-- The following radio buttons let the user decide which field they want to search by -->
  				<input type="radio" id="age1" name="field"  checked="checked" value = "Description">
  				<label for="age1">Description</label><br>
  				
  				<input type="radio" id="age2" name= "field" value = "SellerID">
  				<label for="age2">Seller</label><br>  
  				
  				<input type="radio" id="age3" name="field" value = "Category">
  				<label for="age3">Category</label><br>
    			</form>';
    		echo '<br>';
		
		
		//this is a link to the selling an item 
    		echo '<a href="sellItemFrontEnd.php">Click here to go to sell an item</a> ';
    		echo '<br>';
		
		//if the user is not logged it notifies the user and displays a link for them to log in
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
			
			//if the user is logged the code below displays a text box for them to change their password 
			if (isset($_COOKIE['username']) && !isset($_POST["User_Name"]) ){
				echo '
					<br><h6> You can change your password here </h6>
					<form action = "changePassword.php" method = "post">
					New Password <input type="text" name="New_Password"><br>
					<input type = "hidden" name = "User_Name" value = ' . $_COOKIE['User_Name'] .' />
					<input type="submit" value = "Submit"> 
					</form>';
					
				
			}
			
			//if the user name is not found then the user is prompted to try to log in again. 
			else if (count($users) == 0 ){
				echo "Error: User Name not found. Please go back and try again.";
				
				//this sets a cookie to make all the user information null
				setcookie("username", null, time() + 86400 );
				setcookie("location", null, time() + 86400 );
				setcookie("country", null, time() + 86400 );
				setcookie("rating", null , time() + 86400 );
			}
			else { 
				
				//this checks if the user's password is correct 
				if ( password_verify( $_POST['Password'], $users[0]['password'])  || is_null($users[0]['password'])){
					//this sets a cookie to save all the users information
					setcookie("username", $_POST['User_Name'] , time() + 86400 );
					setcookie("location", $users[0]['location'] , time() + 86400 );
					setcookie("country", $users[0]['country'] , time() + 86400 );
					setcookie("rating", $users[0]['rating'] , time() + 86400 );
					
					
					//this notifies the user if they do not have a password 
					if ( is_null($users[0]['password']) ){
						echo "Warning: you have no password. Any one who knows your user name can log into your account.";
						echo " You can sumbit a password below.";
					}
					
					//this displays a text box and submit button where a user can submit a password 
					echo '
						<br><h6> You can change your password here </h6>
						<form action = "changePassword.php" method = "post">
						New Password <input type="text" name="New_Password"><br>
						<input type = "hidden" name = "User_Name" value = ' . $_COOKIE['username'] .' />
						<input type="submit" value = "Submit">
						</form>';	
				}
				
				
				//this notifies the user that their password is incorrect and they should try to login again 
				else {
					echo "This user exists but the password is invalid. Please go back and try again.";
					
					//this sets a cookie to make all the user information null
					setcookie("username", null, time() + 86400 );
					setcookie("location", null, time() + 86400 );
					setcookie("country", null, time() + 86400 );
					setcookie("rating", null , time() + 86400 );
					
				}
				
			}
		}
		
		//here the user can click to go the login screen 
		echo '<a href="Wamazon_Sign_In.php">Click here to go to the login screen</a> ';
		#if (isset($_COOKIE['username'])){
		
		#	echo '<br><br><br> Username:  '. $_COOKIE['username'];
		#}
		#header("Refresh:0; url=main.php");
	}
	
	
?>



</body>
</html> 
