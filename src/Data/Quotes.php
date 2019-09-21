<?php

namespace Orkhanahmadov\Currencylayer\Data;

use Carbon\CarbonImmutable;
use DateTimeImmutable;

class Quotes
{
    /**
     * @var array
     */
    private $quotes;
    /**
     * @var string
     */
    private $source;
    /**
     * @var int
     */
    private $timestamp;
    /**
     * @var CarbonImmutable|null
     */
    private $date;

    /**
     * Currency constructor.
     *
     * @param array $data
     *
     * @throws \Exception
     */
    public function __construct(array $data)
    {
        $this->quotes = $data['quotes'];
        $this->source = $data['source'];
        $this->timestamp = $data['timestamp'];
        $this->date = isset($data['date']) ? new CarbonImmutable($data['date']) : null;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        $key = $this->source . $name;
        if (! array_key_exists($key, $this->quotes)) {
            throw new \InvalidArgumentException($name . ' does not exist in API response. Did you requested it?');
        }

        return $this->quotes[$key];
    }

    /**
     * @return array
     */
    public function getQuotes(): array
    {
        return $this->quotes;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return DateTimeImmutable
     * @throws \Exception
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return new CarbonImmutable($this->timestamp);
    }

    /**
     * @return CarbonImmutable|null
     */
    public function getDate(): ?CarbonImmutable
    {
        return $this->date;
    }
}