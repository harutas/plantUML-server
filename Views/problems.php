<?php
require_once("../Controllers/ProblemController.php");
require_once("../Models/Problem.php");

$problemController = new Controllers\ProblemController();
$problems = $problemController->getProblems();

define("MAX_PER_PAGE", 5);

$problems_num = count($problems);
$max_page = ceil($problems_num / MAX_PER_PAGE);

if (!isset($_GET["page_id"])) {
  $now = 1;
} else {
  $now = $_GET["page_id"];
}

$start_page_num = ($now - 1) * MAX_PER_PAGE;
$display_problems = array_slice($problems, $start_page_num, MAX_PER_PAGE, true);

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
          foreach ($display_problems as $problem) : ?>
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
          <?php
          if ($now > 1) {
            echo '
              <li class="page-item">
                <a class="page-link" href="problems.php?page_id=1" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>';
          } else {
            echo '
              <li class="page-item disabled">
                <a class="page-link" aria-label="Previous">
                  <span aria-hidden="true">&laquo;</span>
                </a>
              </li>';
          }
          for ($i = 1; $i <= $max_page; $i++) {
            if ($i == $now) {
              echo '<li class="page-item disabled"><a class="page-link">' . $i . '</a></li>';
            } else {
              echo '<li class="page-item"><a class="page-link" href="problems.php?page_id=' . $i . '">' . $i . '</a></li>';
            }
          }

          if ($now < $max_page) {
            echo '
                <li class="page-item">
                  <a class="page-link" href="problems.php?page_id=' . $max_page . '" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>';
          } else {
            echo '
                <li class="page-item disabled">
                  <a class="page-link" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>';
          }
          ?>
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