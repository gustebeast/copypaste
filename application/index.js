// Run on page load
$(document).ready(function() {
    $("textarea").on("input", pasteBoxInput).select();
});

function pasteBoxInput() {
    if ($("#autosubmit:checked").length == 1) {
        $("#newpaste").click();
    }
}