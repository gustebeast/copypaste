<?php
require "../application/dbconn.php";
require "../application/controller.php";
session_start();
setup_db();
//header("Cache-Control: max-age=0, must-revalidate, no-store");
?>
<!DOCTYPE html>
<?php
//$_SESSION['username'] = "gus"; # This should be the actual username eventually

//check if someone has logged in
//if so, set username and pw and check login info
$_SESSION['loggedin'] = False;
if ($_POST["login"]){
    $_SESSION['username'] = $_POST["username"];
    $pw = $_POST["password"];
    $_SESSION['username']->checklogin($_SESSION['username'],$pw);
}

$loggedin = $_SESSION['loggedin'];

$dbc = connect_to_db("CP");
$user = $_SESSION['username'];
$userText = "";
$userImagePath = "";
# If a user is logged in, check to see if there is a saved paste on the server
if (!$user) {
    echo "Please log in!";
} else {
    $userText = getPastedText($user);
    if (!$userText) {
        $userImagePath = getImagePath($user);
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

    <form method='post'>
        <h2> Login </h2>
        Username: <input id='username' type='text' name='username'/> 
        <br/>
        Password: <input id='password' type='text' name='password'/>
        <br/>
        <input id='login' type='submit' name='login' value='login'/>
    </form>

    <!-- <script>
    if ($loggedin == False){
        text = "<form method='post'> <h2> Login </h2> Username: <input id='username' type='text' name='username'/> <br/> Password: <input id='password' type='text' name='password'/> <br/> <input id='login' type='submit' name='login' value='login'/> </form>";
    }
    else {
        text = "<h2> $user is logged in </h2>";
    }
    </script>
    text;
    -->
</header>
<body>
<h2>CopyPaste</h2>
<div id="paste-box">
    <?php
        if ($userText) {
            echo getPasteBoxHTML("text", $userText);
        } elseif ($userImagePath) {
            echo getPasteBoxHTML("image", $userImagePath);
        }
    ?>
</div>
</body>
</html>