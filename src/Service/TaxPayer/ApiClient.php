<?php

namespace App\Service\TaxPayer;

use Exception;

class ApiClient
{
    private string $url;
    private int $connectTimeout;
    private int $timeout;

    public function __construct(string $url, int $connectTimeout, int $timeout)
    {
        $this->url = $url;
        $this->connectTimeout = $connectTimeout;
        $this->timeout = $timeout;
    }

    /**
     * @param string $inn
     * @param string $requestDate
     *
     * @return array
     * @throws Exception
     */
    public function getStatus(string $inn, string $requestDate): array
    {
        return $this->send('tracker/taxpayer_status', [
            'inn' => $inn,
            'requestDate' => $requestDate,
        ]);
    }

    /**
     * @param string $url
     * @param array $data
     *
     * @return array
     * @throws Exception
     */
    private function send(string $url, array $data): array
    {
        $curl = curl_init($this->url . $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_HTTPGET, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        $result = curl_exec($curl);
        if ($code = curl_errno($curl)) {
            throw new Exception(curl_error($curl), $code);
        } else {
            $result = json_decode($result, true);
        }

        return $result;
    }
}
