<?php

namespace Controllers;

use Models\Problem;
use Models\Problem\Problem as ProblemProblem;

class ProblemController
{
  private $problems;

  public function __construct()
  {
    $this->problems = new Problem\Problem();
  }

  public function getProblems()
  {
    return $this->problems->readProblems();
  }
}
