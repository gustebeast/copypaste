<?php
  function imagePaste($user, $image) {
    $filename = sprintf(
      "../application/pastes/%s_%s",
      $user,
      str_replace("/", ".", $image['type'])
    );
    // Move the uploaded file to the proper location
    move_uploaded_file($image["tmp_name"], $filename);
    // Return the filename clientside
    return $filename;
  }

  function textPaste($user, $text) {
    $path = sprintf("../application/pastes/%s.txt", $user);
    file_put_contents($path, $text);
  }

  function getImageURL($user) {
    $matches = glob("../application/pastes/" . $user . "_image*");
    if (count($matches) == 1) {
      return $matches[0];
    }
    return "";
  }

  function getPastedText($user) {
    $path = sprintf("../application/pastes/%s.txt", $user);
    if (file_exists($path)) {
        return file_get_contents($path);
    }
    return "";
  }

  if (!isset($_POST['action'])) return;
  switch($_POST['action']) {
    case 'imagePaste':
      echo imagePaste($_POST['user'], $_FILES['image']);
      break;
    case 'textPaste':
      textPaste($_POST['user'], $_POST['text']);
      break;
    case 'getImageURL':
      echo getImageURL($_POST['user']);
      break;
  }
?>