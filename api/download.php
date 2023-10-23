<?php
require_once("../Models/UMLConverter.php");

use Models\UMLConverter\UMLComverter;

$data = file_get_contents(("php://input"));
$post_data = json_decode($data);

if ($post_data) {
  $userId = $post_data->id;
  $codeString = $post_data->code;
  $extension = $post_data->extension;

  $umls = UMLComverter::convert($userId, $codeString, $extension);

  if ($umls) {
    if ($extension == "png") {
      header('Content-Type: image/png');
      header('Content-Disposition: attachment; filename="convertUML.png"');
      echo $umls[0];
    } else if ($extension == "svg") {
      header('Content-Type: image/svg+xml');
      header('Content-Disposition: attachment; filename="convertUML.svg"');
      echo $umls[0];
    } else if ($extension == "txt") {
      header('Content-Type: image/svg+xml');
      header('Content-Disposition: attachment; filename="convertUML.txt"');
      echo $umls[0];
    } else {
      http_response_code(404);
    }
  } else {
    http_response_code(404);
  }
}
