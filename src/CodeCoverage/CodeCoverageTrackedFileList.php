<?php

// Author: Vitaly Spirin

class CodeCoverageTrackedFileList
{
	protected $trackedFileList = array();

	public function __construct()
	{
		$this->trackedFileList['test2'] = array();
		$this->trackedFileList['test2'][] = realpath(dirname(__FILE__) . '/../../examples/A.php');
	}


	public function getFileList()
	{
		return $this->trackedFileList;
	}


} // class CodeCoveragethis->trackedFileList
