#!/bin/bash

#############################################################################
# Script to Clear Zend Framework Caches
#
# The cache folder is set in the variable
# $APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS found in the
# 'settings/applicationPaths.sh' script
#
# To batch this script and run without user input pass '-f' as the
# first parameter './clearZendCaches.sh -f'
#############################################################################

# Require Scripts
source ./helpers/render.sh
source ./settings/applicationPaths.sh

# Script Title
SCRIPT_TITLE="Zend Framework Cache Clear"

#############################################################################
# Functions
#############################################################################

# Clear the Zend Configuration Cache
function clearZendConfigurationCache {

    #Check Folder is Valid
    if [ ! -d "$APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS" ];
    then
        RenderText "ERROR: Not a Valid Directory $APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS"
        exit 1
    fi

    # Empty the Folder
    rm -Rf "$APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS"/*
    RenderText "Clearing Zend Configuration Folder '$APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS'"

    # Check Folder is Empty
    if [ "$(ls -A $APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS)" ];
    then
        RenderText "ERROR: Could not delete all files in '$APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS'"
        RenderText "Check the file permissions"
        exit 1
    fi
}

#############################################################################
# Main
#############################################################################
if [[ $1 == "-f" ]];
then
    # Forced operation ######################################################
    RenderLineThin
    RenderText "$SCRIPT_TITLE"
    clearZendConfigurationCache
    RenderText "$SCRIPT_TITLE Script - Completed"
else
    # Interactive operation #################################################
    RenderText
    RenderLineThick
    RenderText "= $SCRIPT_TITLE"
    RenderLineThick
    RenderText "This script will Flush the Standard Zend framework Caches"
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

    # Empty Zend Configuration Cache Folder
    RenderLineThin
    clearZendConfigurationCache

    # Completed
    RenderText
    RenderText "$SCRIPT_TITLE Script Completed"
    RenderLineThick
    RenderText
fi
