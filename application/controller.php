<?php
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


  function checklogin($user, $pw) {
    $sqlUsers = "SELECT * FROM Users";
    if ($sqlUsers[$user]){
      if ($sqlUsers[$user]==$pw) {
        $_SESSION['loggedin'] = True;
      }
    }
  }
?>