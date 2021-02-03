#!/bin/bash
#############################################################################
#
# CPCCore - A PHP Micro Framework built upon Zend framework 2 Components
#
#############################################################################
# Script to run PHPUnit Tests
#
# Generates a testdox file and code coverage reports
# The reports are located in 'tmp/test-reports'
#############################################################################

cd ../tests/
TESTS_REPORT_FOLDER="tmp/test-reports"
TESTS_COVERAGE_REPORT_FOLDER="tmp/test-reports/code-coverage"

echo
echo
printf "%78s\n" | tr ' ' =
echo = Generating Unit Test Reports
printf "%78s\n" | tr ' ' =

echo Deleting old reports...
rm -Rf $TESTS_REPORT_FOLDER

echo
echo Creating Report Folders...
mkdir -p $TESTS_REPORT_FOLDER
mkdir -p $TESTS_COVERAGE_REPORT_FOLDER

echo Running Tests and generating new reports...
echo
printf "%78s\n" | tr ' ' -
echo
phpunit --testdox-text $TESTS_REPORT_FOLDER/testdox.txt --coverage-html $TESTS_COVERAGE_REPORT_FOLDER
echo
printf "%78s\n" | tr ' ' -
echo
echo
