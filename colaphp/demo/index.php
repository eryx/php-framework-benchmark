<?php
if (isset($_GET['debug'])) {
    xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
    // System Start Time
    define('START_TIME', microtime(true));
    // System Start Memory
    define('START_MEMORY_USAGE', memory_get_usage());
}

error_reporting(E_ALL);
ini_set('display_errors', 'on');

date_default_timezone_set('Asia/Shanghai');

require '../Cola/Cola.php';

$cola = Cola::getInstance();


//$benchmark = new Cola_Com_Benchmark();

$cola->boot()->dispatch();

//echo "<br />cost:", $benchmark->cost(), 's';


if (!isset($_GET['debug'])) {
    die();
}

$xhprof_data = xhprof_disable();

echo "Page rendered in <b>"
    . round((microtime(true) - START_TIME), 5) * 1000 ." ms</b>, taking <b>"
    . round((memory_get_usage() - START_MEMORY_USAGE) / 1024, 2) ." KB</b>";
$f = get_included_files();
echo ", include files: ".count($f);

$XHPROF_ROOT = realpath(dirname(__FILE__) .'/../..');
include_once $XHPROF_ROOT . "/xhprof/xhprof_lib/utils/xhprof_lib.php";
include_once $XHPROF_ROOT . "/xhprof/xhprof_lib/utils/xhprof_runs.php";

// save raw data for this profiler run using default
// implementation of iXHProfRuns.
$xhprof_runs = new XHProfRuns_Default();

// save the run under a namespace "xhprof_foo"
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");

echo ", xhprof <a href=\"http://xhprof.pfb.example.com/xhprof_html/index.php?run=$run_id&source=xhprof_foo\">url</a>";
