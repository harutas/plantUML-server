<?php
$data = file_get_contents(("php://input"));
$post_data = json_decode($data);

if ($post_data) {
  $userId = $post_data->id;
  $codeString = $post_data->code;

  $uml_output = __DIR__ . "/storage/${userId}";

  $sequenceDiagram = "${userId}.txt";

  file_put_contents($sequenceDiagram, $codeString);
  $command = "java -jar plantuml-1.2023.11.jar -o ${uml_output} ${sequenceDiagram} 2>&1";

  exec($command, $output, $result_code);

  $imageFiles = globImages($uml_output);

  if ($imageFiles) {

    $imageFiles = globImages($uml_output);
    foreach ($imageFiles as $filename) {
      $imageContents = file_get_contents($filename);
      $base64 = base64_encode($imageContents);
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

if (file_exists($sequenceDiagram)) {
  unlink($sequenceDiagram);
}
if (file_exists($uml_output)) {
  array_map("unlink", glob("${uml_output}/*.*"));
  rmdir($uml_output);
}

function globImages(string $dirname)
{
  return glob("${dirname}/*.png");
}
