<?php

namespace Models\UMLConverter;

class UMLComverter
{
  static private $jarFile = __DIR__ . "/plantuml-1.2023.11.jar";

  static public function convert($userId, $codeString)
  {
    // storageフォルダに一時的に生成する
    $output_dir = __DIR__ . "/../storage/{$userId}";

    if (!is_dir($output_dir)) {
      mkdir($output_dir, 0777, true);
    }

    $sequenceDiagram = $output_dir . "/{$userId}.txt";
    file_put_contents($sequenceDiagram, $codeString);

    $command = "java -jar " . UMLComverter::$jarFile . " -o {$output_dir} {$sequenceDiagram}";

    exec($command, $output, $result_code);

    $imageFiles = UMLComverter::globImages($output_dir);

    if ($imageFiles) {
      foreach ($imageFiles as $filename) {
        $imageContents = file_get_contents($filename);
        $base64 = base64_encode($imageContents);
        $imageData[] = $base64;
      }
      $res = array("status" => "ok", "images" => $imageData);

      header("Content-Type: application/json");
      return json_encode($res);
    } else {
      $res = array("status" => "failed", "message" => "no uml");
      header("Content-Type: application/json");
      return json_encode($res);
    }

    if (file_exists($output_dir)) {
      array_map("unlink", glob("${output_dir}/*.*"));
      rmdir($output_dir);
    }
  }

  static private function globImages(string $dirname)
  {
    return glob("${dirname}/*.png");
  }
}
