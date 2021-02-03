#!/bin/bash
#############################################################################
#
# CPCCore - A PHP Micro Framework built upon Zend framework 2 Components
#
#############################################################################
# Script to Generate Api Documentation in PHPDocumentor Format
#
# IMPORTANT:: To Run ensure that PHPDOC_FILE points to 'phpdoc'
#############################################################################

echo
echo
printf "%78s\n" | tr ' ' =
echo = Generating API Documentation
printf "%78s\n" | tr ' ' =
echo

cd ../
PHPDOC_FILE="{IMPORTANT SET THIS TO POINT TO 'phpdoc.bat'}"
SOURCE_FOLDER="$PWD/src/CPCCore"
OUTPUT_FOLDER="$PWD/Documentation/Api"
TEMPLATE="responsive-twig"

echo Deleting old Api Documentation...
rm -Rf $OUTPUT_FOLDER

echo Creating Api Documentation Folder...
mkdir $OUTPUT_FOLDER

echo Generating API Documentation...
echo
printf "%78s\n" | tr ' ' -
echo
$PHPDOC_FILE --directory="$SOURCE_FOLDER" --target="$OUTPUT_FOLDER" --template="$TEMPLATE"
echo
printf "%78s\n" | tr ' ' -
echo
echo
