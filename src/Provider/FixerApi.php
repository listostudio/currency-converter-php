<?php

namespace CurrencyConverter\Provider;

use CurrencyConverter\Exception\UnsupportedCurrencyException;
use GuzzleHttp\Client;

/**
 * Fetch rates from https://fixer.io
 *
 */
class FixerApi implements ProviderInterface
{
    /**
     * Base url of fixer api
     *
     * @var string
     */
    //const FIXER_API_BASEPATH = 'https://api.fixer.io/latest';

    const FIXER_API_BASEPATH = 'http://data.fixer.io/api/latest?access_key=1331a59e956ebdada527edfdbcf77db7&format=1';

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * FixerApi constructor.
     * @param Client|null $httpClient
     */
    public function __construct(Client $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new Client();
    }

    /**
     * {@inheritdoc}
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        $path = sprintf(
            self::FIXER_API_BASEPATH . '?symbols=%s&base=%s',
            $toCurrency,
            $fromCurrency
        );
        $result = json_decode($this->httpClient->get($path)->getBody(), true);

        if (!isset($result['rates'][$toCurrency])) {
            throw new UnsupportedCurrencyException(sprintf('Undefined rate for "%s" currency.', $toCurrency));
        }

        return $result['rates'][$toCurrency];
    }
}
