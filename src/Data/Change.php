<?php

namespace Orkhanahmadov\Currencylayer\Data;

use Carbon\CarbonImmutable;

class Change
{
    /**
     * @var string
     */
    private $source;
    /**
     * @var array
     */
    private $quotes;
    /**
     * @var \DateTimeImmutable
     */
    private $startDate;
    /**
     * @var \DateTimeImmutable
     */
    private $endDate;

    /**
     * Change constructor.
     *
     * @param array $data
     *
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        $this->source = $data['source'];
        $this->quotes = $data['quotes'];
        $this->startDate = new CarbonImmutable($data['start_date']);
        $this->endDate = new CarbonImmutable($data['end_date']);
    }

    /**
     * @param string $currency
     *
     * @return float
     */
    public function startRate(string $currency): float
    {
        return $this->quotes[$this->findKey($currency)]['start_rate'];
    }

    /**
     * @param string $currency
     *
     * @return float
     */
    public function endRate(string $currency): float
    {
        return $this->quotes[$this->findKey($currency)]['end_rate'];
    }

    /**
     * @param string $currency
     *
     * @return float
     */
    public function changeAmount(string $currency): float
    {
        return $this->quotes[$this->findKey($currency)]['change'];
    }

    /**
     * @param string $currency
     *
     * @return float
     */
    public function changePercentage(string $currency): float
    {
        return $this->quotes[$this->findKey($currency)]['change_pct'];
    }

    /**
     * @param string $currency
     *
     * @return string
     */
    private function findKey(string $currency): string
    {
        $key = $this->source.$currency;
        if (!isset($this->quotes[$key])) {
            throw new \InvalidArgumentException(
                "{$currency} currency is not available. Did you put it in request?"
            );
        }

        return $key;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return array
     */
    public function getQuotes(): array
    {
        return $this->quotes;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }
}
