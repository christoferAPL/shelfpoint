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

$itemType = '';
if (isset($_GET["itemtype"])) { $itemType = $_GET["itemtype"]; }

$callNumber = '';
if (isset($_GET["callnumber"])) { $callNumber = $_GET["callnumber"]; }

// check values here .. if any are empty,  bounce

# *********************************************************************************************************************************************
# * generate SQL to grab the shelf information
# *********************************************************************************************************************************************
$sql  = "SELECT shelfname, mapx, mapy, direction ";
$sql .= "FROM shelfdata ";
$sql .= "WHERE branch = '$branch' ";
$sql .= " AND itemtype = '$itemType' ";
$sql .= " AND startvalue <= '$callNumber' AND endvalue >= '$callNumber' ";
$result = mysqli_query($link, $sql) or die(mysqli_error($link));

# *********************************************************************************************************************************************
# * grab the result set ... should only be one
# *********************************************************************************************************************************************
$row = $result->fetch_row();

// probably should check the size of the array .. if greater than 1, bounce

# *********************************************************************************************************************************************
# * need to flesh out the directional instructions
# *********************************************************************************************************************************************
switch ($branch) {
  case "MB2":
    $section  = "second floor";
	$longName = "Main";
    break;
  case "MC":
    $section  = "";
	$longName = "McLean";
    break;
  case "AU":
    $section  = "";
	$longName = "Audley";
    break;
}

?>

<!-- https://www.sitepoint.com/introduction-to-jcanvas-jquery-meets-html5-canvas/ -->
<!-- https://projects.calebevans.me/jcanvas/docs/arrows/ -->
<html>
  <head>
    <title>ShelfPoint</title>
	<link rel="stylesheet" href="./css/style.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="./js/jcanvas.min.js"></script>
    <script type="text/javascript">
	  $(document).ready(function() {
        // Store the canvas object into a variable
        var $floorPlan  = $('#floorplan');
		var $arrowPoint = $('#arrow');
		
        // store the variables from php into js
		var $mapx      = <?php echo $row[1]; ?>;
		var $mapy      = <?php echo $row[2]; ?>;
		var $shelf     = '<?php echo $row[0]; ?>';
		var $direction = '<?php echo $row[3]; ?>';
		var $branch    = '<?php echo $branch; ?>';

        // grab the appropriate floorplan based on the URL parameter
        switch($branch) {
        case 'AU':
          $source = 'http://172.16.2.38/shelfPoint/floorplans/audley.png';
          break;
        case 'MC':
          $source = 'http://172.16.2.38/shelfPoint/floorplans/mcLean.png';
          break;
        case 'MB2':
          $source = 'http://172.16.2.38/shelfPoint/floorplans/mainUp.png';
          break;
        default:
          return;
        }
  
        // draw the floor plan image on the underlying canvas
    	$floorPlan.drawImage({
          source: $source,
          x: 0, y: 0,
          fromCenter: false,
          shadowColor: '#222',
          shadowBlur: 3
        });

        // call function to draw out the arrow
    	if ($direction === "D") {
    	  $tailx = $mapx;
		  $taily = $mapy - 50;
        } else if ($direction === "U") {
    	  $tailx = $mapx;
		  $taily = $mapy + 50;
    	} else if ($direction === "L") {
    	  $tailx = $mapx + 50;
		  $taily = $mapy;
    	} else if ($direction === "R") {
    	  $tailx = $mapx - 50;
		  $taily = $mapy;
    	}

        // draw the arrow on the arrow canvas (z index +1 to floor plan)
		$arrowPoint.drawLine({
          //name: 'shelfLoc',
          strokeStyle: '#FF0000',
          strokeWidth: 6,
          rounded: true,
          startArrow: true,
          arrowRadius: 15,
          arrowAngle: 90,
          x1: $mapx, y1: $mapy,
          x2: $tailx, y2: $taily
        });
	  });
    </script>
  </head>

  <body>
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
      <p>The <span class="redArrow">red</span> arrow indicates the approximate location of your item on the map.</p>
	  <h2>Directions</h2>
	  <ul>
	    <li>This item can be found at the Library's <?php echo $longName; ?> branch.
		<?php if (! empty($section)) { ?>
		<li>Proceed to the <?php echo $section; ?>.
		<?php } ?>
		<li>Look for the shelf labelled: <?php echo $row[0]; ?>
		<li>Look for this item: <?php echo $callNumber; ?>
      </ul>
	  <br>
	  <p>If you require any assistance, please ask any member of our staff.</p>
	</div>
  </body>
</html>
