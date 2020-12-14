<?php

require_once './lib/Network.php';
require_once './lib/Device.php';

if (empty($argv[1])) {
    die('Please specify the csv file like this, --path=filePath.csv');
}
$arguments = explode('=',$argv[1]);
$filePath = $arguments[1];
if (!file_exists($filePath)) {
    die("File $filePath does not exists !");
}
$network = new Network();

$fh = fopen($filePath, 'r');
while ($row = fgetcsv($fh)) {
    if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
        die("File $filePath structure is not valid!");
    }
    // add connection for all devices and their millisecond distance
    $network->addConnection($row[0], $row[1], $row[2]);
}

while (true) {
    $input = readline( PHP_EOL . 'Please enter the nodes and latency'.PHP_EOL);
    $inputParts = explode(' ', $input);

    if (empty($inputParts[0]) || empty($inputParts[1]) || empty($inputParts[2])) {
        echo 'Please enter valid data ';
        continue;
    }

    $distanceDetails = $network->search($inputParts[0], $inputParts[1]);

    if (is_array($distanceDetails)  && $distanceDetails['distance'] < $inputParts[2]) {
        echo "Path found = " .
            implode('=>', $distanceDetails['path']) .
            ', and total time in ms - '.$distanceDetails['distance'];
    } else {
        echo 'Path not found';
    }

}





