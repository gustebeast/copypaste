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
  //https://www.w3schools.com/html/html5_draganddrop.asp

}


function pasteBoxInput() {
  if ($("#autosubmit:checked").length == 1) {
    $("#newpaste").click();
  }
}

function pasteResponse(html) {
  $("#paste-box").html(html);
}

function paste(e, user) {
  if (e.clipboardData) {
    var items = e.clipboardData.items;
    if (!items) return;
    let item = null;
    // Look through the paste information and try to find an image
    for (var i = 0; i < items.length; i++) {
      if (items[i].type.indexOf("image") !== -1) {
        item = items[i];
        break;
      }
    }

    // If an image was found, process it as such. Otherwise it must be text
    if (item) {
      var blob = item.getAsFile();
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
      }).done(pasteResponse);
    } else {
      var formData = new FormData();
      formData.append('text', e.clipboardData.getData('text'));
      formData.append('user', user);
      formData.append('action', 'textPaste');
      $.ajax({
        url: '../application/controller.php', 
        type: "POST", 
        cache: false,
        contentType: false,
        processData: false,
        data: formData
      }).done(pasteResponse);
    }
    e.preventDefault();
  }
}