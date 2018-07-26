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

function run_test_04_harness_basic_access_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $objects = $test_harness_instance->sdk_instance->get_objects(1724,1744,1725,1740,1723);
            if (5 == count($objects)) {
                $test_harness_instance->write_log_entry('COUNT CHECK', $test_count++, true);
                
                $object = $objects[0];
                
                if ($object instanceof RVP_PHP_SDK_Place) {
                    $test_harness_instance->write_log_entry('OBJECT 0 TYPE CHECK', $test_count++, true);
                    
                    if (1723 == $object->id()) {
                        $test_harness_instance->write_log_entry('OBJECT 0 ID CHECK', $test_count++, true);
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('OBJECT 0 ID CHECK', $test_count++, false);
                        echo('<h4 style="color:red">INCORRECT ID FOR OBJECT!</h4>');
                    }
                    
                    if ('Living Clean Group' == $object->name()) {
                        $test_harness_instance->write_log_entry('OBJECT 0 NAME CHECK', $test_count++, true);
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('OBJECT 0 NAME CHECK', $test_count++, false);
                        echo('<h4 style="color:red">INCORRECT NAME FOR OBJECT!</h4>');
                    }
                    
                    $object = $objects[1];
                
                    if ($object instanceof RVP_PHP_SDK_Place) {
                        $test_harness_instance->write_log_entry('OBJECT 1 TYPE CHECK', $test_count++, true);
                    
                        if (1724 == $object->id()) {
                            $test_harness_instance->write_log_entry('OBJECT 1 ID CHECK', $test_count++, true);
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('OBJECT 1 ID CHECK', $test_count++, false);
                            echo('<h4 style="color:red">INCORRECT ID FOR OBJECT!</h4>');
                        }
                    
                        if ('Recovery Through the Step Working Guides' == $object->name()) {
                            $test_harness_instance->write_log_entry('OBJECT 1 NAME CHECK', $test_count++, true);
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('OBJECT 1 NAME CHECK', $test_count++, false);
                            echo('<h4 style="color:red">INCORRECT NAME FOR OBJECT!</h4>');
                        }
                
                        $object = $objects[2];
                
                        if ($object instanceof RVP_PHP_SDK_User) {
                            $test_harness_instance->write_log_entry('OBJECT 2 TYPE CHECK', $test_count++, true);
                    
                            if (1725 == $object->id()) {
                                $test_harness_instance->write_log_entry('OBJECT 2 ID CHECK', $test_count++, true);
                            } else {
                                $all_pass = false;
                                $test_harness_instance->write_log_entry('OBJECT 2 ID CHECK', $test_count++, false);
                                echo('<h4 style="color:red">INCORRECT ID FOR OBJECT!</h4>');
                            }
                    
                            if ('MDAdmin' == $object->name()) {
                                $test_harness_instance->write_log_entry('OBJECT 2 NAME CHECK', $test_count++, true);
                            } else {
                                $all_pass = false;
                                $test_harness_instance->write_log_entry('OBJECT 2 NAME CHECK', $test_count++, false);
                                echo('<h4 style="color:red">INCORRECT NAME FOR OBJECT!</h4>');
                            }
                            
                            $object = $objects[3];
                
                            if ($object instanceof RVP_PHP_SDK_Thing) {
                                $test_harness_instance->write_log_entry('OBJECT 3 TYPE CHECK', $test_count++, true);
                    
                                if (1740 == $object->id()) {
                                    $test_harness_instance->write_log_entry('OBJECT 3 ID CHECK', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('OBJECT 3 ID CHECK', $test_count++, false);
                                    echo('<h4 style="color:red">INCORRECT ID FOR OBJECT!</h4>');
                                }
                    
                                if ('Crickets' == $object->name()) {
                                    $test_harness_instance->write_log_entry('OBJECT 3 NAME CHECK', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('OBJECT 3 NAME CHECK', $test_count++, false);
                                    echo('<h4 style="color:red">INCORRECT NAME FOR OBJECT!</h4>');
                                }
                    
                                if (__CRICKETS_SHA__ == sha1($object->payload()['data'])) {
                                    $test_harness_instance->write_log_entry('OBJECT 3 PAYLOAD PRESENT CHECK', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('OBJECT 3 PAYLOAD PRESENT CHECK', $test_count++, false);
                                    echo('<h4 style="color:red">INCORRECT NAME FOR OBJECT!</h4>');
                                }
                                
                                if ('audio/mpeg' == $object->payload()['type']) {
                                    $test_harness_instance->write_log_entry('OBJECT 3 PAYLOAD TYPE CHECK', $test_count++, true);
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('OBJECT 3 PAYLOAD TYPE CHECK', $test_count++, false);
                                    echo('<h4 style="color:red">INCORRECT PAYLOAD TYPE FOR OBJECT!</h4>');
                                }
                                                            
                                $object = $objects[4];
                
                                if ($object instanceof RVP_PHP_SDK_Thing) {
                                    $test_harness_instance->write_log_entry('OBJECT 4 TYPE CHECK', $test_count++, true);
                    
                                    if (1744 == $object->id()) {
                                        $test_harness_instance->write_log_entry('OBJECT 4 ID CHECK', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        $test_harness_instance->write_log_entry('OBJECT 4 ID CHECK', $test_count++, false);
                                        echo('<h4 style="color:red">INCORRECT ID FOR OBJECT!</h4>');
                                    }
                    
                                    if ('The Divine Comedy Illustrated.' == $object->name()) {
                                        $test_harness_instance->write_log_entry('OBJECT 4 NAME CHECK', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        $test_harness_instance->write_log_entry('OBJECT 4 NAME CHECK', $test_count++, false);
                                        echo('<h4 style="color:red">INCORRECT NAME FOR OBJECT!</h4>');
                                    }
                                    
                                    if (__DIVINE_COMEDY_SHA__ == sha1($object->payload()['data'])) {
                                        $test_harness_instance->write_log_entry('OBJECT 4 PAYLOAD PRESENT CHECK', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        $test_harness_instance->write_log_entry('OBJECT 4 PAYLOAD PRESENT CHECK', $test_count++, false);
                                        echo('<h4 style="color:red">INCORRECT PAYLOAD FOR OBJECT!</h4>');
                                    }

                                    if ('application/epub+zip' == $object->payload()['type']) {
                                        $test_harness_instance->write_log_entry('OBJECT 4 PAYLOAD TYPE CHECK', $test_count++, true);
                                    } else {
                                        $all_pass = false;
                                        $test_harness_instance->write_log_entry('OBJECT 4 PAYLOAD TYPE CHECK', $test_count++, false);
                                        echo('<h4 style="color:red">INCORRECT PAYLOAD TYPE FOR OBJECT!</h4>');
                                    }
                                } else {
                                    $all_pass = false;
                                    $test_harness_instance->write_log_entry('OBJECT 4 TYPE CHECK', $test_count++, false);
                                    echo('<h4 style="color:red">INCORRECT TYPE FOR OBJECT!</h4>');
                                }
                            } else {
                                $all_pass = false;
                                $test_harness_instance->write_log_entry('OBJECT 3 TYPE CHECK', $test_count++, false);
                                echo('<h4 style="color:red">INCORRECT TYPE FOR OBJECT!</h4>');
                            }
                        } else {
                            $all_pass = false;
                            $test_harness_instance->write_log_entry('OBJECT 2 TYPE CHECK', $test_count++, false);
                            echo('<h4 style="color:red">INCORRECT TYPE FOR OBJECT!</h4>');
                        }
                    } else {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('OBJECT 1 TYPE CHECK', $test_count++, false);
                        echo('<h4 style="color:red">INCORRECT TYPE FOR OBJECT!</h4>');
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('OBJECT 0 TYPE CHECK', $test_count++, false);
                    echo('<h4 style="color:red">INCORRECT TYPE FOR OBJECT!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('COUNT CHECK', $test_count++, false);
                echo('<h4 style="color:red">'.count($objects).' IS NOT THE CORRECT COUNT!</h4>');
            }
        } else {
            $all_pass = false;
            $test_harness_instance->write_log_entry('VALIDITY CHECK', $test_count++, false);
            echo('<h4 style="color:red">SERVER NOT VALID!</h4>');
        }
    } else {
        $all_pass = false;
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, false);
        echo('<h4 style="color:red">NO SDK INSTANCE!</h4>');
    }
    
    if ($all_pass) {
        echo('<h4 style="color:green">ALL OBJECTS FOUND AND ACCOUNTED FOR!</h4>');
    }
    
    $test_harness_instance->test_count = $test_count;
    return $all_pass;     
}
?>