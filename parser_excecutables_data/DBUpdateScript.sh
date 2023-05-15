#this adds primary and foreign keys to the tables in the database. 
#It also sets default values and makes certain elements in the tables not be able to be null
#it also creates triggers and indexes
qry="Alter Table Bid add primary key(BidID);
Alter Table Users add primary key(userID);
Alter Table Category add primary key(CategoryID);
Alter Table Items add primary key(ItemID);

alter table Bid  
add constraint BidToItem  
foreign key(ItemID) references Items(ItemID);

alter table Bid  
add constraint BidToBidder  
foreign key(BidderID) references Users(UserID);

alter table ItemCategory  
add constraint ItemCategory_To_Item  
foreign key(ItemID) references Items(ItemID);

alter table ItemCategory  
add constraint ItemCategory_To_Category
foreign key(CategoryID) references Category(CategoryID);

alter table Items 
add constraint Items_To_Sellers 
foreign key(SellerID) references Users(UserID);

Alter table Bid Modify BidID INT NOT NULL;
Alter table Bid Modify ItemID int NOT NULL ;
Alter table Bid Modify BidderID varchar(255) NOT NULL ;
Alter table Bid Modify Time datetime NOT NULL ;
Alter table Bid Modify Amount Double NOT NULL ;
Alter table Category Modify Description varchar(1000) NOT NULL ;
Alter table ItemCategory Modify ItemID int NOT NULL ;
Alter table ItemCategory Modify CategoryID int NOT NULL ;
Alter table Items Modify Name varchar(255) NOT NULL ;
Alter table Items Modify Currently double NOT NULL;
Alter table Items alter Currently set default 0.00;
Alter table Items Modify FirstBid double NOT NULL;
Alter table Items Alter FirstBid set default  0.00;
Alter table Items Modify NumberofBids int NOT NULL;
Alter table Items Alter NumberOfBids set default  0;
Alter table Items Modify country varchar(255) NOT NULL;
Alter table Items Modify started datetime NOT NULL;
Alter table Items Modify ends datetime NOT NULL;
Alter table Items Modify SellerID varchar(255) NOT NULL;
Alter table Items Modify Description text NOT NULL;

Update Users set rating = 0 where isnull(rating) = 1 ;
Alter table Users alter rating set default  0;
Alter table Users Modify rating int NOT NULL ;

Alter table Users Modify UserID varchar(255) NOT NULL;

delimiter //
Create Trigger Update_Item_After_New_Bid
After insert on Bid
FOR EACH ROW
Begin
set @currentAmount:= (Select Currently from Items where 
ItemID = new.ItemID);
   	 	set @Deadline := (Select Ends from Items where ItemID = 
new.ItemID);
   	 	if new.Amount > @currentAmount and new.Time < @Deadline 
then  
   			Update Items
     Set Items.NumberOfBids = Items.NumberOfBids + 
1,Items.Currently = new.Amount
    		    Where Items.ItemID = new.ItemID;
   	     else
   			delete from Bid where BidID = new.BidID;
   	 	End if;
    End;
//
create index ItemIDIndex on Items(ItemID);//
create index UserIDIndex on Users(UserID);//
create index CategoryIdIndex on Category(CategoryId);//
create index itemnameindex on Items(Name);//
"
/usr/bin/mysql mysql -u root -p --local_infile << eof
$qry
eof





    			 






