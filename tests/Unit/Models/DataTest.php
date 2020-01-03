<?php
use PHPUnit\Framework\TestCase;

use App\Cfg;
use App\Models\Data;

class DataTest extends TestCase implements Cfg
{
    private const NumQuotes = 10;     // entries in quotes.json

    public function testFetchAll(): void
    {
        $data = new Data(Cfg::DATA);
        static::assertCount(static::NumQuotes, $data->fetchPage(static::NumQuotes));
    }

    public function testFetchOne(): void
    {
        $data = new Data(Cfg::DATA);
        $year = $data->fetchRandom()[0]->year;
        static::assertGreaterThanOrEqual(1900, $year);
        static::assertLessThan(2020, $year);
    }

    public function testFetchMissing(): void
    {
        $data = new Data(Cfg::DATA);
        static::assertEmpty($data->fetchPage(static::NumQuotes + 1));
        static::assertEmpty($data->fetchPage(0));
    }
}
