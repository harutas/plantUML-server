<?php
require_once("../Models/UMLConverter.php");

use Models\UMLConverter\UMLComverter;

$data = file_get_contents(("php://input"));
$post_data = json_decode($data);

if ($post_data) {
  $userId = $post_data->id;
  $codeString = $post_data->code;

  $umls = UMLComverter::convert($userId, $codeString, "png");

  if ($umls) {
    foreach ($umls as $uml) {
      $base64 = base64_encode($uml);
      $imageData[] = $base64;
    }
    $res = array("status" => "ok", "images" => $imageData);

    header("Content-Type: application/json");
    echo json_encode($res);
  } else {
    $res = array("status" => "failed", "message" => "no uml");

    header("Content-Type: application/json");
    echo json_encode($res);
  }
}
