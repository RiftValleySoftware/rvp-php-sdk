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
defined('__TEST_30_WORTH_ENOUGH_KEY__') or define('__TEST_30_WORTH_ENOUGH_KEY__', 'basalt-test-0171: Worth Enough');
defined('__CSV_TEST_30_FILE__') or define('__CSV_TEST_30_FILE__', 'test-30-small-worth-enough');
function run_test_30_things_put_tests($test_harness_instance) {
    $all_pass = false;
    $test_count = $test_harness_instance->test_count;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $all_pass = true;
            $worth_enough_thing = $test_harness_instance->sdk_instance->get_thing_info(__TEST_30_WORTH_ENOUGH_KEY__);
            
            if (isset($worth_enough_thing) && ($worth_enough_thing instanceof RVP_PHP_SDK_Thing)) {
                $test_harness_instance->write_log_entry('Payload Test. Confirm Fetch Object for String Key \''.__TEST_30_WORTH_ENOUGH_KEY__.'\'.', $test_count++, true);
                $sha = '4b4d79107eeea9ec1eacdab5a7634e3d1189e745';
                $all_pass &= run_test_30_harness_get_payload_tests_load_1_payload($test_harness_instance, $worth_enough_thing, $sha, $test_count);
                
                $new_image = file_get_contents(dirname(__FILE__).'/worth-enough-test-30.png');

                if ($worth_enough_thing->set_payload($new_image)) {
                    $test_harness_instance->write_log_entry('Set New Image for \''.__TEST_30_WORTH_ENOUGH_KEY__.'\'.', $test_count++, true);
                    $sha = 'f3e063b45b370a05d952deefceec2b6cae5f6877';
                    $all_pass &= run_test_30_harness_get_payload_tests_load_1_payload($test_harness_instance, $worth_enough_thing, $sha, $test_count);
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('Set New Image for \''.__TEST_30_WORTH_ENOUGH_KEY__.'\'.', $test_count++, false);
                    echo('<h4 style="color:red">Failure to Upload New Image!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Payload Test. Confirm Fetch Object for String Key \''.__TEST_30_WORTH_ENOUGH_KEY__.'\'.', $test_count++, true);
                echo('<h4 style="color:red">Cannot Fetch the Thing By Its String Key!</h4>');
            }
            
            $test_file_loc = dirname(__FILE__).'/'.__CSV_TEST_30_FILE__.'.csv';
            if (file_exists($test_file_loc)) {
                $csv_data = file_get_contents($test_file_loc);
                if (isset($csv_data) && $csv_data) {
                    $control_sha = '0e3a7a6ba3182cf4ac6bf5cefa70848c50de07f8';
                    $response = $test_harness_instance->sdk_instance->bulk_upload($csv_data);
                    $variable_sha = sha1(serialize($response));
                    RVP_PHP_SDK_Test_Harness::static_echo_sha_data($variable_sha);
                    if ($variable_sha != $control_sha) {
                        $all_pass = false;
                        $test_harness_instance->write_log_entry('BULK LOAD SHA CHECK', $test_count++, false);
                        echo('<h4 style="color:red">SHAS DO NOT MATCH!</h4>');
                    } else {
                        $test_harness_instance->write_log_entry('BULK LOAD SHA CHECK', $test_count++, true);
                        $worth_enough_thing = $test_harness_instance->sdk_instance->get_thing_info(__CSV_TEST_30_FILE__);
                        if (isset($worth_enough_thing) && ($worth_enough_thing instanceof RVP_PHP_SDK_Thing)) {
                            $sha = 'f3e063b45b370a05d952deefceec2b6cae5f6877';
                            $all_pass &= run_test_30_harness_get_payload_tests_load_1_payload($test_harness_instance, $worth_enough_thing, $sha, $test_count);
                        }
                    }
                } else {
                    $all_pass = false;
                    $test_harness_instance->write_log_entry('BULK LOAD FILE VALIDITY CHECK', $test_count++, false);
                    echo('<h4 style="color:red">BULK LOAD FILE ('.htmlspecialchars($test_file_loc).') DATA INVALID!</h4>');
                }
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('BULK LOAD FILE CHECK', $test_count++, false);
                echo('<h4 style="color:red">NO BULK LOAD FILE ('.htmlspecialchars($test_file_loc).')!</h4>');
            }
        } else {
            $test_harness_instance->write_log_entry('VALIDITY CHECK', $test_count++, false);
            echo('<h4 style="color:red">SERVER NOT VALID!</h4>');
        }
    } else {
        $test_harness_instance->write_log_entry('INSTANTIATION CHECK', $test_count++, false);
        echo('<h4 style="color:red">NO SDK INSTANCE!</h4>');
    }
    
    if ($all_pass) {
        echo('<h4 style="color:green">TEST SUCCESSFUL!</h4>');
    }
    
    $test_harness_instance->test_count = $test_count;
    
    return $all_pass;     
}

function run_test_30_harness_get_payload_tests_load_1_payload($in_test_harness_instance, $in_id, $in_sha, &$test_count, $has_payload = true) {
    $all_pass = true;
    $object = NULL;
    
    if (!($in_id instanceof A_RVP_PHP_SDK_Object)) {   // We have a sleazy way of passing in an already-fecthed object.
        $object_instances = $in_test_harness_instance->sdk_instance->get_objects(intval($in_id));
    
        if (isset($object_instances) && is_array($object_instances) && (1 == count($object_instances)) && ($object_instances[0] instanceof A_RVP_PHP_SDK_Object)) {
            $object = $object_instances[0];
            $in_test_harness_instance->write_log_entry('Payload Test. Confirm Fetch Object for ID '.$in_id.'.', $test_count++, true);
        } else {
            echo('<h4 style="color:red">Cannot Get The Record for ID '.$in_id.'!</h4>');
            $in_test_harness_instance->write_log_entry('Payload Test. Instantiate Object '.$in_id.'.', $test_count++, false);
            return false;
        }
    } else {
        $object = $in_id;
    }
    
    if ($object instanceof A_RVP_PHP_SDK_Object) {
        echo('<h4>Fetching the Payload for Object ID '.$object->id().'</h4>');
        $payload = $object->payload();
        
        if ($payload && $has_payload) {
            $in_test_harness_instance->write_log_entry('Payload Test. Confirm Payload Present.', $test_count++, true);
            
            $sha_na_na = sha1($payload['data']);
            
            RVP_PHP_SDK_Test_Harness::static_echo_sha_data($sha_na_na);
            if (isset($in_sha) && $in_sha && ($in_sha != $sha_na_na)) {
                echo('<h4 style="color:red">SHA Mismatch!</h4>');
                $in_test_harness_instance->write_log_entry('Payload Test. Confirm Payload SHA.', $test_count++, false);
                return false;
            } else {
                $in_test_harness_instance->write_log_entry('Payload Test. Confirm Payload SHA.', $test_count++, true);
            }
            
            $type = $payload['type'];
            
            if (isset($type) && $type) {
                $types = explode('/', $type);
                $main_type = strtolower($types[0]);
                $specific_type = strtolower($types[1]);
                switch($main_type) {
                    case 'image':
                        echo('<h4>This is a '.$specific_type.' image.</h4>');
                        
                        if ('tiff' == $specific_type) {
                            echo('<h4>We Cannot Display A TIFF Image.</h4>');
                        } else {
                            run_test_30_harness_get_payload_tests_display_image($payload['data'], $payload['type']);
                        }
                        break;
                        
                    case 'audio':
                        echo('<h4>This is a '.$specific_type.' audio track.</h4>');
                        run_test_30_harness_get_payload_tests_embed_audio($payload['data'], $specific_type, $object->id());
                        break;
                        
                    case 'application':
                        if ($specific_type == 'epub+zip') {
                            echo('<h4>This is a '.$specific_type.' EPUB book.</h4>');
                            echo('<h4>We Cannot Display An EPUB Book.</h4>');
                        } else {
                            echo('<h4 style="color:red">Unknown Payload Type ('.$type.')!</h4>');
                            $in_test_harness_instance->write_log_entry('Payload Test. Type Test.', $test_count++, false);
                            return false;
                        }
                        break;
                        
                    case 'video':
                        echo('<h4>This is a '.$specific_type.' video track.</h4>');
                        run_test_30_harness_get_payload_tests_embed_video($payload['data'], $specific_type, $object->id());
                        break;
                    
                    case 'text':
                        echo('<h4>This is a '.$specific_type.' text dump.</h4>');
                        run_test_30_harness_get_payload_tests_display_text($payload['data']);
                        break;
                        
                    default:
                        echo('<h4 style="color:red">Unknown Payload Type ('.$type.')!</h4>');
                        $in_test_harness_instance->write_log_entry('Payload Test. Type Test.', $test_count++, false);
                        return false;
                }
            } else {
                echo('<h4 style="color:red">No payload Type!</h4>');
                $in_test_harness_instance->write_log_entry('Payload Test. Confirm Payload Type Present.', $test_count++, false);
                return false;
            }
        } elseif ($has_payload) {
            echo('<h4 style="color:red">No payload, where one was expected!</h4>');
            $in_test_harness_instance->write_log_entry('Payload Test. Confirm Payload Present.', $test_count++, false);
            return false;
        } else {
            echo('<h4 style="color:red">Payload Present, Where One Was Not Expected!</h4>');
            $in_test_harness_instance->write_log_entry('Payload Test. Confirm Payload NOT Present.', $test_count++, false);
            return false;
        }
    }
    
    return $all_pass;
}

function run_test_30_harness_get_payload_tests_display_text($in_text) {
    $id = uniqid('test-result-');
    echo('<div id="'.$id.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\''.$id.'\')">Display Text</a></h3>');
        echo('<div class="inner_container">');
            echo('<div class="container"><pre>');
                echo(htmlspecialchars($in_text));
            echo('</pre></div>');
        echo('</div>');
    echo('</div>');
}

function run_test_30_harness_get_payload_tests_display_image($in_image_data, $in_image_type) {
    $id = uniqid('test-result-');
    $file_suffix = 'fa';
    switch ($in_image_type) {
        case 'image/jpeg':
            $file_suffix = 'jpg';
            break;
        case 'image/png':
            $file_suffix = 'png';
            break;
        case 'image/gif':
            $file_suffix = 'gif';
            break;
    }
    
    echo('<div id="'.$id.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\''.$id.'\')">Display Image</a></h3>');
        echo('<div class="inner_container">');
            echo('<div class="container">');
                $fname = 'run_test_30_'.$id.'.'.$file_suffix;
                file_put_contents(dirname(dirname(__FILE__)).'/tmp/'.$fname, $in_image_data);
                
                echo('<div class="image_display_div"><img src="tmp/'.$fname.'" title="Image Payload" alt="Image Payload" style="max-width:100%" /></div>');
            echo('</div>');
        echo('</div>');
    echo('</div>');
}

function run_test_30_harness_get_payload_tests_embed_audio($in_audio_data, $in_audio_type, $in_id) {    
    $suffix = '';
    
    switch($in_audio_type) {
        case 'webm':
            $suffix = 'webm';
            break;
            
        case 'ogg':
            $suffix = 'ogg';
            break;
            
        case 'mpeg':
            $suffix = 'mpg';
            break;
            
        case 'flac':
        case 'x-flac':
            $suffix = 'flac';
            break;
            
        case 'wave':
        case 'wav':
        case 'x-wav':
        case 'x-pn-wav':
            $suffix = 'wav';
            break;
            
        default:
            echo('<h4 style="color:red">Unsupported Audio Format Type ('.$in_audio_type.')!</h4>');
            return false;
    }
    
    $tmp_file_url = "tmp/run_test_30_harness_get_payload_tests_embed_audio.temp.$in_id.$suffix";
    $tmp_file = dirname(dirname(__FILE__)).'/'.$tmp_file_url;
    
    if (file_exists($tmp_file)) {
        unlink($tmp_file);
    }
    
    $handle = fopen($tmp_file, 'w');
    
    if ($handle) {
        fwrite($handle, $in_audio_data);
        fclose($handle);
    } else {
        echo('<h4 style="color:red">Cannot Create Temp File ('.$tmp_file.')!</h4>');
        return false;
    }
    
    $id = uniqid('test-result-');
    echo('<div id="'.$id.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\''.$id.'\')">Reveal Audio Player</a></h3>');
        echo('<div class="inner_container">');
            echo('<div class="container">');
                echo('<audio controls>');
                    echo('<source src="'.$tmp_file_url.'" type="audio/'.$in_audio_type.'">');
                    echo('Your browser does not support the audio element.');
                echo('</audio>');
           echo('</div>');
        echo('</div>');
    echo('</div>');
    
    return true;
}

function run_test_30_harness_get_payload_tests_embed_video($in_video_data, $in_video_type, $in_id) {    
    $suffix = '';
    
    switch($in_video_type) {
        case 'webm':
            $suffix = 'webm';
            break;
            
        case 'ogg':
            $suffix = 'ogg';
            break;
            
        case 'mp4':
            $suffix = 'mp4';
            break;
            
        default:
            echo('<h4 style="color:red">Unsupported Video Format Type ('.$in_video_type.')!</h4>');
            return false;
    }
    
    $tmp_file_url = "tmp/run_test_30_harness_get_payload_tests_embed_video.temp.$in_id.$suffix";
    $tmp_file = dirname(dirname(__FILE__)).'/'.$tmp_file_url;
    
    if (file_exists($tmp_file)) {
        unlink($tmp_file);
    }
    
    $handle = fopen($tmp_file, 'w');
    
    if ($handle) {
        fwrite($handle, $in_video_data);
        fclose($handle);
    } else {
        echo('<h4 style="color:red">Cannot Create Temp File ('.$tmp_file.')!</h4>');
        return false;
    }
    
    $id = uniqid('test-result-');
    echo('<div id="'.$id.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\''.$id.'\')">Reveal Video Player</a></h3>');
        echo('<div class="inner_container">');
            echo('<div class="container">');
                echo('<video controls>');
                    echo('<source src="'.$tmp_file_url.'" type="video/'.$in_video_type.'">');
                    echo('Your browser does not support the video element.');
                echo('</video>');
           echo('</div>');
        echo('</div>');
    echo('</div>');
    
    return true;
}
?>