<?php

$reportDir = dirname(__FILE__) . '/CodeCoverageReports';

deleteReports($reportDir);


function deleteReports($reportDir)
{
  $fileList = scandir($reportDir);
  
  $errors = array();
  foreach($fileList as $file)
  {
    $fullFileName = $reportDir . "/" . $file;
    
    if (is_file($fullFileName))
    {
      $result = unlink($fullFileName);

      if ($result === false)
      {
        $errors[] = "Failure to delete file '$fullFileName'<br />";
      }
    }
  } // foreach

}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Exapmles of Code Coverage</title>
  </head>

  <body>

    <form method="get" action="test1.php">
      <br />
      <button>Test 1 (collect code coverage for all files)</button>  
    </form>

    <form method="get" action="test2.php">
      <br />
      <button>Test 2 (collect code coverage for specified files)</button>  
    </form>

  </body>
</html>
