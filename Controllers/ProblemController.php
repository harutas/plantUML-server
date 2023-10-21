<?php

namespace Controllers;

use Models;

class ProblemController
{
  private $problems;

  public function __construct()
  {
    $this->problems = new Models\Problem();
  }

  public function getProblems()
  {
    return $this->problems->readProblems();
  }
}
