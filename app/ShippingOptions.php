<?php

namespace App;

use GuzzleHttp\Client;
use Throwable;

class ShippingOptions
{

    const API_ENDPOINT="https://api.printful.com";
    const API_KEY="77qn9aax-qrrm-idki:lnh0-fm2nhmp0yca7";
    const CACHE_DURATION = 300;
    const CACHE_KEY = 'key';

    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }


    /**
     * @param $recipient array
     * @param $items array
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getData($recipient, $items)
    {
        if ($data = $this->cache->get(self::CACHE_KEY)) return $data;

        $client = new Client(['base_uri' => self::API_ENDPOINT]);
        try {
            $response = $client->request('POST', '/shipping/rates', [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode(self::API_KEY)
                ],
                'json' => [
                    'recipient' => $recipient,
                    'items'=> $items
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $this->cache->set(self::CACHE_KEY, $data, self::CACHE_DURATION);
            return $data;

        } catch (Throwable $e) {
            echo '<pre>'; var_dump($e->getMessage()); die();
        }
    }
}
