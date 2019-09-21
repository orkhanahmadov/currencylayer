<?php

namespace Orkhanahmadov\Currencylayer\Tests\CurrencylayerClient;

use BlastCloud\Guzzler\UsesGuzzler;
use Carbon\CarbonImmutable;
use GuzzleHttp\Psr7\Response;
use Orkhanahmadov\Currencylayer\Conversion;
use Orkhanahmadov\Currencylayer\Currency;
use Orkhanahmadov\Currencylayer\CurrencylayerClient;
use Orkhanahmadov\Currencylayer\Tests\TestCase;

class ConvertTest extends TestCase
{
    use UsesGuzzler;

    /**
     * @var CurrencylayerClient
     */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new CurrencylayerClient(self::FAKE_ACCESS_KEY);
        $this->client->setClient($this->guzzler->getClient(['base_uri' => self::API_HTTP_URL]));
    }

    public function testConvertWithLiveQuotes()
    {
        $this->guzzler
            ->expects($this->once())
            ->get(self::API_HTTP_URL . 'convert')
            ->withQuery([
                'access_key' => self::FAKE_ACCESS_KEY,
                'from' => 'USD',
                'to' => 'GBP',
                'amount' => 10,
            ])
            ->willRespond(new Response(200, [], $this->jsonFixture('convert/live')));

        $data = $this->client->source('USD')->currencies('GBP')->convert(10);

        $this->assertInstanceOf(Conversion::class, $data);
        $this->assertSame('USD', $data->getFrom());
        $this->assertSame('GBP', $data->getTo());
        $this->assertSame(10, $data->getAmount());
        $this->assertInstanceOf(CarbonImmutable::class, $data->getTimestamp());
        $this->assertSame(1430068515, $data->getTimestamp()->unix());
        $this->assertSame(0.658443, $data->getQuote());
        $this->assertSame(6.58443, $data->getResult());
    }

    public function testConvertWithSpecificDateQuotes()
    {
        $this->guzzler
            ->expects($this->once())
            ->get(self::API_HTTP_URL . 'convert')
            ->withQuery([
                'access_key' => self::FAKE_ACCESS_KEY,
                'date' => '2005-01-01',
                'from' => 'USD',
                'to' => 'GBP',
                'amount' => 10,
            ])
            ->willRespond(new Response(200, [], $this->jsonFixture('convert/historical')));

        $data = $this->client->source('USD')->currencies('GBP')->date('2005-01-01')->convert(10);

        $this->assertInstanceOf(Conversion::class, $data);
        $this->assertSame('USD', $data->getFrom());
        $this->assertSame('GBP', $data->getTo());
        $this->assertSame(10, $data->getAmount());
        $this->assertInstanceOf(CarbonImmutable::class, $data->getTimestamp());
        $this->assertSame(1104623999, $data->getTimestamp()->unix());
        $this->assertSame(0.51961, $data->getQuote());
        $this->assertSame(5.1961, $data->getResult());
        $this->assertInstanceOf(CarbonImmutable::class, $data->getDate());
        $this->assertSame('2005-01-01', $data->getDate()->format('Y-m-d'));
    }
}