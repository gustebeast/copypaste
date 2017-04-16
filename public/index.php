<?php
require "../application/dbconn.php";
require "../application/controller.php";
session_start();
setup_db();
?>
<!DOCTYPE html>
<?php
$_SESSION['username'] = "gus"; # This should be the actual username eventually

$dbc = connect_to_db("CP");
$user = $_SESSION['username'];
$userText = "";
$userImageSrc = "";
# If a user is logged in, check to see if there is a saved paste on the server
if (!$user) {
    echo "Please log in!";
} else {
    $userText = getPastedText($user);
    if (!$userText) {
        $userImageSrc = getImageURL($user);
    }
}

?>
<html lang="en">
<head>
<!-- Load css and javascript -->
<link href="../application/index.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js">
</script>
<script src="../application/index.js"></script>
<title>CopyPaste</title>
</head>
<!-- Add code here to put a username/password box in the
     top right of the page. Next to the boxes we can have
     a log in and a register button. If they click the log
     in button, it logs them in. For registration, we want
     them to reenter their password to make sure they got it
     right. One possibility is to make additional box visible
     when they click register, and then make them click register
     again once they reenter their password. Feel free to do
     whatever you think makes the most sense.
     We also have to do some checks on the username/password.
     They need to both be less than 33 characters, the username
     needs to not already be used and only contain alphanumeric
     characters. -->
<header id="login" style='text-align:center; border: solid #99838f; width:400px; padding-bottom: 40px;'>
    <h2> Login </h2>
    Username: <input id='username' type='text' name='username'/> 
    <br/>
    Password: <input id='password' type='text' name='password'/>
    <br/>
    <?php
    $username = $_POST["username"];
    $password = $_POST["password"];
    ?>
    <input id='login' type='submit' value='login' onclick="login($username,$password,$Users)"/>
</header>
<body>
<h2>CopyPaste</h2>
<div id="paste-box">
    <img id="pasted-image" src="<?php echo $userImageSrc ?>">
    <?php echo $userText ?>
</div>
</body>
</html>