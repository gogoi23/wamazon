#Author: Anand gogoi
#This code takes all the json files in Auction data and converts it into csv files.

from pathlib import Path
import json
import csv
from datetime import timedelta
import re

# get all the files in the directory and puts them in a array
jsonFiles = Path("AuctionData").glob('*') 

# counts what row the following for loop is on. Currently not used
jsonCounter = 0

#opens up the csv file for the ItemCategory table. 
csvConversionFileItemCategory = open('csvConversionFileItemCategory.csv', 'w') 
#this writes data to the item table
csv_writer_itemCategory = csv.writer(csvConversionFileItemCategory)

#stop here stop herer stop here stop here stop here stop here stp here stop here 



#opens up the csv file for the Item table. 
csvConversionFileItem = open('csvConversionFileItem.csv', 'w') 
#this writes data to the item table
csv_writer_item = csv.writer(csvConversionFileItem)

#opens up the csv file for the Category table. 
csvConversionFileCategory = open('csvConversionFileCategory.csv', 'w') 
#this writes data to the Category table
csv_writer_Category = csv.writer(csvConversionFileCategory)
#The array of categories
categories = []
categoriesAndIDs = []

#opens up the csv file for the Bidder table.
csvConversionFileBidder = open('csvConversionFileBidder.csv', 'w') 
#this writes data to the Bidder table
csv_writer_Bidder = csv.writer(csvConversionFileBidder)
#this is the array of bidderIDs
bidderIDs = []
Bidders = []

#opens up the csv file for the Bid table.
csvConversionFileBid = open('csvConversionFileBid.csv', 'w') 
#this writes data to the Bidder table
csv_writer_Bid = csv.writer(csvConversionFileBid)
#counts how many bids are there.
BidCounter = 0
BidObjects = []

#opens up the csv file for the Seller table.
csvConversionFileSeller = open('csvConversionFileSeller.csv','w')
#this writes data seller file
csv_writer_Seller = csv.writer(csvConversionFileSeller)
#array of all the SellerIDs
SellerIDs = []
Sellers = []


#These are the attributes of the Seller
SellerAttributes = [
    'UserID',
    'Rating'
]
csv_writer_Seller.writerow(SellerAttributes)

#These are the attributes of the Bid table
bidAttributes = [
    'BidID',
    'ItemID',
    'BidderID',
    'Time',
    'Amount'
]
csv_writer_Bid.writerow(bidAttributes)



#These are the attributes of the bidder table. This will be the first row of the csv bidder file
bidderAttributes = [
    'UserID',
    'Location',
    'Country',
    'Rating'
]
csv_writer_Bidder.writerow(bidderAttributes)

itemCategoryAttributes = [
    'ItemID',
    'CategoryID'
]
csv_writer_itemCategory.writerow(itemCategoryAttributes)

#These are the attributes of the category table. This will be the first row of the csv category file
categoryAttributes = [
    'Description',
    'CategoryID'
]
csv_writer_Category.writerow(categoryAttributes)

#These are the attributes of the Item table. They will be the first row of the csv Item file. 
itemAttributes = [
    "ItemId",
    "Name",
    "Currently",
    "BuyPrice",
    "FirstBid",
    "NumberOfBids",
    "Location",
    "Country",
    "Started",
    "Ends",
    "SellerID",
    "Description"
]

 #This writes the array list above to the first row of the csv item file.
csv_writer_item.writerow(itemAttributes)


#this checks if a key exists in a directory. If it does not it returns -1
def checkForKey(item,key,default_value):
    if key in item:
        return item[key]
    else:
        return -1

def formatMoney(valueIn):
	if valueIn == None or len(valueIn) == 0:
		return valueIn
	return re.sub(r'[^\d.]', '', valueIn)

def formatDateTime(valueIn):
	MONTHS = {'Jan':'01','Feb':'02','Mar':'03','Apr':'04','May':'05','Jun':'06',\
	'Jul':'07','Aug':'08','Sep':'09','Oct':'10','Nov':'11','Dec':'12'}
	valueIn = valueIn.strip().split(' ')
	tok = valueIn[0].split('-')
	mnt = tok[0]
	if mnt in MONTHS:
		mnt = MONTHS[mnt]
	date = '20' + tok[2] + '-'
	date += mnt + '-' + tok[1]
	return date + ' ' + valueIn[1]

# iterates through the directory.
for file in jsonFiles:
    print( "parsing " + str(file))
    
    #this variable is to tell whether there is used to determine if the file being parsed is a json file. 
    #if it is not a json file it does not get added to the csv files. 
    isJson = False
    
    #opens the file and converts it to a directory who's data can be accessed. 
    with open(file) as json_file:
        # checks if the file is a json file.
        if "json" in str(file):
            itemFile = json.load(json_file)
            isJson = True
    
    if isJson:
        #This returns an array of all the items in the json file 
        itemData = itemFile['Items']   

        # iterates through ever item. 
        # One row of the csv file contains one item. 
        for item in itemData:    
            #this is an array of bids for an item. 
            Bids = checkForKey(item,'Bids',-1)

            #if the bid is not null
            if Bids != None and Bids !=-1:
                #this goes through every bid in the item 
                for Bid in Bids:
                    # this is the user of the bid 
                    UserID = Bid['Bid']['Bidder']['UserID']
                    #checks if the bidder is all ready in the table
                    if UserID not in bidderIDs:
                        bidderIDs.append(UserID)
                
                        #these values get added to a single bidder array       
                        BidderAttributeValues = [
                            UserID,
                            checkForKey(Bid['Bid']['Bidder'],'Location',-1),
                            checkForKey(Bid['Bid']['Bidder'] ,'Country',-1),
                            Bid['Bid']['Bidder']['Rating']
                        ]
                        #these values get added as one row to the bidder table.
                        Bidders.append(BidderAttributeValues)
                        csv_writer_Bidder.writerow(BidderAttributeValues)

                    #these values get added to the Bid table    	
                    bidAttributesValues = [
                        BidCounter,
                        checkForKey(item,'ItemID',-1),
                        UserID,
                        formatDateTime(Bid['Bid']['Time']),
                        Bid['Bid']['Amount'] [1: ]
                    ]
                    csv_writer_Bid.writerow(bidAttributesValues)
                    BidObjects.append(bidAttributesValues)

                    
                        
                    BidCounter = BidCounter + 1
                    
                    


            # These are all the values get added to one row of the item csv file. 
            # the method checkForKey makes sure the value is not null from the json file
            #if it is the value will equal -1
            ItemID = checkForKey(item,'ItemID',-1)
            Name = checkForKey(item,'Name',-1)
            
            Currently = checkForKey(item,'Currently',-1)[1:]
            Buy_Price = str(checkForKey(item,'Buy_Price',-1))[1:]
            FirstBid = checkForKey(item,'First_Bid',-1)[1:]
            
            #print('FirstBId = ' + str(FirstBid))
            #print('Bp = ' + str(Buy_Price))
            #print('Currently = ' +  str(Currently))
            
            NumberOfBids = checkForKey(item,'Number_of_Bids',-1)
            Location = checkForKey(item,"Location",-1)
            Country = checkForKey(item,"Country",-1)
            Started = formatDateTime(checkForKey(item,"Started",-1))
            Ends = formatDateTime(checkForKey(item,"Ends",-1))
            Description = checkForKey(item,"Description", -1)

            #This code does the same thing as checkForKey for the value SellerID
            #I do not use the method here because it accessing the SellerID is different 
            #then the other Values
            SellerID = -1
            if 'Seller' in item:
                if 'UserID' in item['Seller']:
                    SellerID = item['Seller']['UserID']
                    if SellerID not in SellerIDs:
                        SellerAttributeValues = [
                            SellerID,
                            item['Seller']['Rating']
                        ]
                        Sellers.append(SellerAttributeValues)
                        csv_writer_Seller.writerow(SellerAttributeValues)
                        SellerIDs.append(SellerID) 
            if Ends == -1:
                print(Item.ID)
            #This puts all the vlaues above in an array.
            itemAttributeValues = [
                ItemID,
                Name,
                Currently,
                Buy_Price,
                FirstBid,
                NumberOfBids,
                Location,
                Country,
                Started,
                Ends,
                SellerID,
                Description 
            ]
            #writes the array above into the itemCSV file. 
            csv_writer_item.writerow(itemAttributeValues)

            #this code is adds categories to the itemCategory tables
            itemCategories = checkForKey(item,"Category",-1)
            if itemCategories != -1:
                for category in itemCategories:
                    if category not in categories:
                        categories.append(category)
                
    # if it is not a json file it s an error message 
    else :
        print(str(file) + " is not a json file. This data will not be added to the csv file.")
    jsonCounter = jsonCounter + 1

# counts how many categories there are. This will be used for the category ids. 
categoryCount = 0
for category in categories:
    # this will be added as a row in the category table. 
    categoryAttributeValues = [
        category,
        categoryCount
    ]
    
    categoriesAndIDs.append(categoryAttributeValues)

    #writes the array above into the Category CSV file. 
    csv_writer_Category.writerow(categoryAttributeValues)

    categoryCount = categoryCount + 1

jsonFiles2 = Path("AuctionData").glob('*') 
for file in jsonFiles2:
    
    #opens the file and converts it to a directory who's data can be accessed. 
    with open(file) as json_file:
        # checks if the file is a json file.
        if "json" in str(file):
            itemFile = json.load(json_file)
            isJson = True
    
    if isJson:
        #This returns an array of all the items in the json file 
        itemData2 = itemFile['Items']   
    
        # iterates through ever item. 
        # One row of the csv file contains one item. 
        for item in itemData2:    
            for category in item['Category']:
                for categoryMatch in categoriesAndIDs:
                    
                    
                    if category == categoryMatch[0]:
                        
                        itemCategoryAttributeValues = [
                            item['ItemID'],
                            categoryMatch[1]
                        ]
                        csv_writer_itemCategory.writerow(itemCategoryAttributeValues)  
             

#opens up the csv file for the Seller table.
csvConversionFileUser = open('csvConversionFileUser.csv','w')
#this writes data seller file
csv_writer_User = csv.writer(csvConversionFileUser)
#array of all the SellerIDs
UserIDs = []
UserAttributes = [
    'UserID',
    'Location',
    'Country',
    'Rating'
]
csv_writer_User.writerow(UserAttributes)

 
for Bidder in Bidders:
	UserIDs.append(Bidder[0])
	csv_writer_User.writerow(Bidder)

for seller in Sellers:
	if seller[0] not in UserIDs:
		NewUserValues = [
			seller[0],
			"Unknown",
			"Unknown",
			seller[1]
		]
		csv_writer_User.writerow(seller)	
	
	

csvConversionFileItem.close()
csvConversionFileCategory.close()



#Select Count(*) from Items where ItemID in (Select ItemID from ItemCategory Group by ItemID from ItemCategory Group by ItemID Having count(*) > 3); and Items.Ends > '2001-12-01 00:00:01' 




