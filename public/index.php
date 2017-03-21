<?php
require "../application/dbconn.php";
session_start();
setup_db();
?>
<!DOCTYPE html>
<?php
# Handle new paste form
if (isset($_POST["paste"])) {
  echo $_POST["paste"];
}
?>
<html lang="en">
<head><title>CopyPaste</title></head>
<body>
<!-- Add code here to put a username/password box in the
     top right of the page. Next to the boxes we can have
     a log in and a register button. If they click the log
     in button, it logs them in. For registration, we want
     them to reenter their password to make sure they got it
     right. One possibility is to make additional box visible
     when they click register, and then make them click register
     again once they reenter their password. Feel free to do
     whatever you think makes the most sense.
     We also have to make sure the username and password are less
     than 32 characters (to fit in the database) -->
<h2>CopyPaste</h2>
<form method='post' id='pasteform'>
<input type='submit' value='New paste' />
</form>
<textarea name="paste" form="pasteform">Paste here...</textarea>
</body>
</html>