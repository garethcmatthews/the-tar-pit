@echo off
REM #############################################################################
REM #
REM # CPCCore - A PHP Micro Framework built upon Zend framework 2 Components
REM #
REM #############################################################################
REM # Script to Generate Api Documentation in PHPDocumentor Format
REM #
REM # IMPORTANT:: To Run ensure that PHPDOC_BATCHFILE points to 'phpdoc.bat'
REM #############################################################################


echo.
echo.
echo =============================================================================
echo = Generating API Documentation
echo =============================================================================
echo.

cd ../
set PHPDOC_BATCHFILE="{IMPORTANT SET THIS TO POINT TO 'phpdoc.bat'}"
set SOURCE_FOLDER="src/CPCCore"
set OUTPUT_FOLDER="Documentation/Api"
set TEMPLATE="responsive-twig"

echo Deleting old Api Documentation...
rmdir %OUTPUT_FOLDER% /s /q

echo Creating Api Documentation Folder...
mkdir %OUTPUT_FOLDER%

echo Generating...
echo.
echo -----------------------------------------------------------------------------
echo.
call %PHPDOC_BATCHFILE% -d %SOURCE_FOLDER% -t %OUTPUT_FOLDER% --template=%TEMPLATE%"
echo.
echo -----------------------------------------------------------------------------
echo.
echo.



