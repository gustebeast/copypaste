// Run on page load
$(document).ready(function() {
  $("textarea").on("input", pasteBoxInput).select();
  //$(document).on("paste", paste);
  document.addEventListener('paste', function(e) { paste(e); }, false);
});

function pasteBoxInput() {
  if ($("#autosubmit:checked").length == 1) {
    $("#newpaste").click();
  }
}

// Code inspired by ViliusL on StackOverflow
// https://goo.gl/5Ib4og

/**
 * image pasting into canvas
 * 
 * @param {string} id - div id of paste box
 * @param {boolean} autoresize - if paste box will be resized
 */
function paste(e) {
  if (e.clipboardData) {
    var items = e.clipboardData.items;
    if (!items) return;
    
    image = null;
    for (var i = 0; i < items.length; i++) {
      if (items[i].type.indexOf("image") !== -1) {
        var blob = items[i].getAsFile();
        //var URLObj = window.URL || window.webkitURL;
        //var source = URLObj.createObjectURL(blob);
        var formData = new FormData();
        formData.append('image', blob);
        $.ajax({
          url: 'paste.php', 
          type: "POST", 
          cache: false,
          contentType: false,
          processData: false,
          data: formData
        }).done(function(e) { alert('done!'); });
      }
    }
    if (image == null) {
      console.log(e.clipboardData.getData('text'));
    }
    
    e.preventDefault();
  }
}