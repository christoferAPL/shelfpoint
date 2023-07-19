# shelfpoint
Library mapping tool to guide customers to where there item is

Thank you for looking at my code. If you end up using it, please email me at christofer.zorn@ajaxlibrary.ca as I'd love to see where my code ends up!

The concept behind this is to provide "close enough" directions to a patron based on information entered by staff. The patron will search for something in the catalogue, and based on the item type, branch location and call number, the map will display the information to the patron.

The idea is that we're drawing (x,y) coordinates on a map and then giving arrow directions to be drawn afterwards. There are two canvases drawn, one with the map and another hidden one over top that tracks the click coordinates.

The staff member will then enter the item type (say Book), and the call number range for the shelf or the bay (FICAAAAA to FICAAABB, or J612.88 KAL to J629.4 WAL). 

Files:

- shelfpoint.php: is the file that draws the arrows based on the passed parameters. For example, using the URL: http://<yoursite>/shelfPoint/shelfpoint.php?branch=AU&itemtype=BK&callnumber=JFICAAAAB would bring up the map with the name AU, limit the database search by the itemtype (BK) and do a call number comparison (finds the database information where JFICAAAB falls between the start and end values). This file will need some adjustments for your map files.
- drawcoordinates.php: allows a user to select a map from the floorplans directory and add arrows. The staff member will need to click on the map where the arrow is to go, then input information relevant to that shelving location. They'll also need to determine the arrow direction (Up, Down, Left, Right). The arrows drawn can also be deleted and redrawn.
- mapSetup.js: this is the javascript file that handles most of the heavy lifting. Essentially this will pass (x,y) click coordinates back to the PHP, where the staff member will then input the item type and call number range.
- db.php: handles the connection to the database
- removeData.php: removes old / incorrect coordinate points from the database.
- insertData.php: adds coordinate points and data to the database.
