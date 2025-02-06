<?php

namespace App\Services;

use Prometheus\CollectorRegistry;
use Prometheus\Exception\MetricsRegistrationException;
use Prometheus\RenderTextFormat;

class PrometheusService
{
    private CollectorRegistry $collectorRegistry;

    public function __construct(CollectorRegistry $registry)
    {
        $this->collectorRegistry = $registry->getDefault();
    }

    public function metrics(): string
    {
        $renderer = new RenderTextFormat();
        $result = $renderer->render($this->collectorRegistry->getMetricFamilySamples());
        return $result;

    }

    /**
     * @throws MetricsRegistrationException
     */
    public function createTestOrder($count = 1): void
    {
        $counter = $this->collectorRegistry->getOrRegisterCounter(
            'orders',
            'count',
            'Number of Orders', [ 'category' ]
        );

        $counter->incBy( $count, [ 'category' ] );

    }

    /**
     * @throws MetricsRegistrationException
     */
    public function collectSystemMetrics(): void
    {
        // CPU Usage
        $cpuGauge = $this->collectorRegistry->getOrRegisterGauge(
            'system',
            'cpu_usage_percent',
            'CPU Usage in Percentage',
            ['core']
        );

        $cpuUsage = sys_getloadavg(); // [1 min, 5 min, 15 min]
        $cpuGauge->set($cpuUsage[0], ['1_min']);
        $cpuGauge->set($cpuUsage[1], ['5_min']);
        $cpuGauge->set($cpuUsage[2], ['15_min']);

        // RAM Usage
        $memoryGauge = $this->collectorRegistry->getOrRegisterGauge(
            'system',
            'memory_usage_bytes',
            'Memory Usage in Bytes',
            ['type']
        );

        $memInfo = file_get_contents('/proc/meminfo');
        preg_match('/MemTotal:\s+(\d+)/', $memInfo, $totalMem);
        preg_match('/MemFree:\s+(\d+)/', $memInfo, $freeMem);
        preg_match('/Buffers:\s+(\d+)/', $memInfo, $buffers);
        preg_match('/Cached:\s+(\d+)/', $memInfo, $cached);
        preg_match('/SwapTotal:\s+(\d+)/', $memInfo, $totalSwap);
        preg_match('/SwapFree:\s+(\d+)/', $memInfo, $freeSwap);

        $totalMem = $totalMem[1] * 1024;
        $freeMem = ($freeMem[1] + $buffers[1] + $cached[1]) * 1024;
        $usedMem = $totalMem - $freeMem;
        $totalSwap = $totalSwap[1] * 1024;
        $freeSwap = $freeSwap[1] * 1024;
        $usedSwap = $totalSwap - $freeSwap;

        $memoryGauge->set($totalMem, ['total']);
        $memoryGauge->set($usedMem, ['used']);
        $memoryGauge->set($freeMem, ['free']);
        $memoryGauge->set($totalSwap, ['swap_total']);
        $memoryGauge->set($usedSwap, ['swap_used']);
        $memoryGauge->set($freeSwap, ['swap_free']);

        // Disk Usage
        $diskGauge = $this->collectorRegistry->getOrRegisterGauge(
            'system',
            'disk_usage_bytes',
            'Disk Usage in Bytes',
            ['path', 'type']
        );

        $diskTotal = disk_total_space("/");
        $diskFree = disk_free_space("/");
        $diskUsed = $diskTotal - $diskFree;

        $diskGauge->set($diskTotal, ["/", "total"]);
        $diskGauge->set($diskUsed, ["/", "used"]);
        $diskGauge->set($diskFree, ["/", "free"]);

        // Network Usage
        $networkGauge = $this->collectorRegistry->getOrRegisterGauge(
            'system',
            'network_bytes',
            'Network usage in Bytes',
            ['interface', 'direction']
        );

        $netStat = file_get_contents('/proc/net/dev');
        preg_match_all('/\s*(\w+):\s*(\d+)\s*(\d+)/', $netStat, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $interface = $match[1];
            $receivedBytes = (int)$match[2];
            $transmittedBytes = (int)$match[3];

            $networkGauge->set($receivedBytes, [$interface, 'received']);
            $networkGauge->set($transmittedBytes, [$interface, 'transmitted']);
        }

        // System Uptime
        $uptimeGauge = $this->collectorRegistry->getOrRegisterGauge(
            'system',
            'uptime_seconds',
            'System uptime in seconds'
        );

        $uptime = (int)file_get_contents('/proc/uptime');
        $uptimeGauge->set($uptime);

        // Записываем метрики в хранилище Prometheus
        $this->collectorRegistry->getMetricFamilySamples();
    }
}
