<?php

namespace App\Services;

use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;

class PlotService
{
    public static function processPolygon($polygonInput): Polygon
    {
        $polygonArray = is_string($polygonInput)
            ? json_decode($polygonInput, true)
            : $polygonInput;

        if (isset($polygonArray[0][0]) && is_numeric($polygonArray[0][0])) {
            $polygonArray = [$polygonArray];
        }

        foreach ($polygonArray as &$ring) {
            $first = $ring[0];
            $last = end($ring);
            if ($first !== $last) {
                $ring[] = $first;
            }
        }
        unset($ring);

        $lineStrings = array_map(function ($ring) {
            $points = array_map(fn($coords) => Point::make($coords[0], $coords[1]), $ring);
            return LineString::make($points);
        }, $polygonArray);

        return Polygon::make($lineStrings);
    }
}