<?php

// Author: Vitaly Spirin

namespace vitalyspirin\codecoverage;


require_once('CodeCoverageFileReport.php');


class CodeCoverage
{

	// usage:
	// CodeCoverage::$trackedFileList['test2'] = array();
	// CodeCoverage::$trackedFileList['test2'][] = realpath(dirname(__FILE__) . '/../../examples/A.php');
	public static $trackedFileList = [];
	
	protected static $reportDir;
	protected static $userStoryFileName;
	const CODE_COVERAGE_OFF = 'off';


	protected static function initialize($reportDir)
	{
		self::$reportDir = $reportDir;
		self::$userStoryFileName = "$reportDir/userstory";
		
	    if ( !file_exists($reportDir) )
	    {
			if (!mkdir($reportDir, 0777)) 
			{
				echo "Unable to create directory $reportDir";
				return;
			}
	    }
	}


	protected static function getUserStoryFromFile()
	{
		if ( file_exists(self::$userStoryFileName) )
		{
			$userStory = file_get_contents(self::$userStoryFileName);
		} else
		{
			$userStory = null;
		}

		return $userStory;
	}


	public static function start($reportDir = null, $userStory = null)
	{
		if ($reportDir != null)
		{
			self::initialize($reportDir);
		}

	    if (function_exists('xdebug_start_code_coverage') ) // xDebug extension has to be installed
	    {
	    	if ( !empty($userStory) )
	    	{
	    		file_put_contents(self::$userStoryFileName, $userStory);
	    	} else
	    	{
	    		$userStory = self::getUserStoryFromFile();
	    	}

	    	if ($userStory != self::CODE_COVERAGE_OFF)
	    	{
		    	xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
		    }
	    }

	} // function start


	public static function disable($reportDir)
	{
		self::initialize($reportDir);

		file_put_contents(self::$userStoryFileName, self::CODE_COVERAGE_OFF);
	} // function disable


	public static function stop($reportDir = null)
	{
		if ($reportDir !== null)
		{
			self::initialize($reportDir);
		}

		if (function_exists('xdebug_start_code_coverage') ) // xDebug extension has to be installed
		{
			$userStory = self::getUserStoryFromFile();

			self::updateCodeCoverageReports($userStory);

			// some session handlers, close() for example, will be called after main script finished executing, so if we call CodeCoverage::stop()
			// and xdebug is stopped then it will be implossible to get code coverage stats for such session handlers.
			
			xdebug_stop_code_coverage(true); 
		}
		
		self::generateHtmlReports($reportDir);

	} // function stop


	public static function generateHtmlReports($reportDir = null)
	{
		if ($reportDir == null) {
			$reportDir = self::$reportDir;
		}
		
		$fileList = scandir($reportDir);
  
		$data = [];
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
		
		return $data;
	}


	public static function deleteTxtReports($reportDir = null)
	{
		self::deleteReports($reportDir, 'txt');
	}
	
	
	public static function deleteAllReports($reportDir = null)
	{
		self::initialize($reportDir);
		
		self::deleteReports($reportDir, null);
	}
	
	
	protected static function deleteReports($reportDir = null, $extension = null)
	{
		if ($reportDir == null) {
			$reportDir = self::$reportDir;
		}
		
		$fileList = scandir($reportDir);
  
		foreach($fileList as $file)
		{
			$fullFileName = $reportDir . "/" . $file;
		
			if ( is_file($fullFileName) )
			{
				if ( ($extension == null ) ||
				     ($extension != null && 
				    	pathinfo($file, PATHINFO_EXTENSION) == $extension)
				    )
				{
					unlink($fullFileName);
				}
			}
		}		
	}
	
	
	protected static function updateCodeCoverageReports($userStory = null)
	{
		$trackedFileList = self::$trackedFileList;


		$xDebugInfo = xdebug_get_code_coverage();

		if ($userStory != null && isset($trackedFileList[$userStory]) )
		{
			foreach($trackedFileList[$userStory] as $trackedFile)
			{
				if ( isset($xDebugInfo[$trackedFile]) )
				{
					CodeCoverageFileReport::create($xDebugInfo[$trackedFile], $trackedFile, self::$reportDir);
				}
			}
		} else
		{
			// show all files
			foreach($xDebugInfo as $fileName=>$fileInfo)
			{
				if ( strpos($fileName, DIRECTORY_SEPARATOR . 'simpletest' . DIRECTORY_SEPARATOR) === false ) // don't track files of SimpleTest framework
				{
			  		CodeCoverageFileReport::create($fileInfo, $fileName, self::$reportDir);
			  	}
			}
		}
	} // function updateCodeCoverageReports

} // class CodeCoverage
