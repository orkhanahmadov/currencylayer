<?php

namespace Orkhanahmadov\Currencylayer\Tests\CurrencylayerClient;

use GuzzleHttp\Psr7\Response;
use Orkhanahmadov\Currencylayer\Data\Change;
use Orkhanahmadov\Currencylayer\Tests\TestCase;

class ChangeTest extends TestCase
{
    public function test()
    {
        $this->guzzler
            ->expects($this->once())
            ->get(self::API_HTTP_URL.'change')
            ->withQuery([
                'access_key' => self::FAKE_ACCESS_KEY,
                'start_date' => '2005-01-01',
                'end_date'   => '2010-01-01',
                'source'     => 'USD',
                'currencies' => 'AUD,EUR,MXN',
            ])
            ->willRespond(new Response(200, [], $this->jsonFixture('change')));

        $data = $this->client->source('USD')->currency('AUD', 'EUR', 'MXN')->change('2005-01-01', '2010-01-01');

        $this->assertInstanceOf(Change::class, $data);
        $this->assertSame('USD', $data->source());
        $this->assertCount(3, $data->quotes());
        $this->assertSame('2005-01-01', $data->startDate()->format('Y-m-d'));
        $this->assertSame('2010-01-01', $data->endDate()->format('Y-m-d'));
        $this->assertSame(1.281236, $data->startRate('AUD'));
        $this->assertSame(1.108609, $data->endRate('AUD'));
        $this->assertSame(-0.1726, $data->amount('AUD'));
        $this->assertSame(-13.4735, $data->percentage('AUD'));
        $this->assertSame(11.149362, $data->startRate('MXN'));
        $this->assertSame(13.108757, $data->endRate('MXN'));
        $this->assertSame(1.9594, $data->amount('MXN'));
        $this->assertSame(17.5741, $data->percentage('MXN'));
    }

    public function testWithDateTimeInterface()
    {
        $this->guzzler
            ->expects($this->once())
            ->get(self::API_HTTP_URL.'change')
            ->withQuery([
                'access_key' => self::FAKE_ACCESS_KEY,
                'start_date' => '2005-01-01',
                'end_date'   => '2010-01-01',
                'source'     => 'USD',
                'currencies' => 'AUD,EUR,MXN',
            ])
            ->willRespond(new Response(200, [], $this->jsonFixture('change')));

        $data = $this->client->source('USD')->currency('AUD', 'EUR', 'MXN')
            ->change(new \DateTimeImmutable('2005-01-01'), new \DateTimeImmutable('2010-01-01'));

        $this->assertInstanceOf(Change::class, $data);
        $this->assertSame('2005-01-01', $data->startDate()->format('Y-m-d'));
        $this->assertSame('2010-01-01', $data->endDate()->format('Y-m-d'));
    }
}
