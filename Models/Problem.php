<?php

namespace Models;

class Problem
{
  private $problems = "../assets/problems.json";

  public function readProblems()
  {
    $data = file_get_contents($this->problems);
    return json_decode($data);
  }
}
