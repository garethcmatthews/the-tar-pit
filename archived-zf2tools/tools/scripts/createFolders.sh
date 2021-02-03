#!/bin/bash

#############################################################################
# Script to Create any missing Data Directories and set permissions.
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
SCRIPT_TITLE="Create Data Directories"

#############################################################################
# Functions
#############################################################################

# Create Application Configuration Cache Directory
function createApplicationConfigurationCacheDirectory {

    # If directory does not exist then create it
    RenderText "Application Configuration Cache Directory:"
    if [ -d "$APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS" ];
    then
        RenderText "Directory Already Exists"
    else
        mkdir -p $APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS

        # Check Directory has been created
        if [ -d $APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS ];
        then
            RenderText "Created Directory"
        else
            RenderText "Error failed to create folder $APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS"
            exit 1
        fi
    fi

    # Set the Directories permissions
    RenderText "Setting Directory Permissions to 777"
    chmod 777 $APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS
}

#############################################################################
# Main
#############################################################################
if [[ $1 == "-f" ]];
then
    # Forced operation ######################################################
    RenderLineThin
    RenderText "$SCRIPT_TITLE"
    createApplicationConfigurationCacheDirectory
    RenderText "$SCRIPT_TITLE Script - Completed"
else
    # Interactive operation #################################################
    RenderText
    RenderLineThick
    RenderText "= $SCRIPT_TITLE"
    RenderLineThick
    RenderText "This script will create any missing data directories"
    RenderText

    # Await User Input
    read -p "Create Data Directories [Yes/no]? " -r
    if [[ $REPLY != "Yes" ]]
    then
        # Aborted
        RenderText
        RenderText "$SCRIPT_TITLE Script Has been Aborted"
        RenderLineThin
        RenderText
        exit
    fi

    # Create Application Configuration Cache Directory
    createApplicationConfigurationCacheDirectory

    # Completed
    RenderText
    RenderText "$SCRIPT_TITLE Script Completed"
    RenderLineThick
    RenderText
fi
