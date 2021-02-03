#!/bin/bash

#############################################################################
# Application Paths
#############################################################################

# Current Script Folder
APPLICATION_PATH_CURRENT_SCRIPT=`pwd`

# Application Root Folder
APPLICATION_PATH_ROOT=$(readlink -f "$APPLICATION_PATH_CURRENT_SCRIPT/../../")

# Configuration Folder
APPLICATION_PATH_CONFIG="$APPLICATION_PATH_ROOT/config"

# Data Folder
APPLICATION_PATH_DATA="$APPLICATION_PATH_ROOT/data"
APPLICATION_PATH_DATA_CACHE="$APPLICATION_PATH_ROOT/data/cache"
APPLICATION_PATH_DATA_CACHE_CONFIGURATIONS="$APPLICATION_PATH_ROOT/data/cache/configurations"

# Module Folder
APPLICATION_PATH_MODULE="$APPLICATION_PATH_ROOT/module"

# Public Folder
APPLICATION_PATH_PUBLIC="$APPLICATION_PATH_ROOT/public"
APPLICATION_PATH_PUBLIC_CSS="$APPLICATION_PATH_ROOT/public/css"
APPLICATION_PATH_PUBLIC_FONTS="$APPLICATION_PATH_ROOT/public/fonts"
APPLICATION_PATH_PUBLIC_IMG="$APPLICATION_PATH_ROOT/public/img"
APPLICATION_PATH_PUBLIC_JS="$APPLICATION_PATH_ROOT/public/js"

# Vendor Folder
APPLICATION_PATH_VENDOR="$APPLICATION_PATH_ROOT/vendor"
APPLICATION_PATH_VENDOR_BIN="$APPLICATION_PATH_ROOT/vendor/zendframework/zendframework/bin"
