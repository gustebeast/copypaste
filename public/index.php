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

<?php
# Login/Register logic
$uid = "";
if (isset($_POST["logout"])) {
  $uid=false;
  unset($_SESSION['uid']);
}
elseif (isset($_POST["register"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];
  if (strlen($username) > 33){
    echo errorHTML("username must be less than 33 characters!");
  }
  elseif (strlen($password) > 33){
    echo errorHTML("password must be less than 33 characters!");
  }
  elseif(checkregister($username)){
    registerUser($username, $password);
    echo sucessHTML("$username has registered.");
  }
  else {
    echo errorHTML("$username is taken. Please choose another username.");
  }
}
elseif (isset($_POST["login"])) {
  $uid = checklogin($_POST["username"], $_POST["password"]);
  if ($uid) {
    $_SESSION['uid'] = $uid;
    $_SESSION["username"] = $_POST["username"];
    echo sucessHTML("Log in successful!");
  }
  else {
    echo errorHTML("Incorrect username and password.");
  }
}
if (!$uid && isset($_SESSION['uid'])) {
  $uid = $_SESSION['uid'];
}

// Bind the paste event if the user is logged in
if ($uid) {
  echo "<script type='text/javascript'>bindPaste('$uid');</script>";
  printf("<h2 style='color:#a83e7a'>%s is logged in!</h2>",
    $_SESSION["username"]);
} else {
  echo "<h2 style='color:#a83e7a'> Please log in! </h2>";
}
?>

<?php if(!$uid) : ?>
  <body>
  <div class="login-register-box">
    <form method='post'>
      <h2>
        <span id='login' class='box-title'>Login</span>
      </h2>
      <table>
      <tr><td>Username</td><td><input type='text' name='username'/></td></tr>
      <tr><td>Password</td><td><input type='text' name='password'/></td></tr>
      <tr class='verify-box'>
        <td>Verify</td><td><input type='text' name='password2'/></td>
      </tr>
      <tr>
        <td></td>
        <td><input id='login-register-button' type='submit'
          name='login' value='Log In'/></td>
      </tr>
    </form>
  </div>
  </body>
<?php else : ?>
  <body ondrop="drop(event, '<?php echo $uid ?>')"
    ondragover="allowDrop(event)">
  <form method='post'>
    <input id='logout' type='submit' name='logout' value='logout' />
  </form>
  <h2>CopyPaste</h2>
  <div id="paste-box">
    <?php echo getPasteAsHTML($uid); ?>
  </div>
  </body>
<?php endif; ?>
</html>