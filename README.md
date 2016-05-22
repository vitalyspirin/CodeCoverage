# CodeCoverage

Wrapper classes around xDebug to show code coverage data as html files.

## Installation
```
composer require vitalyspirin/codecoverage @dev
```
To generate HTML reports and see them in a table you can put file examples/reporting.php on the same level as a directory for code coverage reports. Then in a browser navigate to reporting.php.

Inside directory for code coverage reports you can create file 'userstory' with the word "off" as a content to disable code coverage (and speed up execution).

## Quick Start and Examples
```php
use vitalyspirin\codecoverage\CodeCoverage;

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

## Screenshot
![screenshot1.png](/docs/screenshot1.png "Code Coverage screenshot")
 
