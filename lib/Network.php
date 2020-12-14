<?php

class Network {

    private $totalDevices = 0;
    private $devicesList = [];
    private $total_cost = 0 ;

    public function addConnection(string $deviceA, string $deviceB, int $weight)
    {
        $this->addDevice($deviceA);
        $this->addDevice($deviceB);

        return $this->devicesList[$deviceA]->addConnection($deviceB, $weight);
    }

    public function addDevice(string $device) {
        if (isset($this->devicesList[$device])) {
            return;
        }
        $this->totalDevices += 1;
        $this->devicesList[$device] = new Device($device);
    }

    public function getAllDevices(): array {
        $allDevices = [];
        if (!empty($this->devicesList)) {
            foreach ($this->devicesList as $device) {
                $allDevices[$device->getName()] = $device->getDeviceDetail();
            }
        }
        return $allDevices;
    }

    public function search(string $from, string $to) {
        $devices  = $this->getAllDevices();

        if (!isset($devices[$from]) || !isset($devices[$to])) {
            //path does not exist
            return false;
        }
        $distance = 0;
        $isFound = false;
        $currentNode = $from;
        $traversedNodes = [];
        $emptyNodes = 0;

        while (!$isFound) {
            $traversedNodes[] = $currentNode;
            $allNeighbours = $this->devicesList[$currentNode]->getNeighbours();
            $lowestLatencyNode = $this->devicesList[$currentNode]->getNearestNeighbours($emptyNodes);

            if ($emptyNodes > 0) {
                $emptyNodes = 0;
            }

            if (empty($lowestLatencyNode)) {
                // if no path detected, then go back to previous node
                $repeatNode = count($traversedNodes) - 2 - $emptyNodes;
                $distance = 0;

                if (in_array($currentNode, $traversedNodes)) {
                    $currentNode = $traversedNodes[$repeatNode];
                    $emptyNodes++;
                    continue;
                } else {
                    // current node is never seen, dead end
                    // this block executes if no path found
                    return false;
                }
            } else {
                $currentNode = $lowestLatencyNode;
            }

            if (isset($allNeighbours[$to])) {
                // got the node in a neighbour node, so exiting while loop from here
                $traversedNodes[] = $to;
                $distance += $allNeighbours[$to];
                $isFound = true;
            } else if ($lowestLatencyNode == $to) {
                // got the node here, so exiting while loop from here
                $traversedNodes[] = $to;
                $isFound = true;
            } else {
                $distance += $allNeighbours[$lowestLatencyNode];
            }
        }
        return [
            'path' => $traversedNodes,
            'distance' => $distance
        ];
    }

}