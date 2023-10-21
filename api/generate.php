<?php
require_once("../Models/UMLConverter.php");

use Models\UMLConverter\UMLComverter;

$data = file_get_contents(("php://input"));
$post_data = json_decode($data);

if ($post_data) {
  $userId = $post_data->id;
  $codeString = $post_data->code;
  echo UMLComverter::convert($userId, $codeString);
}
