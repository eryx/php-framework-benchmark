#!/usr/bin/php

<?php

if (!function_exists("xhprof_enable"))
    die("php5-xhprof not found\n");
if (!function_exists("apc_add"))
    die("php5-apc not found\n");
if (!function_exists("curl_close"))
    die("php5-curl not found\n");
if (!function_exists("imagecopy"))
    die("php5-gd not found\n");
if (!class_exists("Locale"))
    die("php5-intl not found\n");

$opt = getopt("c:n:");
$gc = isset($opt['c']) ? $opt['c'] : 100;
$gn = isset($opt['n']) ? $opt['n'] : 30000;

$al = array(
    'symfony2' => 'Symfony 2.0.6',
    'zf' => 'Zend Framework 1.11.11',
    'zf2' => 'Zend Framework 2.0.0-beta1',
    'cakephp' => 'CakePHP 2.0.4',
    'ci' => 'CodeIgniter 2.1.0',
    'yii' => 'Yii Framework 1.1.8',
    'slim' => 'Slim 1.5',
    'laravel' => 'Laravel 2.0.2',
    'micromvc4' => 'MicroMVC 4.0.0',
    'yaf' => 'Yaf 2.1.3-beta',
);
$a = array_keys($al);

//
$rs = array();
$output = '';
$count = 3;
for ($i = 0; $i < $count; $i++) {
    
    foreach ($a as $v) {
        
        shell_exec("/etc/init.d/apache2 restart");        
        do {
            sleep(60);
            $loadavg = strstr(shell_exec('cat /proc/loadavg'), ' ', true);
        } while ($loadavg > 0.05);
        
        echo "Testing $v\n";
        /** Memuse/Time/fun-calls/fun-map **/
        $memuse = 0;
        $time   = 0;
        $funcal = 0;
        $files  = 0;
        $o = shell_exec("curl -X GET \"http://{$v}.pfb.example.com/?debug=1\""); usleep(300000);// Caching
        $o = shell_exec("curl -X GET --ignore-content-length \"http://{$v}.pfb.example.com/?debug=1\"");
        if (preg_match("/in \<b\>(.*?) ms(.*?)\<b\>(.*?) KB(.*?)files: (.*?),(.*?)\<a href=\"(.*?)\"/",
            $o, $mat)) {
            $memuse = $mat[3];
            $time   = $mat[1];
            $files  = $mat[5];
            $o = shell_exec("curl -X GET \"".urldecode($mat[7])."\"");
            if (preg_match("/Number of Function Calls(.*?)\<td\>(.*?)\<\/td/", $o, $mat2)
                && preg_match("/href=\"(.*?)\"\>\[View Full Callgraph/", $o, $mat3) )
            {
                $funcal = str_replace(array(",", " "), array("", ""), $mat2[2]);
                shell_exec("mkdir -p ./result-".date("Ymd")."/{$v}/");
                copy("http://xhprof.pfb.example.com/".$mat3[1], "./result-".date("Ymd")."/{$v}/funmap{$i}.png");
            }
        }

        /** QPS **/
        $o = shell_exec("ab -c $gc -n $gn -H \"Connection: close\" \"http://{$v}.pfb.example.com/\"");
        if (preg_match("/Requests\ per\ second:\ +(.*?)\[/", $o, $mat)) {
            $loadavg = strstr(shell_exec('cat /proc/loadavg'), ' ', true);
            $rs[$v][] = array($mat[1], $loadavg, $memuse, $time, $funcal, $files);
        }
    }
}

$output .= sprintf("%12s QPS, LOAD, MEM(KB), TIME(ms); functions, include files\n", 'framework');
$rsm = array();
foreach ($rs as $k => $v) {
    $output .= sprintf("%12s ", $k);
    $rqsvg = 0;
    $loadavg = 0;
    $memuse = 0;
    $time   = 0;
    $funcal = 0;
    $files  = 0;
    foreach ($v as $v2) {
        $rqsvg += $v2[0];
        $loadavg += $v2[1];
        $memuse += $v2[2];
        $time += $v2[3];
        $funcal = $v2[4];
        $files  = $v2[5];
        $output .= sprintf("%8d,%5.2f,%7.2f,%6.2f;", $v2[0], $v2[1], $v2[2], $v2[3]);
    }
    $output .= sprintf("%8d,%5.2f,%7.2f,%6.2f;  %5d,%5d\n", 
        $rqsvg/$count, $loadavg/$count, $memuse/$count, $time/$count, $funcal, $files);
    $rsm['qps'][$al[$k]] = intval($rqsvg/$count);
    $rsm['load'][$al[$k]] = round($loadavg/$count, 2);
    $rsm['memuse'][$al[$k]] = round($memuse/$count, 2);
    $rsm['time'][$al[$k]] = round($time/$count, 2);
    $rsm['funcal'][$al[$k]] = $funcal;
    $rsm['files'][$al[$k]] = $files;
}

file_put_contents("./result-".date("Ymd")."/ab-c{$gc}-n{$gn}.txt", $output);
echo $output;

include('./deps/phpgraphlib.php');

foreach ($rsm as $k => $v) {
    switch ($k) {
    case 'qps':
        $graph = new PHPGraphLib(800,450,"./result-".date("Ymd")."/ab-c{$gc}-n{$gn}.png");
        $graph->addData($rsm['qps']);
        $graph->setTitle("ApacheBench (ab -c {$gc} -n {$gn})");
        break;
    case 'load':
        $graph = new PHPGraphLib(800,450,"./result-".date("Ymd")."/loadavg.png");
        $graph->addData($rsm['load']);
        $graph->setTitle("System LoadAvg in 1 Minute (ab -c {$gc} -n {$gn})");
        break;
    case 'memuse':
        $graph = new PHPGraphLib(800,450,"./result-".date("Ymd")."/memory-usage.png");
        $graph->addData($rsm['memuse']);
        $graph->setTitle("Memory Usage (KB)");  
        break;
    case 'files':
        $graph = new PHPGraphLib(800,450,"./result-".date("Ymd")."/number-of-files.png");
        $graph->addData($rsm['files']);
        $graph->setTitle("Number of files been included or required");
        break;
    case 'funcal':
        $graph = new PHPGraphLib(800,450,"./result-".date("Ymd")."/number-of-function-calls.png");
        $graph->addData($rsm['funcal']);
        $graph->setTitle("Number fo function calls");
        break;
    case 'time':
        $graph = new PHPGraphLib(800,450,"./result-".date("Ymd")."/response-time.png");
        $graph->addData($rsm['time']);
        $graph->setTitle("Response Time (Millisecond)");
        break;
    default:
        continue;
    }
    
    $graph->setTitleLocation('left');
    $graph->setBarColor('255,102,51');
    $graph->setDataValues(true);
    $graph->setXValuesHorizontal(true);
    $graph->setupXAxis(20, '');
    $graph->createGraph();
}


