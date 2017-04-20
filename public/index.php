<?php
require "../application/dbconn.php";
require "../application/controller.php";
session_start();
setup_db();
//header("Cache-Control: max-age=0, must-revalidate, no-store");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Load css and javascript -->
<link href="../application/index.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js">
</script>
<script src="../application/index.js"></script>
<title>CopyPaste</title>
</head>

<!-- Log in logic -->
<?php
//check if someone has logged in
//if so, set username and pw and check login info
$uid = "";
if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    // TODO: Need checks here to validate the username and password
    // We have to make sure the username/password are less than 33 characters
    // and the username is not already used 

    registerUser($username, $password);
} elseif (isset($_POST["login"])) {
    $uid = checklogin($_POST["username"], $_POST["password"]);
    if ($uid) {
        // Log in successful
        $_SESSION['uid'] = $uid;
    } else {
        // Log in failed
    }
}
if (!$uid && isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
}

// Bind the paste event if the user is logged in
if ($uid) {
    echo "<script type='text/javascript'>bindPaste('$uid');</script>";
} else {
    echo "Please log in!";
}

?>

<!-- Add code here to put a username/password box in the
     top right of the page. Next to the boxes we can have
     a log in and a register button. If they click the log
     in button, it logs them in. For registration, we want
     them to reenter their password to make sure they got it
     right. One possibility is to make additional box visible
     when they click register, and then make them click register
     again once they reenter their password. Feel free to do
     whatever you think makes the most sense. -->
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
<header id="register" style='text-align:center; border: solid #99838f; width:400px; padding-bottom: 40px;'>
    <form method='post'>
        <h2> Register </h2>
        Username: <input id='username' type='text' name='username'/> 
        <br/>
        Password: <input id='password' type='text' name='password'/>
        <br/>
        <input id='register' type='submit' name='register' value='register'/>
    </form>
</header>

<body ondrop="drop(event, '<?php echo $uid ?>')" ondragover="allowDrop(event)">
<h2>CopyPaste</h2>
<div id="paste-box">
    <?php echo getPasteAsHTML($uid); ?>
</div>
<br>
<?php
    // A little input box so mobile clients can paste, apologies for ugliness
    $useragent=$_SERVER['HTTP_USER_AGENT'];

    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
        echo '<input type="text" onpaste="paste(event, \'$uid\')" value="">';
    }
?>
</body>
</html>