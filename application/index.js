$(document).ready(function() {
  $('#login').click(function() {switchLoginRester('login');});
  $('#register').click(function() {switchLoginRester('register');});
});

function bindPaste(uid) {
  $(document).bind(
    'paste',
    function(e) { paste(e.originalEvent, uid); }
  );
}

function drop(e, uid) {
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
      var formData = getFormData(uid, "image", image);
      makeRequest(formData);
    }
}

function allowDrop(e) {
    e.preventDefault();
}

function switchLoginRester(to) {
  if (to == "login") {
    $('#register').removeClass('selected-box');
    $('#login').addClass('selected-box');
    $('#verify').hide();
    $('#confirm-button').attr('name', 'login').attr('value', 'Log In');
    $('input[name="password2"]').attr('required', false);
  } else {
    $('#login').removeClass('selected-box');
    $('#register').addClass('selected-box');
    $('#verify').show();
    $('#confirm-button').attr('name', 'register').attr('value', 'Register');
    $('input[name="password2"]').attr('required', true);
  }

}


function pasteBoxInput() {
  if ($("#autosubmit:checked").length == 1) {
    $("#newpaste").click();
  }
}

function pasteResponse(html) {
  $("#paste-box").html(html);
}

/* Takes a uid, type (either "image" or "text"), and
 * data which can be either text or an image file and
 * returns formData for an ajax request
 */
function getFormData(uid, type, data) {
  var formData = new FormData();
  formData.append(type, data);
  formData.append('uid', uid);
  formData.append('action', type + 'Paste');
  return formData;
}

function paste(e, uid) {
  if (e.clipboardData) {
    var items = e.clipboardData.items;
    if (!items) return;
    // Look through the paste information and try to find an image
    var imageItem = null;
    for (var i = 0; i < items.length; i++) {
      if (items[i].type.indexOf("image") !== -1) {
        imageItem = items[i];
        break;
      }
    }
    // Generate the form data for either the image or text paste
    if (imageItem) {
      var formData = getFormData(uid, "image", imageItem.getAsFile());
    } else {
      var formData = getFormData(uid, "text", e.clipboardData.getData('text'));
    }
    // Finally make the ajax call to confirm the paste server side
    makeRequest(formData);
    e.preventDefault();
  }
}

function makeRequest(formData) {
  $.ajax({
      url: '../application/controller.php', 
      type: "POST", 
      cache: false,
      contentType: false,
      processData: false,
      data: formData
    }).done(pasteResponse); // Get back HTML from the server
}