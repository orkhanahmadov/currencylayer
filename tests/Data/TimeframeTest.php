<?php

namespace Orkhanahmadov\Currencylayer\Tests\Data;

use Orkhanahmadov\Currencylayer\Data\Timeframe;
use Orkhanahmadov\Currencylayer\Tests\TestCase;

class TimeframeTest extends TestCase
{
    /**
     * @var Timeframe
     */
    private $class;

    public function testGetSource()
    {
        $this->assertSame('USD', $this->class->source());
    }

    public function testGetQuotes()
    {
        $this->assertTrue(is_array($this->class->allQuotes()));
        $this->assertCount(2, $this->class->allQuotes());
    }

    public function testGetStartDate()
    {
        $this->assertInstanceOf(\DateTimeInterface::class, $this->class->startDate());
        $this->assertSame('2010-03-01', $this->class->startDate()->format('Y-m-d'));
    }

    public function testGetEndDate()
    {
        $this->assertInstanceOf(\DateTimeInterface::class, $this->class->endDate());
        $this->assertSame('2010-03-02', $this->class->endDate()->format('Y-m-d'));
    }

    public function testQuotes()
    {
        $this->assertTrue(is_array($this->class->quotes('2010-03-02')));
    }

    public function testQuotesWithDateTimeInterface()
    {
        $this->assertTrue(is_array($this->class->quotes(new \DateTime('2010-03-02'))));
    }

    public function testQuotesThrowsExceptionWhenDateIsNotAvailable()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Quotes for 2018-03-02 is not available. Did you put it in request?');

        $this->class->quotes('2018-03-02');
    }

    public function testGetsCurrencyRate()
    {
        $this->assertSame(0.668827, $this->class->GBP('2010-03-02'));
    }

    public function testGetsCurrencyRateWithDateTimeInterface()
    {
        $this->assertSame(0.668827, $this->class->GBP(new \DateTime('2010-03-02')));
    }

    public function testMagicThrowsExceptionIfArgumentIsNotAvailable()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'whatever currency or its argument is invalid. You sure you calling correct currency with correct date?'
        );

        $this->class->whatever();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->class = new Timeframe(json_decode($this->jsonFixture('timeframe'), true));
    }
}
