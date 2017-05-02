<?php
require "resources/dbconn.php";
require "resources/controller.php";
if (!file_exists("resources/sessions")) { mkdir("resources/sessions", 0744); }
session_save_path('resources/sessions');
ini_set('session.gc_probability', 1);
session_start();
setup_db();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Load css and javascript -->
<link href="resources/index.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js">
</script>
<script src="resources/index.js"></script>
<title>CopyPaste</title>
</head>

<?php
// Login/Register logic
$uid = "";
if (isset($_POST["logout"])) {
  $uid=false;
  unset($_SESSION['uid']);
}
elseif (isset($_POST["register"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];
  if (strlen($username) > 32 || strlen($username) < 5){
    echo errorHTML("Username must be between 5 and 32 characters!");
  }
  elseif (strlen($password) > 32 || strlen($password) < 5){
    echo errorHTML("Password must be between 5 and 32 characters!");
  }
  elseif ($password != $_POST["password2"]) {
    echo errorHTML("The passwords do not match!");
  }
  elseif(checkregister($username)){
    $uid = registerUser($username, $password);
    echo successHTML("$username has registered.");
  }
  else {
    echo errorHTML("$username is taken. Please choose another username.");
  }
}
elseif (isset($_POST["login"])) {
  $uid = checklogin($_POST["username"], $_POST["password"]);
  if ($uid) {
    $_SESSION['uid'] = $uid;
  }
  else {
    echo errorHTML("Incorrect username and password.");
  }
}
if (!$uid && isset($_SESSION['uid'])) {
  $uid = $_SESSION['uid'];
}
// If the user tried to register but failed,
// make sure the registration box pops up
if(isset($_POST["register"])) {
  echo "<script type='text/javascript'>switchLoginRegister('register')</script>";
}
?>
<?php if(!$uid) : ?>
  <body>
  <h1>CopyPaste</h1>
  <div class="login-register-box">
    <form method='post'>
      <h2>
        <div id='login' class='box-title selected-box'>Login</div> /
        <div id='register' class='box-title'>Register</div>
      </h2>
      <table>
      <tr>
        <td>Username</td>
        <td><input type='text' name='username' required/></td>
      </tr>
      <tr>
        <td>Password</td>
        <td><input type='password' name='password' required/></td>
      </tr>
      <tr id='verify' hidden>
        <td>Verify</td>
        <td><input type='password' name='password2'/></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <input id='confirm-button' type='submit' name='login' value='Log In'/>
        </td>
      </tr>
    </form>
  </div>
  </body>
<?php else : ?>
  <!-- Bind the paste event to the logged in user -->
  <script type='text/javascript'>bindPaste('<?php echo $uid ?>');</script>
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
<!-- If the user tried to register but failed,
     make sure the registration box pops up -->
<?php if(isset($_POST["register"])) : ?>
  <script type='text/javascript'>switchLoginRegister('register');</script>
<?php endif; ?>
</html>