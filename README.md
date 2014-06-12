# CodeCoverage

============

Wrapper classes around xDebug to show code coverage data as html files.

### Quick Start and Examples
```php
$reportDir = 'CodeCoverageReports';  // directory for report files 
CodeCoverage::start($reportDir);

$a = new A();

CodeCoverage::stop();
```

```php
$reportDir = 'CodeCoverageReports';  // directory for report files 
CodeCoverage::start($reportDir, "test2"); // "test2" is used to indicate which files should be analyzed. Files are specified in CodeCoverageTrackedFileList.php

$a = new A();

CodeCoverage::stop();
```

### Screenshot
![screenshot1.png](/docs/screenshot1.png "Code Coverage screenshot")
 
