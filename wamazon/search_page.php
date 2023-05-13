<html>
<title>
Wamazon Home Page 
</title>

<body>


<?php
	echo $_POST["User_Name"];
	#echo $_POST["field"];
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
		
		$sqlquery = "";
		$sqlqueryExpired = "";
		if ($_POST["field"] != "Category"){
		// hello i am goin to take my typing test after I do this idea. I am watching family guy. I really like to go to the john and figure hout how to jump into the bands and make cool millie on these hoes to figure out how to jump in coup and dash in a rari.
			$sqlquery = "Select * from Items where " . $_POST["field"] . " like '%" . $_POST["search"] . "%' and ends > '" . date('d-m-y h:i:s') . "' order by currently";
			//echo $sqlquery;
			$sqlqueryExpired = "Select * from Items where " . $_POST["field"] . " like '%" . $_POST["search"] . "%' and ends < '" . date('d-m-y h:i:s') . "' order by currently";
			//echo $sqlqueryExpired;
		}
		else {
			$sqlquery = "Select Items.* from Items,ItemCategory,Category where Category.Description like '%" . $_POST["search"] . "%' and ends > '". date('d-m-y h:i:s') ."' and ItemCategory.ItemID = Items.ItemID and ItemCategory.CategoryId = Category.CategoryID order by currently";
			$sqlqueryExpired = "Select Items.* from Items,ItemCategory,Category where Category.Description like '%" . $_POST["search"] . "%' and ends < '" . date('d-m-y h:i:s') ."' and ItemCategory.ItemID = Items.ItemID and ItemCategory.CategoryId = Category.CategoryID order by currently";
			
			
		}
		
		
		$results = mysqli_query($conn,$sqlquery);
		$resultArrayFirstHalf = mysqli_fetch_all($results,MYSQLI_ASSOC);
		
		$resultsSecondHalf = mysqli_query($conn,$sqlqueryExpired);
		$resultArraySecondHalf = mysqli_fetch_all($resultsSecondHalf,MYSQLI_ASSOC);
		
		$resultArray = array_merge($resultArrayFirstHalf, $resultArraySecondHalf);
		
		if (count($resultArray) == 0){
			echo "No results";
				
				
		}
		else{
			echo '<br>';
			echo "<h3> Results</h3>";
			echo '<a href="main.php">Main Page</a> ';
			for ($x = 0; $x < count($resultArray); $x++) {
  				$categoryQuery = "Select Description from Category,ItemCategory where ItemID =" . $resultArray[$x]['ItemID'] . " and ItemCategory.CategoryID = Category.CategoryID";
  				$categoryresults = mysqli_query($conn,$categoryQuery);
				$categoryQueryArray = mysqli_fetch_all($categoryresults,MYSQLI_ASSOC);
  				//print_r($resultArray[$x]);
  				echo "<p>";
  				echo "Result Number: " . ($x + 1) . '<br>';
  				echo "Item name: " . $resultArray[$x]['Name'] . '<br>';
  				echo "Item ID: " . $resultArray[$x]['ItemID'] . '<br>';
  				echo "Owner: " . $resultArray[$x]['SellerID'] . ' <br>' ;
  				echo 'Current Price: ' . $resultArray[$x]['Currently'] . '<br>';
  				echo "The last date to place bids on this item is " . $resultArray[$x]['ends'] . '<br>';
  				echo 'The bidding started on ' . $resultArray[$x]['started'] . '<br>';
  				
				echo "Category(s): ";
  				for ($i = 0; $i < count($categoryQueryArray); $i++){
  					echo $categoryQueryArray[$i]["Description"];
  					if ($i != count($categoryQueryArray) -1){
  						echo ", ";
 					}
  				}
  				echo '<br>';
  				echo "Number of Bids: " . $resultArray[$x]['NumberofBids'] . '<br>';
  				echo "Location: " . $resultArray[$x]['Location'] .'<br>';
  				echo "Country: " . $resultArray[$x]['country'] .'<br>';
  				echo "Buy Price: " . $resultArray[$x]['BuyPrice'] .'<br>';
  				echo "First Bid: " . $resultArray[$x]['FirstBid'] .'<br>';
  				
  				echo "Description " . '<br>';
  				echo $resultArray[$x]['Description'] . '<br>';
  				
  				echo '<form action= "bid_page.php" method = "post">';
  				echo '<button type="submit">Place Bid</button>';
  				echo '<input type = "hidden" name = "Item_Name" value = "' . $resultArray[$x]['Name'] .'" />';
  				echo '<input type = "hidden" name = "SellerID" value = "' . $resultArray[$x]['SellerID'] .'" />';
  				echo '<input type = "hidden" name = "currently" value = "' . $resultArray[$x]['currently'] .'" />';
  				echo '<input type = "hidden" name = "ends" value = "' . $resultArray[$x]['ends'] .'" />';
  				echo '<input type = "hidden" name = "ItemID" value = "' . $resultArray[$x]['ItemID'] .'" />';
  				echo '</form>';
  				
  				echo '<p>';
  				if ($x == 99){
  					break;
  				}
			} 
		}
				
	}
	
?>



</body>
</html> 
