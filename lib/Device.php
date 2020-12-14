<?php


class Device {

    private $device;
    private $connectedTo;

    public function __construct(string $device) {
        $this->device = $device;
        $this->connectedTo = [];
    }

    public function addConnection(string $connection, int $weight): array {
        $this->connectedTo[$connection] = $weight;
        return $this->connectedTo;
    }

    public function getDeviceDetail(): array {
        return $this->connectedTo;
    }
    public function getName(): string {
        return $this->device;
    }

    public function getNeighbours(): array {
        return $this->connectedTo;
    }

    public function getNearestNeighbours(int $nodeNumber): ?string {
        $neighbours = $this->getNeighbours();

        $lowestLatency = [];
        if (!empty ($neighbours)) {
            foreach ($neighbours as $nodeName => $latency) {
                $lowestLatency[$nodeName] = $latency;
            }
        }

        asort($lowestLatency);
        $lowestLatencyArr = array_keys($lowestLatency);

        return !empty($lowestLatencyArr[$nodeNumber]) ? $lowestLatencyArr[$nodeNumber] : null;
    }

}