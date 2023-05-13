<title>
Sell Page
</title>
<html>
<body>

<?php

	if ( is_numeric($_POST["Price"]) ){
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
		$catCheck = "Select CategoryID from Category where Description = '" . $_POST["Category"] ."'";
		$catCheck2 = mysqli_query($conn,$catCheck);
		$catCheck3 = mysqli_fetch_all($catCheck2,MYSQLI_ASSOC);
		//print_r($catCheck3[0]['CategoryID']);
		
		if (count($catCheck3) == 0){
			echo "Category does not exist";
		}
		else if ($_POST["Price"] > 0){
			//echo "This is valid number";
			if (DateTime::createFromFormat('Y-m-d H:i:s', $_POST['deadline'] ) !== false) {
  				
  				$idquery = "select max(ItemID) from Items";
  				$idquery2 = mysqli_query($conn,$idquery);
				$idquery3 = mysqli_fetch_all($idquery2,MYSQLI_ASSOC);
				$id = $idquery3[0]['max(ItemID)'] + 1;
				
				$sellQuery = "insert into Items values ( " . $id . ", '" . $_POST["Item_name"] . "', " .  $_POST['Price'] . ", " . $_POST['Price'] . ", " . $_POST['Price'] . ", 0 , '" . $_POST["Location"] . "', '" . $_POST["Country"] . "', '" . date('d-m-y h:i:s') .   "', '" . $_POST['deadline'] . "' , '" . $_COOKIE['username'] . "' , '" . $_POST["Description"] . "')";
				echo "<br>" . $sellQuery ;
				$sellquery2 = mysqli_query($conn,$sellQuery);
				$sellquery3 = mysqli_fetch_all($sellquery2,MYSQLI_ASSOC);
				
				
				$categoryItemQuery = "insert into ItemCategory values($id, " . $catCheck3[0]['CategoryID'] .")"; 			
				$categoryItemQuery2 = mysqli_query($conn,$categoryItemQuery);
				$categoryItemQuery3 = mysqli_fetch_all($categoryItemQuery2,MYSQLI_ASSOC);
				echo "Item placed for auction.";

			}
			else {
			 	echo '<script type="text/javascript">
       			window.onload = function () { alert("Error: Please enter the end date in this format. Y-m-d H:i:s"); } 
				</script>'; 
				header("Refresh:0; url=sellItemFrontEnd.php");
			}
		}
		else {
			echo '<script type="text/javascript">
       		window.onload = function () { alert("Error: Please enter a number greater than 0 for the price."); } 
			</script>'; 
			header("Refresh:0; url=sellItemFrontEnd.php");
		}
		
	}
	else{
		echo '<script type="text/javascript">
       	window.onload = function () { alert("Error: Please enter a number in the price tag."); } 
		</script>'; 
		header("Refresh:0; url=sellItemFrontEnd.php");
	}


?>


</body>
</html>
