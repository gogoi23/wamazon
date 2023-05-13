qry="
drop database Auction_Data_Base;
CREATE DATABASE Auction_Data_Base;
USE Auction_Data_Base;
SET GLOBAL local_infile=1;
Create table Items(
ItemID int,
Name varchar(255),
Currently Double,
BuyPrice Double,
FirstBid Double,
NumberOfBids int,
Location varchar(255),
Country varchar(255),
Started DATETIME,
Ends DATETIME,
SellerID varchar(255),
Description text);
	
load data local infile '~/Desktop/finalproject/csvConversionFileItem.csv'
into table Items
fields terminated by ','
enclosed by '\"'
lines terminated by '\r\n'
ignore 1 rows;

Create table Category(
Description varchar(1000),
CategoryID int
);
    	
load data local infile '~/Desktop/finalproject/csvConversionFileCategory.csv'
into table Category
fields terminated by ','
enclosed by '\"'
lines terminated by '\r\n'
ignore 1 rows;

create table Users(
userID varchar(255),
location varchar(255),
country varchar(255),
rating int
);

load data local infile '~/Desktop/finalproject/csvConversionFileUser.csv'
into table Users
fields terminated by ','
enclosed by '\"'
lines terminated by '\n'
ignore 1 rows;





create table ItemCategory (
ItemID int,
CategoryID int
);
load data local infile '~/Desktop/finalproject/csvConversionFileItemCategory.csv'
into table ItemCategory
fields terminated by ','
enclosed by '\"'
lines terminated by '\n'
ignore 1 rows;



show tables;"
python3 parser.py
/usr/bin/mysql mysql -u root -p --local_infile << eof
$qry
eof
