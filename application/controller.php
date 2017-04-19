<?php
  require "../application/dbconn.php";

  function imagePaste($user, $image) {
    if ($image['size'] == 0) {
      return false;
    }
    deleteFileIfExists(getTextPath($user));
    $filename = sprintf(
      "../application/pastes/%s_%s",
      $user,
      str_replace("/", ".", $image['type'])
    );
    // Move the uploaded file to the proper location
    move_uploaded_file($image["tmp_name"], $filename);
    // Add a timestamp to the path to force the browser to refresh the image
    return getPasteBoxHTML("image", $filename . "?" . time());
  }

  function textPaste($user, $text) {
    deleteFileIfExists(getImagePath($user));
    file_put_contents(getTextPath($user), $text);
    return getPasteBoxHTML("text", $text);
  }

  function getImagePath($user) {
    $matches = glob("../application/pastes/" . $user . "_image*");
    if (count($matches) == 1) {
      return $matches[0];
    }
    return "";
  }

  function getTextPath($user) {
    return sprintf("../application/pastes/%s.txt", $user);
  }

  function getPastedText($user) {
    $path = sprintf("../application/pastes/%s.txt", $user);
    if (file_exists($path)) {
        return file_get_contents($path);
    }
    return "";
  }

  function deleteFileIfExists($path) {
    if (file_exists($path)) {
      unlink($path);
    }
  }

  function getPasteBoxHTML($type, $data) {
    if ($type == "text") {
      return "<textarea readonly>$data</textarea>";
    } elseif ($type = "image") {
      // Add a time stamp to the image url to force reload
      return "<img id='pasted-image' src='$data'>";
    } else {
      return "An error occurred!";
    }
  }

  function registerUser($user, $pass) {
    // 60 char hash plus 68 random characters = 128 char ID
    $uid = password_hash($user, PASSWORD_BCRYPT) . bin2hex(random_bytes(34));
    $uid = mysql_real_escape_string($uid);
    $pass = password_hash($pass, PASSWORD_BCRYPT);
    $pass = mysql_real_escape_string($pass);
    $dbc = connect_to_db();
    $query = "INSERT INTO Users (uid, username, password)
      VALUES ('$uid', '$user', '$pass')";
    perform_query($dbc, $query);
  }

  if (!isset($_POST['action'])) return;
  switch($_POST['action']) {
    case 'imagePaste':
      echo imagePaste($_POST['user'], $_FILES['image']);
      break;
    case 'textPaste':
      echo textPaste($_POST['user'], $_POST['text']);
      break;
    case 'getImagePath':
      echo getImagePath($_POST['user']);
      break;
  }
?>