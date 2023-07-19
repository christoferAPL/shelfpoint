// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

/***********************************************************************************************************************************************
 * handle the input form submit
 ***********************************************************************************************************************************************/
$(document).ready(function() {
  $(document).on("submit", '#shelfLabel', function(event) { 
    event.preventDefault();
    
/***********************************************************************************************************************************************
 *  validate what we've been given to what we expect ... if not then don't proceed
 ***********************************************************************************************************************************************/
//    $successFlag = validateCff();

//    if ($successFlag) {
      $.ajax("./insertData.php", {
        type: "get",
		dataType: "html",
        data: $(this).serialize(),
        success: function(data) {
          $('#shelfLabel').trigger("reset");
		  $('.close').trigger('click');

		  //alert(data.xValue);
		  var json = $.parseJSON(data); // create an object with the key of the array
		  $('#showCoordinates').append("<tr id='" + json.id + "' xcoord='" + json.xValue + "' ycoord='" + json.yValue + "'><td>" + json.itemtype + "</td><td>" + json.shelfName + "</td><td>" + json.startValue + "</td><td>" + json.endValue + "</td><td>" + json.direction + "</td><td><button id='" + json.id + "'>delete</button></td></tr>");
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
        }
      });
//    }
  });
});
