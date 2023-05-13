<html>
<title>
Sell Page
</title>

<body>

<H3> Item Sale Page </H3>

<?php
	if ( !isset($_COOKIE["username"]) ){
		echo "You need to be logged in to sell an item. " ;
		echo '<a href="Wamazon_Sign_In.php">Click here to go to the login screen</a> ';
	}
	else{
		echo '<form action = "sellItemBackEnd.php" method = "post">
		 enter the item name <input type= "text" name="Item_name"><br>
		
		
		 enter the country <input type = "text" name = "Country"> <br>
		 enter the deadline to place bids on this item.  <input type = "text" name = "deadline">format: Y-m-d H:i:s<br>
		 enter the location <input type = "text" name = "Location"> <br>
		 enter a description <input type = "text" name = "Description" ><br>
		 enter the price <input type= "text" name="Price"><br>
		 enter the Category <input type= "text" name="Category"><br>
		 <input type="submit" value = "Submit">
		</form>';
		
	}
?>



</body>
