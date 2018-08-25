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
set_time_limit(3600);
require_once(dirname(__FILE__).'/rvp_php_sdk_test_manifest.php');
require_once(dirname(__FILE__).'/rvp_php_sdk_test_harness.class.php');
RVP_PHP_SDK_Test_Harness::clear_out_tmp_dir();
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>BLUE DRAGON PHP SDK Test Harness</title>
        <link rel="shortcut icon" href="icon.png" type="image/png" />
        <style>
            *{margin:0;padding:0}
            body {
                font-family: Arial, San-serif;
                text-align:center;
                }
            
            div.timing_report,
            div.test_report {
                margin-top: 1em;
                margin-bottom: 1em;
                margin-left: 1em;
            }
            
            div.timing_report {
                font-style: italic;
                color: #930;
            }
            
            div.test_report {
                border-radius: 0.5em;
                padding: 0.25em;
                border: 1px dashed black;
            }
            
            div.test_report.good_report {
                background-color: #dfd;
            }
            
            div.test_report.bad_report {
                background-color: #fdd;
            }
            
            div.closed,
            div.open,
            div.inner_closed {
                display: table;
                margin:auto;
            }
            
            div.inner_closed,
            div.inner_open {
                margin-top:0.25em;
                margin-bottom: 0.25em;
            }
            
            div.indent_1 {
                margin-left:1em;
            }
            
            div.main_div {
                margin-top:0.25em;
                margin-bottom: 0.25em;
                margin-left:1em;
                padding: 0.5em;
            }
            
            div.inner_div {
                margin-top:0.25em;
                padding: 0.25em;
            }
            
            .explain {
                font-style: italic;
                display:block;
                margin:0.5em;
                text-align:left;
            }
            
            h1.header {
                font-size: x-large;
            }
            
            h2.header {
                font-size: large;
            }
            
            h2.header a {
                display: block;
            }
            
            div.closed,
            div.open {
                margin-top:0.5em;
                border: 1px solid #009;
                border-radius:0.25em;
                padding: 0.25em;
                min-width:50em;
                text-align:left;
            }
            
            div.open div.container {
                margin:auto;
                display: table;
                text-align:left;
            }
            
            h2.passed,
            div.test-pass div.closed h2 a,
            div.test-pass div.closed h2 a:visited {
                color:green;
            }
            
            h2.failed,
            div.test-fail div.closed h2 a,
            div.test-fail div.closed h2 a:visited {
                color:red;
            }
            
            div.open h3.inner_header a,
            div.open h2 a {
                color:white;
                margin-top: -0.25em;
                margin-right: -0.25em;
                margin-left: -0.25em;
                padding: 0.25em;
                background-color: #009;
                display:block;
                width: 100%;
            }
            
            div.open h3.inner_header a {
                margin-bottom: 0.25em;
            }
            
            div.inner_open h3.inner_header a,
            div.open div.inner_open h3.inner_header.sub_test_header a,
            div.open .inner_open .indent_1 .inner_open h3.inner_header a,
            div.open h2 a {
                border-top-left-radius:0.25em;
                border-top-right-radius:0.25em;
                border-bottom-left-radius:0em;
                border-bottom-right-radius:0em;
                margin-bottom: 0.25em;
            }
            
            div.open .inner_open .indent_1 .inner_open h3.inner_header,
            div.open div.inner_open h3.inner_header.sub_test_header {
                margin-right: -1px;
                margin-left: -1px;
            }
            
            div.open .inner_open .indent_1 .inner_closed h3.inner_header a,
            div.open h3.inner_header a {
                border-radius:0.25em;
            }
            
            div.closed div.container {
                display: none;
            }
            
            h3.inner_header {
                font-size: medium;
                display:table;
                margin:auto;
            }
            
            div.inner_open {
                border:1px dashed #009;
                border-top:none;
                border-radius:0.25em;
                padding: 0.25em;
            }
            
            div.inner_closed h2.inner_header {
            }
            
            div.inner_open h3.inner_header {
                text-align:left;
                display:block;
            }
            
            div.inner_open div.inner_container {
                display: table;
                margin:auto;
            }
            
            div.inner_closed div.inner_container {
                display: none;
            }
            
            div.test-wrapper {
                display: table;
                margin:auto;
                padding: 0.25em;
                margin-top:1em;
            }
            
            div.open {
                background-color: #ffd;
            }
            
            div#tests-wrapped-up {
                margin:auto;
            }
            
            div.tests-displayed {
                margin-top:1em;
            }
            
            div.pass_failimage_display_div {
                padding:2em;
                text-align:center;
            }
            
            div.pass_failimage_display_div img {
                display:block;
                border-radius:2em;
                margin:auto;
            }
            
            div.thermometer-div {
                border-radius: 0.25em;
                border: 2px solid #03f;
                height: 1em;
                width:50%;
                margin:auto;
                margin-top: 1em;
                background-color: #9bf;
            }
            
            div.thermometer-complete-div {
                border: none;
                height: 100%;
                background-color: #03f;
            }
            
        </style>
        <script type="text/javascript" src="ajaxLoader.js"></script>
        <script type="text/javascript">
            var test_array = [<?php
                $tests = [];
                foreach ($rvp_php_sdk_test_manifest as $test) {
                    $tests[] = $test['blurb'];
                }
                $tests = "'".implode("','", $tests)."'";
                
                echo($tests);
            ?>];
            var current_test = 0;
            var start_time =  <?php echo microtime(true); ?>;
            var ajaxLoader = new ajaxLoader();
            
            function set_thermometer(in_completness) {
                var thermometer_complete_div = document.getElementById('thermometer-complete-div');
                thermometer_complete_div.style.width = (in_completness * 100).toString() + '%';
            };
            
            function toggle_main_state(in_id) {
                var item = document.getElementById(in_id);
                
                if ( item.className == 'closed' ) {
                    item.className = 'open';
                } else {
                    item.className = 'closed';
                };
            };
            
            function toggle_inner_state(in_id) {
                var item = document.getElementById(in_id);
                
                if ( item.className == 'inner_closed' ) {
                    item.className = 'inner_open';
                } else {
                    item.className = 'inner_closed';
                };
                
            };
            
            function expose_tests(in_pass) {
                var item = document.getElementById('throbber-container');
                
                if (item) {
                    item.style="display:none";
                };
                
                item = document.getElementById('tests-displayed');
                
                if (item) {
                    item.innerHTML = '<h2 class="' + (in_pass ? 'passed' : 'failed') + '">' + (in_pass ? 'ALL TESTS PASS' : 'TEST FAILURES') + '</pre>';
                };
                
                item = document.getElementById('tests-wrapped-up');
                
                if (item) {
                    item.style="display:block";
                };
            };
            
            function incrementHTML (in_html) {
                document.getElementById('test-results-displayed').innerHTML += in_html;
            };
            
            function runTestCallback (in_response_object) {
                var pass = false;
                var current_total = 0;
                
                if (in_response_object.responseText) {
                    eval('var json_object = ' + in_response_object.responseText + ';');
                    incrementHTML(json_object.html);
                    pass = json_object.pass;
                    current_total = json_object.current_total;
                };
                
                current_test++;
                set_thermometer(current_test / test_array.length);
                
                if (current_test == test_array.length) {
                    expose_tests(pass);
                } else {
                    var last_test = false;
                    
                    if (current_test == (test_array.length - 1)) {
                        last_test = true;
                    };
                    var item = document.getElementById('progress-report');
                    item.innerHTML = 'RUNNING ' + test_array[current_test].toString() + '.';
                    ajaxLoader.ajaxRequest('test-runner.php?' + ('start_index=' + current_test.toString()) + ('&end_index=' + (current_test + 1).toString()) + '&current_total=' + current_total.toString() + '&allpass=' + (pass ? '1' : '0') + (last_test ? '&last_test' : ''), runTestCallback, 'GET');
                };
            };
            
            function runTests () {
                document.getElementById('test-results-displayed').innerHTML = '';
                var item = document.getElementById('progress-report');
                item.innerHTML = 'RUNNING ' + test_array[0].toString() + '.';
                ajaxLoader.ajaxRequest('test-runner.php?first_test&start_index=0&end_index=1&allpass=1&current_total=0', runTestCallback, 'GET');
            };
        </script>
    </head>
    <body>
        <h1 style="text-align:center">BLUE DRAGON PHP SDK TESTS</h1>
        <div style="text-align:center;padding:1em;">
            <div id="throbber-container" style="text-align:center">
                <h3 id="progress-report" style="margin-top:1em"></h3>
                <div class="thermometer-div"><div class="thermometer-complete-div" id="thermometer-complete-div" style="width:0"></div></div>
                <img src="throbber.gif" alt="throbber" style="position:absolute;width:190px;top:50%;left:50%;margin-top:-95px;margin-left:-95px" />
                <img src="icon.png" alt="icon" style="position:absolute;width:128px;top:50%;left:50%;margin-top:-64px;margin-left:-64px" />
            </div>
            <?php
            $start_time = microtime(true);
            ?>
            <div id="tests-wrapped-up" style="display:none">
                <img src="icon.png" style="display:block;margin:auto;width:80px" alt="" />
                <div class="tests-displayed" id="tests-displayed"></div>
                <div id="test-results-displayed"></div>
                <h3 style="margin-top:1em"><a href="./">RETURN TO MAIN ENVIRONMENT SETUP</a></h3>
            </div>
            <script type="text/javascript">
                runTests();
            </script>
        </div>
    </body>
</html>