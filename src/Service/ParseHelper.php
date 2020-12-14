<?php

namespace App\Service;

/**
 * Class ParseHelper
 * @package App\Service
 */
class ParseHelper
{
    /**
     * Converts an array of Entity objects to an array of Entity arrays
     * @param $arr
     * @return array
     */
    public function entitiesToArray($arr)
    {
        $result = [];
        foreach ($arr as $row) {
            $result[] = $row->toArray();
        }

        return $result;
    }
}