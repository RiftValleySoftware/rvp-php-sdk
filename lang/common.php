<?php
/***************************************************************************************************************************/
/**
    BAOBAB PHP SDK
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/

define('_ERR_INVALID_SERVER_URI__', 1);         ///< This means that the server pointed to by the SDK URI is somehow invalid.
define('_ERR_INVALID_LOGIN__', 2);              ///< This means that the login/password combination sent to the server was not recognized.
define('_ERR_INVALID_AUTHENTICATION__', 3);     ///< This means that the authentication sent to the server was not valid.
define('_ERR_NOT_AUTHORIZED__', 4);             ///< This means that the attempted operations was not permitted.
define('_ERR_NO_RESULTS__', 5);                 ///< The last operation had no results, and results were expected.
define('_ERR_PREV_LOGIN__', 6);                 ///< There is a current login, so you cannot attempt a new login.
define('_ERR_COMM_ERR__', 7);                   ///< There was some kind of communication error with the server.
define('_ERR_INTERNAL_ERR__', 8);               ///< There was some kind of internal program error.

abstract class A_RVP_Locale {
    /***********************/
    /**
     */
    abstract protected static function _get_default_error_message();
    
    /***********************/
    /**
     */
    abstract protected static function _get_error_table();
    
    /***********************/
    /**
     */
    static function get_error_message(  $in_code    ///< REQUIRED: The error code to translate. An integer.
                                        ) {
        $key = "message_$in_code";
        $ret = static::_get_default_error_message();
        $table = static::_get_error_table();
        
        if (isset($table[$key]) && $table[$key]) {
            $ret = $table[$key];
        }
        
        return $ret;
    }
}
?>