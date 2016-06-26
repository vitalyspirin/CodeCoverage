<!DOCTYPE HTML>

<?php

require_once('../src/CodeCoverage.php');

use vitalyspirin\codecoverage\CodeCoverage;


$reportDir = 'CodeCoverageReports';

CodeCoverage::deleteAllReports($reportDir);
CodeCoverage::start();

require_once('A.php');

$a = new A();

CodeCoverage::stop();
CodeCoverage::deleteTxtReports();
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Test 1</title>
  </head>

  <body>

    <form method="get" action="reporting.php">
      <br />
      <button>Show Code Coverage Reports</button>
    </form>

  </body>
</html>
