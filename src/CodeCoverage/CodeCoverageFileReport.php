<?php

// Author: Vitaly Spirin

class CodeCoverageFileReport
{
  
  public static function create($lineDataArray, $phpFileName, $reportDir)
  {
    if ( !file_exists($reportDir) )
    {
      if (!mkdir($reportDir, 0777)) 
      {
        echo "Unable to create directory $reportDir";
        return;
      }

    }

    $reportFullFileName = self::getReportFileName($phpFileName, $reportDir);
    
    if ( !file_exists($reportFullFileName) )
    {
      $PhpFileArray = file($phpFileName, FILE_IGNORE_NEW_LINES);
      $PhpFileArray = self::newReport($PhpFileArray, $lineDataArray);
    } else 
    {
      $PhpFileArray = file($reportFullFileName, FILE_IGNORE_NEW_LINES);
      $PhpFileArray = self::updateReport($PhpFileArray, $lineDataArray);
    }
    
    self::writeFile($PhpFileArray, $reportFullFileName);
  } // function create
  
  
  private static function writeFile($arr, $fileName)
  {
    $stringToWrite = implode(PHP_EOL, $arr);
    //var_dump($fileName);exit;    
    return file_put_contents($fileName, $stringToWrite);
  } // function writeFile
  
  
  private static function getReportFileName($phpFileName, $reportDir)
  {
    $reportFileName = str_replace(DIRECTORY_SEPARATOR, '__', $phpFileName);
    $reportFileName = str_replace(':', '___', $reportFileName);
    $reportFileName .= ".txt";
    
    return $reportDir . "/" . $reportFileName;
  }


  public static function getOriginalFileName($reportFileName)
  {
    $originalFileName = str_replace('___', ':', $reportFileName);
    $originalFileName = str_replace('__', DIRECTORY_SEPARATOR, $originalFileName);

    return $originalFileName;
  }
  
  
  private static function newReport($PhpFileArray, $lineDataArray)
  {
    for($i = 0; $i < count($PhpFileArray); $i++)
    {
      if ( isset($lineDataArray[$i+1]) )
      {
        $PhpFileArray[$i] = str_pad($lineDataArray[$i+1], 2, "  ", STR_PAD_LEFT) . $PhpFileArray[$i];
      } else
      {
        $PhpFileArray[$i] = "  " . $PhpFileArray[$i];
      }
    }
    
    return $PhpFileArray;
  }
  
  
  private static function updateReport($PhpFileArray, $lineDataArray)
  {
    for($i = 0; $i < count($PhpFileArray); $i++)
    {
      if ( isset($lineDataArray[$i+1]) && substr($PhpFileArray[$i], 0, 2) != " 1" )
      {
        $PhpFileArray[$i] = str_pad($lineDataArray[$i+1], 2, "  ", STR_PAD_LEFT) . substr($PhpFileArray[$i], 2);
      }
    }
    
    return $PhpFileArray;
    
  }
  
  public static function getCodeCoverageSummary($fileName)
  {
    $reportFileArray = file($fileName, FILE_IGNORE_NEW_LINES);

    $numberOfExecutedLines = 0;
    $numberOfNotExecutedLines = 0;
    
    for($i = 0; $i < count($reportFileArray); $i++)
    {
      $prefix = substr($reportFileArray[$i], 0, 2);
      switch ($prefix)
      {
        case " 1"  :  $numberOfExecutedLines++;
                      break;
                      
        case "-1"  :  $numberOfNotExecutedLines++;
                      break;
      }
    }
    
    if ($numberOfExecutedLines + $numberOfNotExecutedLines != 0)
    {
      $codeCoveragePercentage = $numberOfExecutedLines / ($numberOfExecutedLines + $numberOfNotExecutedLines);
    } else
    {
      $codeCoveragePercentage = 0;
    }

    return $codeCoveragePercentage;
  } // function getCodeCoverageSummary

  
  public static function convertTextReportIntoHtml($fullFileName)
  {
    $htmlFileHeader = "
<!DOCTYPE HTML>
<html>
  <head>
    <meta charset='UTF-8' />
    <title>$fullFileName</title>
    <style>
      * { margin: 0; padding: 0;}
      body {padding-left: 0.5em;}
      .lineNumber {color: grey; display: inline-block; width: 2em;}
      .executedLine {color:green;}
      .notExecutedLine {color: red;}
      .uncreachableLine {color: #F68B2A;}
    </style>
  </head>
  <body>
    <pre>
    ";
    $htmlFileFooter = "
    </pre>
  </body>
</html>
    ";
    
    $htmlPrefixForLineNumber = '<span class="lineNumber">';
    $htmlPrefixForExecutedLine = '<span class="executedLine">';
    $htmlPrefixForNotExecutedLine = '<span class="notExecutedLine">';
    $htmlPrefixForUnreachableLine = '<span class="uncreachableLine">';
    $htmlPrefixForRegularLine = '<span>';
    $htmlSuffix = '</span>';
    
    $reportFileArray = file($fullFileName, FILE_IGNORE_NEW_LINES);
  
    $numberOfExecutedLines = 0;
    $numberOfNotExecutedLines = 0;

    $htmlFileArray = array();
    $htmlFileArray[] = $htmlFileHeader;
    for($i = 0; $i < count($reportFileArray); $i++)
    {
      $prefix = substr($reportFileArray[$i], 0, 2);
      switch ($prefix)
      {
        case " 1"  :  $htmlPrefix = $htmlPrefixForExecutedLine;
                      break;
      
        case "-1"  :  $htmlPrefix = $htmlPrefixForNotExecutedLine;   
                      break;
                      
        case "-2"  :  $htmlPrefix = $htmlPrefixForUnreachableLine;
                      break;
        
        default    :  $htmlPrefix = $htmlPrefixForRegularLine;
      }
      $htmlFileArray[] = $htmlPrefixForLineNumber . ($i+1) . $htmlSuffix . " " . $htmlPrefix . 
        htmlspecialchars( substr($reportFileArray[$i], 2) ) . $htmlSuffix;
    }
    $htmlFileArray[] = $htmlFileFooter;
    
    $pathInfo = pathinfo($fullFileName);
    $htmlReportFullFileName = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.html'; 

    self::writeFile($htmlFileArray, $htmlReportFullFileName);
    
    return $pathInfo['filename'] . '.html';
  } // function convertTextReportIntoHtml
  
} // class CodeCoverageFileReport
