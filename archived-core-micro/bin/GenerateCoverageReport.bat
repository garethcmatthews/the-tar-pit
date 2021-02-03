@echo off
REM #############################################################################
REM #
REM # CPCCore - A PHP Micro Framework built upon Zend framework 2 Components
REM #
REM #############################################################################
REM # Script to run PHPUnit Tests
REM #
REM # Generates a testdox file and code coverage reports
REM # The reports are located in 'tmp/test-reports'
REM #############################################################################

cd ../tests/
set TESTS_REPORT_FOLDER="./tmp/test-reports"
set TESTS_COVERAGE_REPORT_FOLDER="./tmp/test-reports/code-coverage"

echo.
echo.
echo =============================================================================
echo = Generating Unit Test Reports
echo =============================================================================
echo.
echo Deleting old reports...
rmdir %TESTS_REPORT_FOLDER% /s /q

echo Creating Report Folders...
mkdir %TESTS_REPORT_FOLDER%
mkdir %TESTS_COVERAGE_REPORT_FOLDER%

echo Running Tests and generating new reports...
echo.
echo -----------------------------------------------------------------------------
echo.
phpunit --testdox-text "%TESTS_REPORT_FOLDER%/testdox.txt" --coverage-html %TESTS_COVERAGE_REPORT_FOLDER%
echo.
echo -----------------------------------------------------------------------------
echo.
echo.
