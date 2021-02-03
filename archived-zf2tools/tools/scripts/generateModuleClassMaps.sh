#!/bin/bash

#############################################################################
# Script to Generate Zend Framework Classmaps for Application Modules
#
# To batch this script and run without user input pass '-f' as the
# first parameter './generateModuleClassMaps.sh -f'
#############################################################################

# Require Scripts
source ./helpers/render.sh
source ./settings/applicationPaths.sh

# Script Title
SCRIPT_TITLE="Module Class Maps Generation"

#############################################################################
# Functions
#############################################################################

# Scan for Modules
function scanModules {
    MODULE_NUMBER=1
    MODULE_FOLDERS=($APPLICATION_PATH_MODULE/*)
    for f in "${MODULE_FOLDERS[@]}";
    do
        RenderText "${MODULE_NUMBER} - $f"
        let "MODULE_NUMBER++"
    done
}

# Generate Module Classmaps
function generateModuleClassmaps {
    RenderText "Generating Module Classmaps:"
    MODULE_NUMBER=1
    MODULE_FOLDERS=($APPLICATION_PATH_MODULE/*)
    for f in "${MODULE_FOLDERS[@]}";
    do
        RenderText
        RenderText "${MODULE_NUMBER} - $f"
        php ${APPLICATION_PATH_VENDOR_BIN}/classmap_generator.php -w -l $f
        let "MODULE_NUMBER++"
    done
}

#############################################################################
# Main
#############################################################################
if [[ $1 == "-f" ]];
then
    # Forced operation ######################################################
    RenderLineThin
    RenderText "$SCRIPT_TITLE"
    generateModuleClassmaps
    RenderText
    RenderText "$SCRIPT_TITLE Script - Completed"
else
    # Interactive operation #################################################
    RenderText
    RenderLineThick
    RenderText "= $SCRIPT_TITLE"
    RenderLineThick
    RenderText "This script will Generate the classmaps for your Applications modules"
    RenderText

    # Scan for Modules
    RenderLineThin
    RenderText "Found Modules..."
    RenderText
    scanModules

    # Await User Input
    RenderText
    read -p "Generate Classmaps for these modules [Yes/no]? " -r
    if [[ $REPLY != "Yes" ]]
    then
        # Aborted
        RenderText
        RenderText "$SCRIPT_TITLE Script Has been Aborted"
        RenderLineThick
        RenderText
        exit
    fi

    # Generate Module Classmaps
    RenderLineThin
    generateModuleClassmaps

    # Completed
    RenderText
    RenderText "$SCRIPT_TITLE Script Completed"
    RenderLineThick
    RenderText
fi
