<?php

namespace App\Services;

use App\Models\Station\MetroStation;
use App\Models\Station\TimeStation;
use App\Models\Station\TimeTransfer;
use SplPriorityQueue;
use Illuminate\Support\Facades\DB;

class MetroStationService extends BaseService
{
    public function __construct()
    {
        parent::__construct(MetroStation::class);
    }

    public function findBestPaths($startStationId, $endStationId)
    {
        // Найдем все возможные пути
        $allPaths = $this->findAllPaths($startStationId, $endStationId);

        // Отсортировать пути по времени
        usort($allPaths, function ($a, $b) {
            return $a['total_time'] <=> $b['total_time'];
        });

        // Возврат лучшего пути. 
        // Планировалось несколько на бывор, но, видимо, в предоставленных данных только по 1 пути.
        return array_slice($allPaths, 0, 1);
    }

    private function findAllPaths($startStationId, $endStationId)
    {
        // Инициализация
        $dist = [];
        $prev = [];
        $queue = [];

        $stations = MetroStation::all();

        foreach ($stations as $station) {
            $dist[$station->id] = INF;
            $prev[$station->id] = null;
            $queue[$station->id] = INF;
        }

        $dist[$startStationId] = 0;
        $queue[$startStationId] = 0;

        while (!empty($queue)) {
            // Найти станцию с минимальным расстоянием
            $u = array_search(min($queue), $queue);
            unset($queue[$u]);

            if ($u == $endStationId) {
                break;
            }

            // Получить соседей и время до них
            $neighbors = $this->getNeighbors($u);

            foreach ($neighbors as $neighbor) {
                $alt = $dist[$u] + $neighbor['time'];
                if ($alt < $dist[$neighbor['id']]) {
                    $dist[$neighbor['id']] = $alt;
                    $prev[$neighbor['id']] = $u;
                    $queue[$neighbor['id']] = $alt;
                }
            }
        }

        // Сбор всех возможных путей
        $allPaths = [];
        $this->collectPaths($prev, $endStationId, [], $allPaths);

        // Формирование результатов
        $paths = [];
        foreach ($allPaths as $path) {
            $totalTime = 0;
            $route = [];
            foreach ($path as $stationId) {
                $station = MetroStation::find($stationId);
                $route[] = $station->toArray();
            }

            $transfers = [];
            for ($i = 0; $i < count($route) - 1; $i++) {
                $time = $this->getTimeBetweenStations($route[$i]['id'], $route[$i + 1]['id']);
                $totalTime += $time;

                // Проверка на пересадки
                if ($i > 0 && $i < count($route) - 1) {
                    $transfer = $this->getTransfer($route[$i]['id'], $route[$i + 1]['id']);
                    if ($transfer) {
                        $transfers[] = [
                            'id' => $transfer->id,
                            'time' => $transfer->time,
                            'from' => $route[$i],
                            'to' => $route[$i + 1]
                        ];
                    }
                }
            }

            $paths[] = [
                'total_time' => $totalTime,
                'stations' => array_slice($route, 1, -1), // Исключаем начальную и конечную станции
                'transfers' => $transfers,
                'departure_station' => $route[0],
                'arrival_station' => end($route)
            ];
        }

        return $paths;
    }

    private function getNeighbors($stationId)
    {
        $neighbors = [];

        $timeStations = TimeStation::where('st1_id', $stationId)
            ->orWhere('st2_id', $stationId)
            ->get();

        foreach ($timeStations as $timeStation) {
            if ($timeStation->st1_id == $stationId) {
                $neighbors[] = [
                    'id' => $timeStation->st2_id,
                    'time' => $timeStation->time
                ];
            } else {
                $neighbors[] = [
                    'id' => $timeStation->st1_id,
                    'time' => $timeStation->time
                ];
            }
        }

        $timeTransfers = TimeTransfer::where('st1_id', $stationId)
            ->orWhere('st2_id', $stationId)
            ->get();

        foreach ($timeTransfers as $timeTransfer) {
            if ($timeTransfer->st1_id == $stationId) {
                $neighbors[] = [
                    'id' => $timeTransfer->st2_id,
                    'time' => $timeTransfer->time
                ];
            } else {
                $neighbors[] = [
                    'id' => $timeTransfer->st1_id,
                    'time' => $timeTransfer->time
                ];
            }
        }

        return $neighbors;
    }

    private function collectPaths($prev, $stationId, $path, &$allPaths)
    {
        if ($stationId === null) {
            $allPaths[] = array_reverse($path);
            return;
        }
        $path[] = $stationId;
        $this->collectPaths($prev, $prev[$stationId], $path, $allPaths);
    }

    private function getTimeBetweenStations($st1, $st2)
    {
        $timeStation = TimeStation::where(function ($query) use ($st1, $st2) {
            $query->where('st1_id', $st1)->where('st2_id', $st2);
        })->orWhere(function ($query) use ($st1, $st2) {
            $query->where('st1_id', $st2)->where('st2_id', $st1);
        })->first();

        if ($timeStation) {
            return $timeStation->time;
        }

        $timeTransfer = TimeTransfer::where(function ($query) use ($st1, $st2) {
            $query->where('st1_id', $st1)->where('st2_id', $st2);
        })->orWhere(function ($query) use ($st1, $st2) {
            $query->where('st1_id', $st2)->where('st2_id', $st1);
        })->first();

        return $timeTransfer ? $timeTransfer->time : INF;
    }

    private function getTransfer($st1, $st2)
    {
        return TimeTransfer::where(function ($query) use ($st1, $st2) {
            $query->where('st1_id', $st1)->where('st2_id', $st2);
        })->orWhere(function ($query) use ($st1, $st2) {
            $query->where('st1_id', $st2)->where('st2_id', $st1);
        })->first();
    }
}
