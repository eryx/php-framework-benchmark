#!/usr/bin/php

<?php

$opt = getopt("c:n:");
$gc = isset($opt['c']) ? $opt['c'] : 100;
$gn = isset($opt['n']) ? $opt['n'] : 30000;

$a = array('ci','yii','micromvc4','yaf','zf','zf2','slim','laravel','cakephp','symfony2');
//$a = array('micromvc4','yaf','slim');
$r = array();
$output = '';
$mt = array();

for ($i = 0; $i < 3; $i++) {
    
    shuffle($a);
    
    foreach ($a as $v) {
        
        shell_exec("/etc/init.d/apache2 restart");
        sleep(60);
        do {
            $loadavg = strstr(shell_exec('cat /proc/loadavg'), ' ', true);
        } while ($loadavg < 0.05);
        
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
            $r[$v][] = array($mat[1], $loadavg, $memuse, $time, $funcal, $files);
        }
    }
}

$output .= sprintf("%12s QPS, LOAD, MEM(KB), TIME(ms); functions, include files\n", 'framework');
foreach ($r as $k => $v) {
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
    $output .= sprintf("%8d,%5.2f,%7.2f,%6.2f;  %5d,%5d\n", $rqsvg/3, $loadavg/3, $memuse/3, $time/3, $funcal, $files);
}

file_put_contents("./result-".date("Ymd")."/ab-c{$gc}-n{$gn}.txt", $output);
echo $output;


