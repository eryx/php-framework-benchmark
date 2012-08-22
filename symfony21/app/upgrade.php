<?php

// This script is very conservative in what it uses to avoid breaks at all costs

use Sensio\Bundle\DistributionBundle\Upgrade\Upgrade;
use Symfony\Component\Console\Output\ConsoleOutput;

// We don't use the app autoloader in case it is not up to date
require_once __DIR__.'/../vendor/autoload.php';

$upgrade = new Upgrade();
$output = new ConsoleOutput();

$skeletonDir = __DIR__.'/../vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/skeleton';

$files = array(
    __DIR__.'/console' => $skeletonDir.'/app/console',
    __DIR__.'/autoload.php' => $skeletonDir.'/app/autoload.php',
);

foreach ($files as $file => $skeleton) {
    $upgrade->outputConsoleDiff($output, $file, $skeleton);
}
