function bindPaste(user) {
  $(document).bind(
    'paste',
    function(e) { paste(e.originalEvent, user); }
  );
}

function drop(e, user) {
    e.preventDefault();
    var files = e.dataTransfer.files;
    if (!files) return;
    let image = null;
    for (var i = 0; i < files.length; i++) {
      if (files[i].type.indexOf("image") !== -1) {
        image = files[i];
        break;
      }
    }
    if (image) {
      processImage(user, image);
    }
}

function allowDrop(e) {
    e.preventDefault();
}

function login() {
  //check login info in database

}


function pasteBoxInput() {
  if ($("#autosubmit:checked").length == 1) {
    $("#newpaste").click();
  }
}

function pasteResponse(html) {
  $("#paste-box").html(html);
}

function processImage(user, image) {
  var formData = new FormData();
  formData.append('image', image);
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
}

function paste(e, user) {
  //$("#login").hide();
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
      processImage(user, item.getAsFile());
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