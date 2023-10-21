<?php
require_once("../Controllers/ProblemController.php");
require_once("../Models/Problem.php");

$problemController = new Controllers\ProblemController();
$problems = $problemController->getProblems();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <title>PlantUML Server</title>
</head>

<body>
  <div class="container">
    <!-- Header -->
    <div class="my-2">
      <h1 class="fs-2 fw-bolder">PlantUML Server</h1>
    </div>

    <!-- Table -->
    <div class="my-4">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th colspan="2" scope="col">Titld</th>
            <th scope="col">Theme</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($problems as $problem) : ?>
            <?php $id = $problem->id ?>
            <tr style="cursor: pointer;" onclick="navigateToProblem(<?php echo $id ?>)">
              <th scope="row"><?php echo $problem->id ?></th>
              <td colspan="2"><?php echo $problem->title ?></td>
              <td><?php echo $problem->theme ?></td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>

    <!-- Pagenation -->
    <div class="d-flex justify-content-center">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
            </a>
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
  <script>
    function navigateToProblem(id) {
      window.location.href = `problem.php?id=${id}`
    }
  </script>
</body>

</html>