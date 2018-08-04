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
defined('__EMPTY_SHA__') or define('__EMPTY_SHA__', '8739602554c7f3241958e3cc9b57fdecb474d508');
defined('__WORTH_ENOUGH_KEY__') or define('__WORTH_ENOUGH_KEY__', 'basalt-test-0171: Worth Enough');
defined('__BW_KEY__') or define('__BW_KEY__', 'basalt-test-0171: Brown And Williamson Phone Message');
defined('__CRICKETS_ID__') or define('__CRICKETS_ID__', 1740);
defined('__TJ_ID__') or define('__TJ_ID__', 1737);
defined('__PETE_ID__') or define('__PETE_ID__', 1741);
defined('__WP_ID__') or define('__WP_ID__', 1739);
defined('__SHADOW_ID__') or define('__SHADOW_ID__', 1735);
defined('__MUSK_ID__') or define('__MUSK_ID__', 1743);
defined('__COM_ID__') or define('__COM_ID__', 1744);

function run_test_17_harness_get_payload_tests($test_harness_instance) {
    $all_pass = true;
    $test_count = $test_harness_instance->test_count + 1;
    
    if (isset($test_harness_instance->sdk_instance)) {
        if ($test_harness_instance->sdk_instance->valid()) {
            $thing1 = $test_harness_instance->sdk_instance->get_thing_info(__WORTH_ENOUGH_KEY__);
            
            if (isset($thing1) && ($thing1 instanceof RVP_PHP_SDK_Thing)) {
                $test_harness_instance->write_log_entry('Payload Test. Confirm Fetch Object for String Key \''.__WORTH_ENOUGH_KEY__.'\'.', $test_count++, true);
                $sha = 'fb536a96f2f652d967153a0829d9fc482325a96c';
                $all_pass &= run_test_17_harness_get_payload_tests_load_1_payload($test_harness_instance, $thing1, $sha, $test_count);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Get Thing By String Key', $test_count++, false);
                echo('<h4 style="color:red">Cannot Fetch the Thing By Its String Key!</h4>');
            }
            
            $sha = '056991f9381513578bf06b9fc8bc5ce4bf3f216d';
            $all_pass &= run_test_17_harness_get_payload_tests_load_1_payload($test_harness_instance, __WP_ID__, $sha, $test_count);
            
            $thing2 = $test_harness_instance->sdk_instance->get_thing_info(__BW_KEY__);
            
            if (isset($thing1) && ($thing1 instanceof RVP_PHP_SDK_Thing)) {
                $test_harness_instance->write_log_entry('Payload Test. Confirm Fetch Object for String Key \''.__BW_KEY__.'\'.', $test_count++, true);
                $sha = '6dc601872e630ef7136d62a78c03790a4518be2e';
                $all_pass &= run_test_17_harness_get_payload_tests_load_1_payload($test_harness_instance, $thing2, $sha, $test_count);
            } else {
                $all_pass = false;
                $test_harness_instance->write_log_entry('Get Thing By String Key', $test_count++, false);
                echo('<h4 style="color:red">Cannot Fetch the Thing By Its String Key!</h4>');
            }
            
            $sha = 'f4a78392a63f6674ddb275eb8878fe6faf9c647d';
            $all_pass &= run_test_17_harness_get_payload_tests_load_1_payload($test_harness_instance, __CRICKETS_ID__, $sha, $test_count);
            
            $sha = 'f6bab2a827a544943b8b70245c3e380d92ccc1b6';
            $all_pass &= run_test_17_harness_get_payload_tests_load_1_payload($test_harness_instance, __TJ_ID__, $sha, $test_count);
            
            $sha = 'fe417287d9a82d3f4e4c4c8a6aa78ee52d96bb21';
            $all_pass &= run_test_17_harness_get_payload_tests_load_1_payload($test_harness_instance, __PETE_ID__, $sha, $test_count);
            
            $sha = 'd1f54c5780cd894b722d21985eb523b979eb9bdf';
            $all_pass &= run_test_17_harness_get_payload_tests_load_1_payload($test_harness_instance, __SHADOW_ID__, $sha, $test_count);
            
            $sha = '551e2d5e7dea8abed9c884aa6d23de324a53e2b8';
            $all_pass &= run_test_17_harness_get_payload_tests_load_1_payload($test_harness_instance, __MUSK_ID__, $sha, $test_count);
            
            $sha = 'e573b256d891488b35c5c03b61e3c5c9bdaf830b';
            $all_pass &= run_test_17_harness_get_payload_tests_load_1_payload($test_harness_instance, __COM_ID__, $sha, $test_count);
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
        echo('<h4 style="color:green">TEST SUCCESSFUL!</h4>');
    }
    
    $test_harness_instance->test_count = $test_count;
    
    return $all_pass;     
}

function run_test_17_harness_get_payload_tests_load_1_payload($in_test_harness_instance, $in_id, $in_sha, &$test_count, $has_payload = true) {
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
            
            $sha_na_na = sha1(serialize($payload));
            
            echo('<p><strong>SHA:</strong> <big><code>'.htmlspecialchars(print_r($sha_na_na, true)).'</code></big></p>');
            
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
                            run_test_17_harness_get_payload_tests_display_image($payload['data'], $payload['type']);
                        }
                        break;
                        
                    case 'audio':
                        echo('<h4>This is a '.$specific_type.' audio track.</h4>');
                        run_test_17_harness_get_payload_tests_embed_audio($payload['data'], $specific_type, $object->id());
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
                        run_test_17_harness_get_payload_tests_embed_video($payload['data'], $specific_type, $object->id());
                        break;
                    
                    case 'text':
                        echo('<h4>This is a '.$specific_type.' text dump.</h4>');
                        run_test_17_harness_get_payload_tests_display_text($payload['data']);
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

function run_test_17_harness_get_payload_tests_display_text($in_text) {
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

function run_test_17_harness_get_payload_tests_display_image($in_image_data, $in_image_type) {
    $id = uniqid('test-result-');
    echo('<div id="'.$id.'" class="inner_closed">');
        echo('<h3 class="inner_header"><a href="javascript:toggle_inner_state(\''.$id.'\')">Display Image</a></h3>');
        echo('<div class="inner_container">');
            echo('<div class="container">');
                echo('<div class="image_display_div"><img src="data:'.$in_image_type.';base64,'.base64_encode($in_image_data).'" title="Image Payload" alt="Image Payload" style="max-width:100%" /></div>');
            echo('</div>');
        echo('</div>');
    echo('</div>');
}

function run_test_17_harness_get_payload_tests_embed_audio($in_audio_data, $in_audio_type, $in_id) {    
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
    
    $tmp_file_url = "tmp/run_test_17_harness_get_payload_tests_embed_audio.temp.$in_id.$suffix";
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

function run_test_17_harness_get_payload_tests_embed_video($in_video_data, $in_video_type, $in_id) {    
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
    
    $tmp_file_url = "tmp/run_test_17_harness_get_payload_tests_embed_video.temp.$in_id.$suffix";
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