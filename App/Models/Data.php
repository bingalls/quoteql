<?php

namespace App\Models;

class Data
{
    /** @var array<string> */
    private $data = [];

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
     * @param int $index
     * @return array<string>
     */
    public function fetch(int $index): array
    {
        if (($index < 0) || ($index > count($this->data))) {
            return [];
        }

        return [$this->data[$index]];
    }

    /**
     * @return array<string>
     */
    public function fetchAll(): array
    {
        return $this->data;
    }
}
