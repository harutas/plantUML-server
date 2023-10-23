<?php

namespace Models\UMLConverter;

class UMLComverter
{
  static private $jarFile = __DIR__ . "/plantuml-1.2023.11.jar";
  static private $base_output_dir = __DIR__ . "/../storage";

  static public function convert($userId, $codeString, $extension)
  {
    $output_dir = UMLComverter::$base_output_dir . "/{$userId}";

    if (!is_dir($output_dir)) {
      mkdir($output_dir, 0777, true);
    }

    $sequenceDiagram = $output_dir . "/{$userId}.txt";
    file_put_contents($sequenceDiagram, $codeString);

    // UML生成
    $command = "java -jar " . UMLComverter::$jarFile . " -{$extension} -o {$output_dir} {$sequenceDiagram}";
    exec($command);

    if ($extension == "txt") {
      $imageFiles = UMLComverter::globData($output_dir, "a" . $extension);
    } else {
      $imageFiles = UMLComverter::globData($output_dir, $extension);
    }

    if ($imageFiles) {
      foreach ($imageFiles as $filename) {
        $imageContents = file_get_contents($filename);
        $imageData[] = $imageContents;
      }

      UMLComverter::deleteAllDataInDir($output_dir);
      return $imageData;
    } else {
      UMLComverter::deleteAllDataInDir($output_dir);
      return null;
    }
  }

  static private function globData(string $dirname, string $extension)
  {
    return glob("{$dirname}/*.{$extension}");
  }

  static private function deleteAllDataInDir($output_dir)
  {
    if (file_exists($output_dir)) {
      array_map("unlink", glob("${output_dir}/*.*"));
      rmdir($output_dir);
    }
  }
}
