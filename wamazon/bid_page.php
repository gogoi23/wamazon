<html>
<title>
Bid page
</title>

<body>
<?php
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
	$ItemAttributeQuery = "";
	if (isset($_POST['ItemID'])){
		$ItemAttributeQuery = "Select * from Items where ItemID = " . $_POST['ItemID'];
	}
	if (isset($_GET['ItemID'])){
		$ItemAttributeQuery = "Select * from Items where ItemID = " . $_GET['ItemID'];
		
	}
	
	
	
	$ItemAttribute = mysqli_query($conn,$ItemAttributeQuery);
	$ItemAttributeArray = mysqli_fetch_all($ItemAttribute,MYSQLI_ASSOC);
	
	
	echo '<a href="main.php">Main Page</a> <br>';
	echo "Product Name: " . $ItemAttributeArray[0]['Name'] . '<br>';
	echo "Item ID: " . $ItemAttributeArray[0]['ItemID'] . '<br>';
	echo "Current Price: " . $ItemAttributeArray[0]['Currently'] . '<br>';
	echo "Seller: " . $ItemAttributeArray[0]['SellerID'] . '<br>';
	echo 'The bidding started on ' . $ItemAttributeArray[0]['started'] . '<br>';
	
	$categoryQuery = "Select Description from Category,ItemCategory where ItemID =" . $ItemAttributeArray[0]['ItemID'] . " and ItemCategory.CategoryID = Category.CategoryID";
  	$categoryresults = mysqli_query($conn,$categoryQuery);
	$categoryQueryArray = mysqli_fetch_all($categoryresults,MYSQLI_ASSOC);
	
	echo "Category(s): ";
  	for ($i = 0; $i < count($categoryQueryArray); $i++){
  		echo $categoryQueryArray[$i]["Description"];
  		if ($i != count($categoryQueryArray) -1){
  			echo ", ";
 		}
  	}
  	echo '<br>';
  	echo "Number of Bids: " . $ItemAttributeArray[0]['NumberofBids'] . '<br>';
	echo "Location: " . $ItemAttributeArray[0]['Location'] .'<br>';
  	echo "Country: " . $ItemAttributeArray[0]['country'] .'<br>';
  	echo "Buy Price: " . $ItemAttributeArray[0]['BuyPrice'] .'<br>';
  	echo "First Bid: " . $ItemAttributeArray[0]['FirstBid'] .'<br>';
	
	$deadline = new DateTime(date('d-m-y h:i:s'));
	$ends = new DateTime($ItemAttributeArray[0]['ends']);
	
	
	if ($deadline > $ends) {
	
    		echo 'The deadline to place bids on this item has passed';
    		
	}

	if ($deadline < $ends) {
   			
    		echo 'The deadline to place bids on this item is ' . $_POST['ends'];
    		echo "<br>Description: <br>";
    		echo $ItemAttributeArray[0]['Description'] . '<br>';
    		if (isset($_COOKIE["username"])){
    			echo '
				<br><br>Enter the amount you want to bid
				<form action = "bid_page.php" method = "post">
				<input type="text" placeholder = "Bid Amount" name="Bid_Amount">
				<input type = "hidden" name = "ItemID" value = "' . $_POST['ItemID'] .'" />
				<input type="submit" value = "Submit Bid">
				</form>';
    		}
    		else{
    			echo "<br>You are not logged in <br>";
    			echo '<a href="Wamazon_Sign_In.php">Click here to go to the login screen</a>';
    		}
	}
	
	if (isset($_POST["Bid_Amount"])){
		if (is_numeric($_POST["Bid_Amount"])){
			if($_POST["Bid_Amount"] <  $ItemAttributeArray[0]['Currently'] ){
				echo '<script type="text/javascript">
       			window.onload = function () { alert("Please enter a bid higher than the current one."); } 
				</script>'; 
				echo "Error: Please enter a price higher than the current Bid.";
				
			}
			else{
				
				$numBidsQuery = "Select count(*) from Bid";
				$numBidsEx = mysqli_query($conn,$numBidsQuery);
				$actualNumDisplay = mysqli_fetch_all($numBidsEx,MYSQLI_ASSOC);
				
				$id = $actualNumDisplay[0]["count(*)"] + 10;
				$itemID = $ItemAttributeArray[0]['ItemID'];
				$time = date('d-m-y h:i:s');
				$BidAmount = $_POST["Bid_Amount"];
				
				$insertQuery = "Insert into Bid values ($id, $itemID, '" . $_COOKIE['username'] . "','$time', $BidAmount  )";
				#echo $insertQuery;
				$insertQuery2 = mysqli_query($conn,$insertQuery);
				$insertQuery3 = mysqli_fetch_all($insertQuery2,MYSQLI_ASSOC);
				
				echo '<script type="text/javascript">
       			window.onload = function () { alert("Bid has been placed"); } 
				</script>';
				
				header("Refresh:0; url=main.php");
				
				
				
				
			}
			
		}
		else{
			echo '<script type="text/javascript">
       			window.onload = function () { alert("Error: Please enter a number."); } 
			</script>'; 
			echo "please enter a numeric value.";
		}
	}
	
	



?>

</body>

</html>
