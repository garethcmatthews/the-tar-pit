#!/bin/bash

#############################################################################
# Script to Generate Zend Framework Classmaps
#
# To batch this script and run without user input pass '-f' as the
# first parameter './generateZendClassMaps.sh -f'
#############################################################################

# Require Scripts
source ./helpers/render.sh
source ./settings/applicationPaths.sh

# Script Title
SCRIPT_TITLE="Zend Framework Library Class Maps Generation"

#############################################################################
# Functions
#############################################################################

# Generate Zend Framework Library Classmap
function generateZendFrameworkLibraryClassmap {
    RenderText
    RenderText "Generating Zend Framework Library Classmap:"
    cd $APPLICATION_PATH_ROOT
    php ${APPLICATION_PATH_VENDOR_BIN}/classmap_generator.php -w -l "${APPLICATION_PATH_VENDOR}/zendframework/zendframework"
}

# Generate ZendXml Classmap
function generateZendXmlClassmap {
    RenderText
    RenderText "Generating ZendXml Classmap:"
    cd $APPLICATION_PATH_ROOT
    php ${APPLICATION_PATH_VENDOR_BIN}/classmap_generator.php -w -l "${APPLICATION_PATH_VENDOR}/zendframework/zendxml"
}

#############################################################################
# Main
#############################################################################
if [[ $1 == "-f" ]];
then
    # Forced operation ######################################################
    RenderLineThin
    RenderText "$SCRIPT_TITLE"
    generateZendFrameworkLibraryClassmap
    generateZendXmlClassmap
    RenderText
    RenderText "$SCRIPT_TITLE Script - Completed"
else
    # Interactive operation #################################################
    RenderText
    RenderLineThick
    RenderText "= $SCRIPT_TITLE"
    RenderLineThick
    RenderText "This script will Generate the classmaps for the Zend Libraries"
    RenderText

    # Await User Input
    read -p "Clear Zend Caches [Yes/no]? " -r
    if [[ $REPLY != "Yes" ]]
    then
        # Aborted
        RenderText
        RenderText "$SCRIPT_TITLE Script Has been Aborted"
        RenderLineThick
        RenderText
        exit
    fi

    # Generate Classmap
    generateZendFrameworkLibraryClassmap
    generateZendXmlClassmap

    # Completed
    RenderText
    RenderText "$SCRIPT_TITLE Script Completed"
    RenderLineThick
    RenderText
fi
