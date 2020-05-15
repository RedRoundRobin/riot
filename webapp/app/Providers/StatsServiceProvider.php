<?php

namespace App\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class StatsServiceProvider extends BasicProvider
{
    private $request;

    /**
     * EntityServiceProvider constructor.
     */
    public function __construct()
    {
        parent::__construct(app());
        $this->request = new Client([
            'base_uri' => config('app.api') . '/stats',
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    public function stats()
    {
        try {
            return json_decode($this->request->get('', $this->setHeaders())->getBody());
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }
}
