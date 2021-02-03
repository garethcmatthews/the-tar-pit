#!/bin/bash

#############################################################################
# Batch Script to Run the Utility Scripts in Order
#
# 1 - createFolders.sh
# 2 - clearZendCaches.sh
# 3 - generateModuleClassMaps.sh
# 4 - generateZendClassMaps.sh
#############################################################################

# Require Scripts
source ./helpers/render.sh

# Script Title
SCRIPT_TITLE="Zend Framework Application Build Script"

#############################################################################
# Main
#############################################################################
RenderText
RenderLineThick
RenderText "= $SCRIPT_TITLE"
RenderLineThick
RenderText "This script will Flush the Standard Zend framework Caches"
RenderText

# Run Scripts
source ./createFolders.sh -f
source ./clearZendCaches.sh -f
source ./generateModuleClassMaps.sh -f
source ./generateZendClassMaps.sh -f

# Completed
RenderText
RenderLineThick
RenderText "$SCRIPT_TITLE Script Completed"
RenderLineThick
RenderText
