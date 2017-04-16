// Run on page load
$(document).ready(function() {
  document.addEventListener(
    'paste',
    function(e) { paste(e, "gus"); },
    false
  );
});



function login() {
  //check login info in database
  

}


function pasteBoxInput() {
  if ($("#autosubmit:checked").length == 1) {
    $("#newpaste").click();
  }
}

function imageResponse(e) {
  $("#pasted-image").attr("src", e);
}

function paste(e, user) {
  if (e.clipboardData) {
    var items = e.clipboardData.items;
    if (!items || items.length != 1) return;

    if (items[0].type.indexOf("image") !== -1) {
      var blob = items[0].getAsFile();
      var formData = new FormData();
      formData.append('image', blob);
      formData.append('user', user);
      formData.append('action', 'imagePaste');
      $.ajax({
        url: '../application/controller.php', 
        type: "POST", 
        cache: false,
        contentType: false,
        processData: false,
        data: formData
      }).done(imageResponse);
    } else {
      console.log(e.clipboardData.getData('text'));
    }
    
    e.preventDefault();
  }
}