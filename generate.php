<?php
$data = file_get_contents(("php://input"));
$post_data = json_decode($data);

if ($post_data) {
  $codeString = $post_data->code;
  $sequenceDiagram = "sequenceDiagram.txt";

  file_put_contents($sequenceDiagram, $codeString);
  $command = "java -jar plantuml-mit-1.2023.11.jar sequenceDiagram.txt -";

  exec($command, $output, $result_code);

  $umlPath = "sequenceDiagram.png";

  header("Content-Type: image/png");
  readfile($umlPath);

  unlink($sequenceDiagram);
  unlink($umlPath);
}
