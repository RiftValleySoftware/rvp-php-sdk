<?php
/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC.
    
    This code is proprietary and confidential code, 
    It is NOT to be reused or combined into any application,
    unless done so, specifically under written license from Little Green Viper Software Development LLC.

    Little Green Viper Software Development: https://littlegreenviper.com
*/
function run_test_01_harness_basic_login_tests($test_harness_instance) {
    if ($test_harness_instance->sdk_instance->is_logged_in()) {
        echo('<h2>Logged In. There are '.$test_harness_instance->sdk_instance->login_time_left().' Seconds Left.</h2>');
        $info = $test_harness_instance->sdk_instance->get_my_info();
    
//     echo('MY INFO:<pre>');
//     var_dump($info);
//     echo('</pre>');

        $info = $test_harness_instance->sdk_instance->get_user_info(1727);
    
    echo('DC ADMIN\'S INFO:<pre>');
    var_dump($info);
    echo('</pre>');
        
        if (isset($info) && isset($info->associated_login)) {
            $info = $test_harness_instance->sdk_instance->get_login_info($info->associated_login->id);
    
    echo('DC ADMIN\'S LOGIN INFO:<pre>');
    var_dump($info);
    echo('</pre>');
        }
    }
}
?>