<!-- Author: Anand Gogoi -->
<!-- This is back end for a user selling an item -->
<title>
Sell Page
</title>
<html>
<body>

<?php
	//this checks if the user enters a number as the price
	if ( is_numeric($_POST["Price"]) ){
		//these are the credentials for logging into the data base 
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
		
		//this is gets the id of the category that the item will go into 
		$catCheck = "Select CategoryID from Category where Description = '" . $_POST["Category"] ."'";
		$catCheck2 = mysqli_query($conn,$catCheck);
		$catCheck3 = mysqli_fetch_all($catCheck2,MYSQLI_ASSOC);
		
		//this checks if the category exists
		if (count($catCheck3) == 0){
			echo "Category does not exist";
		}
		
		else if ($_POST["Price"] > 0){
			
			//this checks if the data is entered in the correct format
			if (DateTime::createFromFormat('Y-m-d H:i:s', $_POST['deadline'] ) !== false) {
  				
				//this sets the id of the item to be the number of items + 1
  				$idquery = "select max(ItemID) from Items";
  				$idquery2 = mysqli_query($conn,$idquery);
				$idquery3 = mysqli_fetch_all($idquery2,MYSQLI_ASSOC);
				$id = $idquery3[0]['max(ItemID)'] + 1;
				
				//this inserts the item into the item table
				$sellQuery = "insert into Items values ( " . $id . ", '" . $_POST["Item_name"] . "', " .  $_POST['Price'] . ", " . $_POST['Price'] . ", " . $_POST['Price'] . ", 0 , '" . $_POST["Location"] . "', '" . $_POST["Country"] . "', '" . date('d-m-y h:i:s') .   "', '" . $_POST['deadline'] . "' , '" . $_COOKIE['username'] . "' , '" . $_POST["Description"] . "')";
				echo "<br>" . $sellQuery ;
				$sellquery2 = mysqli_query($conn,$sellQuery);
				$sellquery3 = mysqli_fetch_all($sellquery2,MYSQLI_ASSOC);
				
				//this inserts a new item into the item category table. 
				$categoryItemQuery = "insert into ItemCategory values($id, " . $catCheck3[0]['CategoryID'] .")"; 			
				$categoryItemQuery2 = mysqli_query($conn,$categoryItemQuery);
				$categoryItemQuery3 = mysqli_fetch_all($categoryItemQuery2,MYSQLI_ASSOC);
				echo "Item placed for auction.";

			}
			
			//this prompts the user to enter the date in the correct format if they enter the data in an invalid format
			else {
			 	echo '<script type="text/javascript">
       			window.onload = function () { alert("Error: Please enter the end date in this format. Y-m-d H:i:s"); } 
				</script>'; 
				header("Refresh:0; url=sellItemFrontEnd.php");
			}
		}
		
		//this prompts the user to enter a number greater than 0 for the price if they enter negative price 
		else {
			echo '<script type="text/javascript">
       		window.onload = function () { alert("Error: Please enter a number greater than 0 for the price."); } 
			</script>'; 
			header("Refresh:0; url=sellItemFrontEnd.php");
		}
		
	}
	
	
	//this notifies the user to enter a number in the price field. 
	else{
		echo '<script type="text/javascript">
       	window.onload = function () { alert("Error: Please enter a number in the price tag."); } 
		</script>'; 
		header("Refresh:0; url=sellItemFrontEnd.php");
	}


?>


</body>
</html>
