<?php

namespace Controllers;

use Models\Problem;

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

  public function getProblem(int $problemId)
  {
    $problems = $this->getProblems();
    return array_values(array_filter($problems, function ($problem) use ($problemId) {
      return $problem->id === $problemId;
    }))[0];
  }
}
