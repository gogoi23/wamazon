<html>
<title>
Wamazon Home Page 
</title>

<body>


<?php
	//displays the user name if they are logged in 
	echo $_POST["User_Name"];
	
	
	
	// credentials for loggin into the database on the local machine 
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
		
		$sqlquery = ""; // this query gets all the items that aren't expired
		$sqlqueryExpired = ""; // this query gets all the items that are expired
		
		//this sets the values above to search in the data base based on either the seller or description based on the value in field value of the post. 
		if ($_POST["field"] != "Category"){
			$sqlquery = "Select * from Items where " . $_POST["field"] . " like '%" . $_POST["search"] . "%' and ends > '" . date('d-m-y h:i:s') . "' order by currently";
			$sqlqueryExpired = "Select * from Items where " . $_POST["field"] . " like '%" . $_POST["search"] . "%' and ends < '" . date('d-m-y h:i:s') . "' order by currently";

		}
		
		//this sets the values above to search in the data base based on the category 
		else {
			$sqlquery = "Select Items.* from Items,ItemCategory,Category where Category.Description like '%" . $_POST["search"] . "%' and ends > '". date('d-m-y h:i:s') ."' and ItemCategory.ItemID = Items.ItemID and ItemCategory.CategoryId = Category.CategoryID order by currently";
			$sqlqueryExpired = "Select Items.* from Items,ItemCategory,Category where Category.Description like '%" . $_POST["search"] . "%' and ends < '" . date('d-m-y h:i:s') ."' and ItemCategory.ItemID = Items.ItemID and ItemCategory.CategoryId = Category.CategoryID order by currently";
			
			
		}
		
		// this stores the results of the two queries above in $resultArrayFirstHalf and $resultArraySecondHalf
		$results = mysqli_query($conn,$sqlquery);
		$resultArrayFirstHalf = mysqli_fetch_all($results,MYSQLI_ASSOC);
		$resultsSecondHalf = mysqli_query($conn,$sqlqueryExpired);
		$resultArraySecondHalf = mysqli_fetch_all($resultsSecondHalf,MYSQLI_ASSOC);
		
		//this is the entire array 
		$resultArray = array_merge($resultArrayFirstHalf, $resultArraySecondHalf);
		
		//it displays no results if no items show up 
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
