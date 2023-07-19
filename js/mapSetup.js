$(document).ready(function() {
  // Store the canvas object into a variable
  var $floorPlan  = $('#floorplan');
  var $arrowPoint = $('#arrow');
  
  // remove clickability on the HTML5 canvas until there is an underlying map
  //$arrowPoint.css("cursor", "not-allowed");
  $arrowPoint.css("pointer-events", "none");

  // add the event listener to the screen so we can trap the x/y coordinates
  const canvas = document.querySelector('#arrow')
  canvas.addEventListener('mousedown', function(e) {
    getCursorPosition(canvas, e);
  });

  // give style to the table rows
  $("tr:even").css("background-color", "#eeeeee");
  $("tr:odd").css("background-color", "#ffffff");

  const queryString = window.location.search;
  const urlParams = new URLSearchParams(queryString);
  const branch = urlParams.get('branch');

  // grab the appropriate floorplan based on the URL parameter
  switch(branch) {
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
    $source = '';
	break;
  } 

  // draw out the floor plan
  $floorPlan.drawImage({
    source: $source,
    x: 0,
	y: 0,
    fromCenter: false,
    shadowColor: '#222',
    shadowBlur: 3
  });
  
  // if source is not null, readd the clickability of the canvas
  if ($source != '') {
    //$arrowPoint.css("cursor", "allowed");
	$arrowPoint.css("pointer-events", "auto");
  }
  
  // function to determine where to draw the arrows
  function getCursorPosition(canvas, event) {
         
    // determine the x and y coords of where the click was placed
    const rect = canvas.getBoundingClientRect()
    const x    = Math.round(event.clientX - rect.left)
    const y    = Math.round(event.clientY - rect.top)
         
    // draw a circle on the layer for visual feedback
    var ctx = canvas.getContext("2d");
    ctx.beginPath();
    ctx.fillRect(x-5, y-5, 10, 10); // subtract 5 to center the clicks
    ctx.fillStyle = 'green';
    ctx.fill();

    // trigger the button click to open the modal
    $('#myBtn').trigger('click');
    // send the data over to the forms hidden fields ... won't need to edit these
    $("#xValue").val(x);
    $("#yValue").val(y);
	$("#branch").val(branch);
  }
  
  
  // on hover, shows the location of the dots so they can be deleted if needed 
  $(document).on('mouseenter', 'tr', function(e) { // needs to be this to accomodate the append calls
    var x = $(this).attr('xcoord');
	var y = $(this).attr('ycoord');
    
    // draw a circle on the layer for visual feedback
    var ctx = canvas.getContext("2d");
    ctx.beginPath();
    ctx.fillRect(x-5, y-5, 10, 10);
    ctx.fillStyle = 'red';
    ctx.fill();
  });
 

  // removes the rectangle drawing to keep the screen uncluttered
  $(document).on('mouseleave', 'tr', function(e) { // needs to be this to accomodate the append calls
    var x = $(this).attr('xcoord');
	var y = $(this).attr('ycoord');
    
    // draw a circle on the layer for visual feedback
    var ctx = canvas.getContext("2d");
    ctx.beginPath();
    ctx.clearRect(x-5, y-5, 10, 10);
  }); 
 
  
  // removes the rectangle drawing to keep the screen uncluttered
  $(document).on('click', '#showCoordinates button', function(e) { // needs to be this to accomodate the append calls
	$.ajax("./removeData.php", {
      type: "get",
	  dataType: "html",
      data: {
        id: $(this).attr('id')
      },
      success: function(data) { },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
      }
    });
	
	$("#showCoordinates tr").trigger("mouseleave");
	$(this).closest("tr").remove();
  });
  
  // toggles the map to allow for inputting
  $("#changeMap").change(function () {
	var url = new URL(window.location.href);
    url.searchParams.set('branch', this.value);
    window.location.href = url.href;
  });
});