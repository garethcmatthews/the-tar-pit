# CPC Opcache

This is a tool that I developed for personal use as I wanted to be able to manage a projects Opcache from a mobile device or tablet.

## Installation
composer create-project CrossPlatformCoder/cpc-opcache ./

**Important**
The project currently does not support installation into a sub folder your webserver needs to be configured to launch the project from the 'public' folder. If you would like to install it into a sub folder then refer to [Expressive using-a-base-path](http://zendframework.github.io/zend-expressive/cookbook/using-a-base-path/).

I will resolve this issue in a future release.

## Configuration:
There are a number of settings that can be configured in 'config/autoload/application.global.php'

    'application_settings' => [
        'content_tab' => 'Scripts', // [Overview | Scripts | Information]
        'wide_screen' => false, // bool
        'auto_refresh' => true, // bool
        'auto_refresh_interval' => 5, // int seconds
        'opcache_reset_confirm' => false, // bool
        'show_scripts_details_column' => true, // bool
    ],
    

- **content_tab** : Which tab is enabled at startup
- **wide_screen** : Enabled widescreen at startup
- **auto_refresh** : Enable auto refresh at startup
- **auto_refresh_interval** : Display update interval
- **opcache_reset_confirm** : Display a confirmation dialog for opcache clear
- **show_scripts_details_column** : Show the script details column on startup

## Todo:
There are a few features that I would still like to add and will do so when time allows. Refer to todo.txt in the root of the project for details of these features.

![desktop-scripts](/../master/build/readme-images/desktop-scripts.png)

![desktop-overview](/../master/build/readme-images/desktop-overview.png)

![mobile-scripts](/../master/build/readme-images/mobile-scripts.png)

![mobile-overview](/../master/build/readme-images/mobile-overview.png)
