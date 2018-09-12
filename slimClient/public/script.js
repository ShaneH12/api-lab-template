$(document).ready(function() {
  $("#playerForm").submit(function(event) {
    var form = $(this);
    event.preventDefault();
    $.ajax({
      type: "POST",
      url: "http://localhost:8080/firstslim/player",
      data: form.serialize(), // serializes the form's elements.
      success: function(data) {
        window.location.replace("http://localhost:8080/slimClient");
      }
    });
  });
  $("#playerEditForm").submit(function(event) {

      var form = $(this);
      event.preventDefault();
      $.ajax({
        type: "PUT",
        url: "http://localhost:8080/firstslim/player/" + $(this).attr("data-id"),
        data: form.serialize(),
        success: function(data) {
          window.location.replace("http://localhost:8080/slimClient");
        }
      });
    }

  );
  $( ".deletebtn" ).click(function() {
  alert( "Are you sure you want to delete this person?" );

  $.ajax({
    type: "DELETE",
    url: "http://localhost:8080/firstslim/people/" + $(this).attr("data-id"),
    success: function(data) {
      window.location.reload("http://localhost:8080/slimClient");
    }
  });


});
});
