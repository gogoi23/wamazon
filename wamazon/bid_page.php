<!-- Author: Anand Gogoi -->
<!-- This it the bid page that allows the user to place a bid on an item.  -->

<html>
<title>
Bid page
</title>

<body>
<?php
	//credentials for logging in to the data base
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
	
	//this is a mysql query that takes data gets info on an item. 
	$ItemAttributeQuery = "";
	
	//this page can be accessed through post and get request so it is prepared for both 
	if (isset($_POST['ItemID'])){
		$ItemAttributeQuery = "Select * from Items where ItemID = " . $_POST['ItemID'];
	}
	if (isset($_GET['ItemID'])){
		$ItemAttributeQuery = "Select * from Items where ItemID = " . $_GET['ItemID'];
		
	}
	
	
	//this stores the item info in $ItemAttributeArray
	$ItemAttribute = mysqli_query($conn,$ItemAttributeQuery);
	$ItemAttributeArray = mysqli_fetch_all($ItemAttribute,MYSQLI_ASSOC);
	
	//this displays on the item name,id,price,seller and date the product went for sale. 
	echo '<a href="main.php">Main Page</a> <br>';
	echo "Product Name: " . $ItemAttributeArray[0]['Name'] . '<br>';
	echo "Item ID: " . $ItemAttributeArray[0]['ItemID'] . '<br>';
	echo "Current Price: " . $ItemAttributeArray[0]['Currently'] . '<br>';
	echo "Seller: " . $ItemAttributeArray[0]['SellerID'] . '<br>';
	echo 'The bidding started on ' . $ItemAttributeArray[0]['started'] . '<br>';
	
	//this gets all the categories that the item is a part of 
	$categoryQuery = "Select Description from Category,ItemCategory where ItemID =" . $ItemAttributeArray[0]['ItemID'] . " and ItemCategory.CategoryID = Category.CategoryID";
  	$categoryresults = mysqli_query($conn,$categoryQuery);
	$categoryQueryArray = mysqli_fetch_all($categoryresults,MYSQLI_ASSOC);
	
	//this displays all the categories that the item is in.
	echo "Category(s): ";
  	for ($i = 0; $i < count($categoryQueryArray); $i++){
  		echo $categoryQueryArray[$i]["Description"];
  		if ($i != count($categoryQueryArray) -1){
  			echo ", ";
 		}
  	}
	
	//this displays the number of bids, location,country,buy price, and first bid of the item. 
  	echo '<br>';
  	echo "Number of Bids: " . $ItemAttributeArray[0]['NumberofBids'] . '<br>';
	echo "Location: " . $ItemAttributeArray[0]['Location'] .'<br>';
  	echo "Country: " . $ItemAttributeArray[0]['country'] .'<br>';
  	echo "Buy Price: " . $ItemAttributeArray[0]['BuyPrice'] .'<br>';
  	echo "First Bid: " . $ItemAttributeArray[0]['FirstBid'] .'<br>';
	
	
	$deadline = new DateTime(date('d-m-y h:i:s'));// this is the current date. 
	$ends = new DateTime($ItemAttributeArray[0]['ends']);//this is the last date to place a bid on the item 
	
	//if the dead line to place bids has passed then the user can not place bids and the message below displays
	if ($deadline > $ends) {
	
    		echo 'The deadline to place bids on this item has passed';
    		
	}
	
	//if the dead line has not passed the user can place bids
	if ($deadline < $ends) {
   		
		//this displays the dead line and the item description
    		echo 'The deadline to place bids on this item is ' . $_POST['ends'];
    		echo "<br>Description: <br>";
    		echo $ItemAttributeArray[0]['Description'] . '<br>';
    		
		//if the user is logged in they can place a bid. When the user enters submit the page refreshes and excecutes the code at the 
		// bottom
		if (isset($_COOKIE["username"])){
    			//this code lets the user enter how much moneythey want to spend 
			echo '
				<br><br>Enter the amount you want to bid
				<form action = "bid_page.php" method = "post">
				<input type="text" placeholder = "Bid Amount" name="Bid_Amount">
				<input type = "hidden" name = "ItemID" value = "' . $_POST['ItemID'] .'" />
				<input type="submit" value = "Submit Bid">
				</form>';
    		}
		
		//if the user is not logged in then they can not place a bid
    		else{
    			echo "<br>You are not logged in <br>";
    			echo '<a href="Wamazon_Sign_In.php">Click here to go to the login screen</a>';
    		}
	}
	
	//after the user places a bid then page executes this code
	if (isset($_POST["Bid_Amount"])){
		//if the user places a numeric value then than the bid gets gets entered in the data base. 
		
		if (is_numeric($_POST["Bid_Amount"])){
			//this checks if the bid is higher than the current amount. If it is not then the user is prompted to re enter the 
			// the value
			if($_POST["Bid_Amount"] <  $ItemAttributeArray[0]['Currently'] ){
				echo '<script type="text/javascript">
       			window.onload = function () { alert("Please enter a bid higher than the current one."); } 
				</script>'; 
				echo "Error: Please enter a price higher than the current Bid.";
				
			}
			//this updates the item's number of bids and highest bids
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
		
		// if the bid amount is not a number then the user if prompted to enter a number instead. 
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
