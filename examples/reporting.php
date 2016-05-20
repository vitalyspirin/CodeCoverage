<?php

  require_once('../src/CodeCoverageFileReport.php');

  use vitalyspirin\codecoverage\CodeCoverageFileReport;


  $reportDir = dirname(__FILE__) . '/CodeCoverageReports';
  $reportWebDir = dirname($_SERVER["REQUEST_URI"]) . '/CodeCoverageReports';
  $fileList = scandir($reportDir);
  
  $data = array();
  foreach($fileList as $file)
  {
    $fullFileName = $reportDir . "/" . $file;
    
    if (is_file($fullFileName) && pathinfo($file, PATHINFO_EXTENSION) == 'txt')
    {
      $htmlReportFileName = CodeCoverageFileReport::convertTextReportIntoHtml($fullFileName);
      $data[$reportWebDir . "/" . $htmlReportFileName] = 
          round(CodeCoverageFileReport::getCodeCoverageSummary($fullFileName) * 100, 0);
    }
  }
  
?>


<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="http://tablesorter.com/__jquery.tablesorter.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://tablesorter.com/themes/blue/style.css">

    <style>
      table#codeCoverageSummary { width: 50%; margin: auto;}
    </style>

    <script type="text/javascript">
      $(document).ready(function() 
        { 
            $("#codeCoverageSummary").tablesorter( {sortList: [[1/*column*/, 1/*direction*/]]} ); 
        } 
      ); 
    </script>
    
    <title>Code Coverage</title>
  </head>

  <body>
    <table id="codeCoverageSummary" class="tablesorter">
      <thead>
        <tr>
          <th>File name</th>
          <th>Code Coverage %</th>
        </tr>
      </thead>
      
      <tbody>
        <?php
          $totalCoverage = 0;
          foreach($data as $fileName=>$codeCoverage)
          {
            $totalCoverage += $codeCoverage;
            $originalFileName = CodeCoverageFileReport::getOriginalFileName(basename($fileName));
        ?>
        <tr>
          <td><a href="<?php echo $fileName;?>"><?php echo $originalFileName;?></a></td>
          <td><?php echo $codeCoverage;?></td>
        </tr>
        <?php
          } 
        ?>
      </tbody>
      
      <tfoot>
        <tr>
          <td>Total</td>
          <td><?php echo (count($data)==0? 0 : round($totalCoverage/count($data), 0) ); ?></td>
        </tr>
      </tfoot>
    </table>
  </body>
</html>
