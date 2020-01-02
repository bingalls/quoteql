<?php
use PHPUnit\Framework\TestCase;

use App\Cfg;
use App\Models\Data;

class DataTest extends TestCase implements Cfg
{
    private const NumQuotes = 3;     // entries in quotes.json

    public function testFetchAll(): void
    {
        $data = new Data(Cfg::DATA);
        static::assertCount(static::NumQuotes, $data->fetchAll());
    }

    public function testFetchOne(): void
    {
        $data = new Data(Cfg::DATA);
        static::assertEquals('Lionel Ruby', $data->fetch(0)[0]->author);
    }

    public function testFetchMissing(): void
    {
        $data = new Data(Cfg::DATA);
        static::assertEmpty($data->fetch(static::NumQuotes+1));
        static::assertEmpty($data->fetch(-1));
    }
}
