<?php
/***************************************************************************************************************************/
/**
    BLUE DRAGON PHP SDK
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
define('LGV_CONFIG_CATCHER', true);
require_once (dirname(dirname(__FILE__)).'/config/s_config.class.php');
if (file_exists(dirname(dirname(dirname(__FILE__))).'/basalt/entrypoint.php')) {
    require_once (dirname(dirname(__FILE__)).'/basalt/entrypoint.php');
} else {
    require_once (dirname(dirname(dirname(dirname(__FILE__)))).'/baobab/basalt/entrypoint.php');
}
?>