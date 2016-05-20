<?php

// Author: Vitaly Spirin

namespace vitalyspirin\codecoverage;

// usage:
// CodeCoverageTrackedFileList::$trackedFileList['test2'] = array();
// CodeCoverageTrackedFileList::$trackedFileList['test2'][] = realpath(dirname(__FILE__) . '/../../examples/A.php');

class CodeCoverageTrackedFileList
{
	public static $trackedFileList = array();

}
