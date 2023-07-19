<?php
# *********************************************************************************************************************************************
# * File last edited: March 20, 2023
# * - Aspen MapMe functionality
# * 
# * 03-20-23: Working prototype - CZ
# * 03-14-23: Base Version - CZ
# *********************************************************************************************************************************************

# *********************************************************************************************************************************************
# * connect to the db if not already done
# *********************************************************************************************************************************************
include_once "db.php";

# *********************************************************************************************************************************************
# * grab the passed variables
# *********************************************************************************************************************************************
$branch = '';
if (isset($_GET["branch"])) { $branch = $_GET["branch"]; }
?>

<!-- https://www.sitepoint.com/introduction-to-jcanvas-jquery-meets-html5-canvas/ -->
<!-- https://projects.calebevans.me/jcanvas/docs/arrows/ -->
<html>
  <head>
    <title>ShelfPoint!</title>
    <link rel="stylesheet" href="./css/modal.css">
	<link rel="stylesheet" href="./css/style.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="./js/jcanvas.min.js"></script>
    <script type="text/javascript" src="./js/mapSetup.js"></script>
  </head>

  <body>
    <p>
	  <h2><i>Shelf Point!</i></h2>
	  <select id="changeMap">
        <option value="">Choose Map</option>
	    <option value="AU">Audley</option>
        <!--<option value="MB1">Main (First Floor)</option>-->
		<option value="MB2">Main (Second Floor)</option>
        <option value="MC">McLean</option>
      </select>
	</p>
	
    <div id = "leftbox">
	  <div style="position: relative;">
        <canvas id="floorplan" width="552" height="647" style="position: absolute; left: 0; top: 0; z-index: 0;"></canvas>
        <canvas id="arrow" width="552" height="647" style="position: absolute; left: 0; top: 0; z-index: 1;"></canvas>
      </div>
	</div>
	<div id = "middlebox">
      <p>&nbsp;</p>
	</div>
	<div id = "rightbox">
      <!-- Trigger/Open The Modal -->
      <button id="myBtn" style='display: none'>This is a hidden button to trigger the modal opening</button>

      <?php
        # *********************************************************************************************************************************************
        # * generate SQL to grab the shelf information
        # *********************************************************************************************************************************************
        $sql  = "SELECT id, itemtype, shelfname, startvalue, endvalue, direction, mapx, mapy ";
        $sql .= "FROM shelfdata ";
        $sql .= "WHERE branch = '$branch' ";
        $sql .= "ORDER BY itemtype, shelfname, startvalue, endvalue";
        $result = mysqli_query($link, $sql) or die(mysqli_error($link));
        
        echo "<table id='showCoordinates'>";
        echo "<tr><th width='10%'>ItemType</th><th width='20%'>Shelf Name</th><th width='15%'>Start Value</th><th width='15%'>End Value</th><th width='5%'>Direction</th><th width='10%'>&nbsp;</th></tr>";
        
        # *********************************************************************************************************************************************
        # * grab the result set ... should only be one
        # *********************************************************************************************************************************************
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr id='" . $row['id'] . "' xcoord='" . $row['mapx'] . "' ycoord='" . $row['mapy'] . "'><td>" . $row['itemtype'] . "</td><td>" . stripslashes($row['shelfname']) . "</td><td>" . stripslashes($row['startvalue']) . "</td><td>" . stripslashes($row['endvalue']) . "</td><td>" . $row['direction'] . "</td><td><button id='" . $row['id'] . "'>delete</button></td></tr>";
        }
        
        echo "</table>";
      ?>
	</div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
      <!-- Modal content -->
      <div class="modal-content">
        <span class="close">&times;</span>
        <form id='shelfLabel' name='shelfLabel'>
		  <!--<label>Branch:&nbsp;</label><input name='branch' size='10'><br>-->
		  <label>Item Type (must match ILS):&nbsp;</label><input name='itype' size='10'><br>
		  <label>Shelf Name:&nbsp;</label><input name='shelfName' size='50'><br>
		  <label>Lowest Call Number:&nbsp;</label><input name='callNumberLow' size='10'><br>
		  <label>Highest Call Number:&nbsp;</label><input name='callNumberHigh' size='10'><br>
		  <label>Arrow Direction:&nbsp;</label><input name='direction' size='5'><br>
          <!--<label>xCoordinate:&nbsp;</label><input id='xValue' name='xValue' size='5' ><br>
		  <label>yCoordinate:&nbsp;</label><input id='yValue' name='yValue' size='5' ><br>-->
          <input type="hidden" id="branch" name='branch' size='10'><br>
          <input type="hidden" id="xValue" name="xValue" value="3487">
          <input type="hidden" id="yValue" name="yValue" value="3487">
		  <input type="submit" value="Submit">
        </form>
      </div>
    </div>
  </body>
  
  <!-- need this here for some reason ... otherwise modal button doesn't work -->
  <script type="text/javascript" src="./js/modal.js"></script>
</html>
