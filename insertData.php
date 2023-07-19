<?php
# *********************************************************************************************************************************************
# * File last edited: March 22, 2023
# * - provides form for input of map coordinate data
# * 
# * 03-22-23: Base Version - CZ
# *********************************************************************************************************************************************

# *********************************************************************************************************************************************
# * include the db file if not already
# *********************************************************************************************************************************************
include_once "db.php";

# *********************************************************************************************************************************************
# * grab the passed category
# *********************************************************************************************************************************************
if (isset($_GET["branch"])) { $branch = $_GET["branch"]; }
if (isset($_GET["itype"])) { $itype = $_GET["itype"]; }
if (isset($_GET["shelfName"])) { $shelfName = $_GET["shelfName"]; }
if (isset($_GET["callNumberLow"])) { $callNumberLow = $_GET["callNumberLow"]; }
if (isset($_GET["callNumberHigh"])) { $callNumberHigh = $_GET["callNumberHigh"]; }
if (isset($_GET["direction"])) { $direction = $_GET["direction"]; }
if (isset($_GET["xValue"])) { $xValue = 0 + $_GET["xValue"]; }
if (isset($_GET["yValue"])) { $yValue = 0 + $_GET["yValue"]; }

# *********************************************************************************************************************************************
# * prep data for database ... ensure nothing is undefined and escape strings for safe input
# *********************************************************************************************************************************************
$branch         = '' . addslashes($branch);
$itype          = '' . addslashes($itype);
$shelfName      = '' . addslashes($shelfName);
$callNumberLow  = '' . addslashes($callNumberLow);
$callNumberHigh = '' . addslashes($callNumberHigh);
$direction      = '' . addslashes($direction);
  
# *********************************************************************************************************************************************
# * insert the shelf information
# *********************************************************************************************************************************************
$sql  = "INSERT INTO shelfdata (branch, itemtype, shelfname, startvalue, endvalue, mapx, mapy, direction) ";
$sql .= "VALUES ('$branch', '$itype', '$shelfName', '$callNumberLow', '$callNumberHigh', $xValue, $yValue, '$direction') ";
mysqli_query($link, $sql) or die(mysqli_error($link));

# *********************************************************************************************************************************************
# * pass back the information so we can populate the table
# *********************************************************************************************************************************************
$response = array('id' => mysqli_insert_id($link), 'itemtype' => $itype, 'shelfName' => $shelfName, 'startValue' => $callNumberLow, 'endValue' => $callNumberHigh, 'direction' => $direction, 'xValue' => $xValue, 'yValue' => $yValue);
echo json_encode($response);
?>
