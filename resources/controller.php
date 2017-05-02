<?php
  define("PASTE_PATH", "pastes/");
  require_once "dbconn.php";

  // // // // // // // // // // // // //
  // Public helper functions          //
  // // // // // // // // // // // // //
  function getPasteAsHTML($uid) {
    $path = PASTE_PATH . getPasteFilename($uid);
    if ($path == PASTE_PATH) {
      return "<div class='help-text'>Paste anywhere to start</div>";
    } elseif (!file_exists($path)) {
      return errorHTML("An error occurred!");
    } elseif (endsWith($path, ".txt")) {
      return getTextPasteHTML(file_get_contents($path));
    } else {
      return getImagePasteHTML($path);
    }
  }

  // // // // // // // // // // // // //
  // Private helper functions         //
  // // // // // // // // // // // // //
  function endsWith($string, $test) {
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
  }

  function encrypt($string) {
    return hash("sha256", $string);
  }

  function genUID($user, $pass) {
    return encrypt($user . $pass);
  }

  function getPasteFilename($uid) {
    $dbc = connect_to_db();
    $cleanencrypteduid = $dbc->real_escape_string(encrypt($uid));
    $query = "SELECT filename
              FROM Users
              WHERE encrypteduid='$cleanencrypteduid'";
    $result = perform_query($dbc, $query);
    if (mysqli_num_rows($result) != 0) {
      return mysqli_fetch_array($result)['filename'];
    }
    return null;
  }

  function getTextPasteHTML($text) {
    return "<textarea readonly>$text</textarea>";
  }

  function getImagePasteHTML($path) {
    return "<img id='pasted-image' src='$path'>";
  }

  function deleteFileIfExists($filename) {
    $path = PASTE_PATH . $filename;
    if (file_exists($path)) {
      unlink($path);
    }
  }

  function errorHTML($text) {
    return "<div class='error'>$text</div>";
  }

  function successHTML($text) {
    return "<div class='success'>$text</div>";
  }

  // // // // // // // // // // // // //
  // Functions called via javascript  //
  // // // // // // // // // // // // //
  function paste($uid, $type, $data) {
    deleteFileIfExists(getPasteFilename($uid));
    if ($type == "image" && $data['size'] == 0) {
      return errorHTML("The image you pasted was rejected by the server");
    }
    // Get the proper extension so we can save the paste as a file
    if ($type == "text") {
      $extension = ".txt";
    } else {
      // This converts type identifiers like 'image/jpg' to '.jpg'
      $extension = '.'.substr($data['type'], strpos($data['type'], "/") + 1);
    }
    // Generate a new random and unique filename
    $newFilename = uniqid(bin2hex(random_bytes(7))) . $extension;
    // Make sure the paste directory exists
    if (!file_exists(PASTE_PATH)) { mkdir("pastes", 0744); }
    $path = PASTE_PATH . $newFilename;
    // If text, write directly to file, otherwise move the uploaded image
    if ($type == "text") {
      file_put_contents($path, $data);
    } else {
      move_uploaded_file($data["tmp_name"], $path);
    }
    // Update the database with the new filename
    $dbc = connect_to_db();
    $cleanencrypteduid = $dbc->real_escape_string(encrypt($uid));
    $query = "UPDATE Users
              SET filename='$newFilename'
              WHERE encrypteduid='$cleanencrypteduid'";
    perform_query($dbc, $query);
    // Return the new html to display clientside
    if ($type == "text") {
      return getTextPasteHTML($data);
    } else {
      return getImagePasteHTML($path);
    }
  }

  // This code runs when an ajax request is made
  if (!isset($_POST['action'])) return;
  switch($_POST['action']) {
    case 'textPaste':
      echo paste($_POST['uid'], "text", $_POST['text']);
      break;
    case 'imagePaste':
      echo paste($_POST['uid'], "image", $_FILES['image']);
      break;
  }

  // // // // // // // // // // // // //
  // Functions for login/registration //
  // // // // // // // // // // // // //
  function registerUser($user, $pass) {
    // Create the new UID
    $uid = genUID($user, $pass);
    // Encrypt the UID again for storage in the database
    $encrypteduid = encrypt($uid);
    // And clean to prevent SQL injection
    $dbc = connect_to_db();
    $cleanencrypteduid = $dbc->real_escape_string($encrypteduid);
    $cleanuser = $dbc->real_escape_string($user);
    // We use a more secure algorithm for password hashing
    $encryptedpass = password_hash($pass, PASSWORD_BCRYPT);
    $cleanencryptedpass = $dbc->real_escape_string($encryptedpass);
    $query = "INSERT INTO Users (encrypteduid, username, encryptedpassword)
      VALUES ('$cleanencrypteduid', '$cleanuser', '$cleanencryptedpass')";
    perform_query($dbc, $query);
    return $uid; // Return the actual UID, not the encrypted one
  }

  /* Takes in a username and an unencrypted password and checks to see if it
   * matches an entry in the database. If so, it returns the user's UID,
   * otherwise it returns false.
   */
  function checklogin($user, $pass) {
    $dbc = connect_to_db();
    $cleanuser = $dbc->real_escape_string($user); // clean for sql query
    $query = "SELECT encryptedpassword FROM Users WHERE username='$cleanuser'";
    $result = perform_query($dbc, $query);
    if (mysqli_num_rows($result) != 0) {
      $correctpass = mysqli_fetch_array($result)['encryptedpassword'];
      if (password_verify($pass, $correctpass)) {
        // Return the UID
        return genUID($user, $pass);
      }
    }
    return false;
  }


  function checkregister($user) {
    $dbc = connect_to_db();
    $cleanuser = $dbc->real_escape_string($user); // clean for sql query
    $query = "SELECT encryptedpassword FROM Users WHERE username='$cleanuser'";
    $result = perform_query($dbc, $query);
    if (mysqli_num_rows($result) != 0) {
      return false;
    }
    return true;
  }
?>