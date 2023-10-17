<?php
$data = file_get_contents(("php://input"));
$post_data = json_decode($data);

if ($post_data) {
  $userId = $post_data->id;
  $codeString = $post_data->code;

  $uml_output = "./storage/${userId}";

  $sequenceDiagram = "${userId}.txt";

  file_put_contents($sequenceDiagram, $codeString);
  $command = "java -jar plantuml-mit-1.2023.11.jar -o ${uml_output} ${sequenceDiagram} 2>&1";

  exec($command, $output, $result_code);

  if (count($output) === 0) {

    $imageFiles = getImages($uml_output);
    foreach ($imageFiles as $filename) {
      $imageContents = file_get_contents($filename);
      $base64 = base64_encode($imageContents);
      $imageData[] = $base64;
    }
    $res = array("status" => "ok", "images" => $imageData);

    header("Content-Type: application/json");
    echo json_encode($res);
  } else {
    if ($result_code === 0) {
      // エラーメッセージを生成
      $message = "";
      foreach ($output as $line) {
        $message .= $line . "\n";
      }
      $res = array("status" => "failed", "message" => $message);
      header("Content-Type: application/json");
      echo json_encode($res);
    } else {
      $res = array("status" => "failed", "message" => "実行エラーが生じました");
      header("Content-Type: application/json");
      echo json_encode($res);
    }
  }
}

if (file_exists($sequenceDiagram)) {
  unlink($sequenceDiagram);
}
if (file_exists($uml_output)) {
  array_map("unlink", glob("${uml_output}/*.*"));
  rmdir($uml_output);
}

function getImages(string $dirname)
{
  return glob("${dirname}/*.png");
}
