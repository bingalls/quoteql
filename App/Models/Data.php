<?php

namespace App\Models;

class Data
{
    /** @var array<string> */
    private $data;

    /**
     * Data constructor.
     * @param string $jsonFile format: {"data":[author:*, year:*, text:*]}
     */
    public function __construct(string $jsonFile)
    {
        $json = file_get_contents($jsonFile) ?: '{}';
        $this->data = json_decode($json, false)->data;
    }

    /**
     * Group of random quotes, suitable for printing.
     * @param int $count
     * @return array<string>
     */
    public function fetchPage(int $count): array
    {
        if (($count < 1) || ($count > count($this->data))) {
            return [];
        }
        $result = [];
        while ($count--) {
            $result[]= $this->fetchRandom()[0];
        }
        return $result;
    }

    /**
     * @return array<string>
     */
    public function fetchRandom(): array
    {
        $index = 0;
        try {
            $index = random_int(0, count($this->data) - 1);
        } catch (\Exception $ex) {
            // TODO log $ex
        }
        return [$this->data[$index]];
    }
}
