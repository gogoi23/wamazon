Read me

Video Demo: https://youtu.be/WjC_WIhFBdU 

This is an apache based website that is coded in php. I ran it on a virtual machine that my school provided. It is replica of eBay. Users can sell and place bids on items. There is also a user log in system. All the data regarding the users' passwords and other data as well as the items can be found in a mysql data base stored in the virtual machine that is running the website. I got the data for the items in the Auction directory located in parser_excecutables_data.

Layout
The code files for setting up the data base are stored in parser_excecutables_data. The code for setting up the website is in wamazon. 


Setting up the Data base
In the auction directory are 39 json files that each contain an array of json objects. These arrays each contain about 499 elements and each element contains information about an object. Running script1.sh will place all these elements into a mysql data base that the website can pull info on the items from. Running DBUpdateScript.sh will add triggers, primary keys, etc. and make the data base more efficient. These files are located in parser_excecutables_data. 



Sign in /make an account
If you go to the sign in page then you can either sign in or make an account. If you sign in with the wrong credentials the website notifies you and goes to the main page. You can try to sign in again from the main page. If you have the right credentials then you are also brought to the main page. If you try to make an account with a username that is already taken or no password then the website notifies you of this.


Changing password
I added a feature that lets the user change their password on the website since none of the users had passwords. It can be anything as long it is not left blank. If it is left blank then the user is redirected back to the main screen. It does not work at the moment. Do not try to use it. I will redirect to the search page as if no parameter are entered. SIDE NOTE(All the item entries in the the json files have a seller id. Some of them also have bidder ids as well. This is where I get the data for user info from. )


Searching for items
You can search for an item based on its category, seller, and description and If you search for an item and no results come up then the website will say no results. If there are results it will show a list of items with a button that says place bid. Each item in the search page will have this.


Bidding on an Item
If you go to place a bid on an item and the item is expired the website will tell the user this and won’t let them place a bid. 99 percent of all items can not be bidded on because they are all expired as this database is from over 20 years ago. I did make a few items that can be bidded on made by the seller anand. There will be no option to place a bid on an item that has expired. If a user places a bid on an item and the bid is too cheap they are redirected to the page and asked to bid more. If the user enter a non numeric value then they are told to place the bid again with a numeric value.


Selling an item
The user can also place items up for sale on the main page. If the user clicks on the link that states Click here to go to sell an item then they will be taken to a page where they can enter information for the item they are about to sell. If they do not fill out the data or enter bad information such as setting the price below 0 or having an invalid date format they are told to do it again the item is not placed for sale.
  

