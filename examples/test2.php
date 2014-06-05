<!DOCTYPE HTML>

<?php

require_once('../src/CodeCoverage/CodeCoverage.php');
$reportDir = 'CodeCoverageReports';

CodeCoverage::start($reportDir, "test2");

require_once('A.php');

$a = new A();

CodeCoverage::stop();

?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Test 2</title>
  </head>

  <body>

    <form method="get" action="reporting.php">
      <br />
      <button>Show Code Coverage Reports</button>
    </form>

  </body>
</html>
