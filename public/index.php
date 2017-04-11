<?php
require "../application/dbconn.php";
session_start();
setup_db();
?>
<!DOCTYPE html>
<?php
$_SESSION['username'] = "gus"; # This should be the actual username eventually

$paste = "";
$textareaclass = ""; # Used to make the text area green when text is pasted
$dbc = connect_to_db("CP");
$username = $_SESSION['username'];
if (!$username) {
    echo "Please log in!";
} else if (isset($_POST["paste"])) {
    # If the user made a new paste, use that
    $paste = $_POST["paste"];
    $textareaclass = "submitted";
    $path = sprintf("../application/pastes/%s.txt", $username);
    file_put_contents($path, $paste);
} else {
    # Otherwise look for an existing paste
    $path = sprintf("../application/pastes/%s.txt", $username);
    if (file_exists($path)) {
        $paste = file_get_contents($path);
    }
}

?>
<html lang="en">
<head>
<!-- Load css and javascript -->
<link href="../application/index.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="../application/index.js"></script>
<title>CopyPaste</title>
</head>
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
     We also have to do some checks on the username/password.
     They need to both be less than 33 characters, the username
     needs to not already be used and only contain alphanumeric
     characters. -->
<header id="login" style='text-align:center; border: solid #99838f; width:400px; padding-bottom: 40px;'>
    <h2> Login </h2>
    Username: <input id='username' type='text' name='username' value="<?php echo $username;?>"/> 
    <br/>
    Password: <input id='password' type='text' name='password' value="<?php echo $password;?>"/>
    <br/>
    <input id='login' type='submit' value='login' onclick="login()"/>

    <?php
    $username = $_POST["username"];
    $password = $_POST["password"];
    ?>
</header>

<div id="form">
    <h2>CopyPaste</h2>
    <form method='post' id='pasteform'>
    <?php
        printf(
            "<textarea maxlength='10000' name='paste' form='pasteform' class='%s'>%s</textarea>",
            $textareaclass,
            $paste
        );
    ?>
    <br>
    <input id='newpaste' type='submit' value='New paste' />
    <input id='autosubmit' type='checkbox' checked>Autosubmit</input>
    <br>
    <br>
    <div class='paste-box'></div>
    </form>
</div>
</body>
</html>