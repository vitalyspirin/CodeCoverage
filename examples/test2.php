<!DOCTYPE HTML>

<?php
error_reporting(-1);
require_once('../src/CodeCoverage.php');

use vitalyspirin\codecoverage\CodeCoverageTrackedFileList;
use vitalyspirin\codecoverage\CodeCoverage;


CodeCoverage::$trackedFileList['test2'] = [];
CodeCoverage::$trackedFileList['test2'][] = realpath(dirname(__FILE__) . '/A.php');

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
