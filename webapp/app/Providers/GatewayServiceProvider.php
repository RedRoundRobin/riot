<?php

namespace App\Providers;

use App\Models\Gateway;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use function config;

/**
 * Class GatewayServiceProvider
 * @package App\Providers
 */
class GatewayServiceProvider extends BasicProvider
{
    //si occupa di prendere i device dal database
    /**
     * @var Client
     */
    private $request;

    /**
     * GatewayServiceProvider constructor.
     */
    public function __construct()
    {
        parent::__construct(app());
        $this->request = new Client([
            'base_uri' => config('app.api') . '/gateways',
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * @param mixed $identifier
     * @return Gateway
     */
    public function find($identifier)
    {
        try {
            $response = json_decode($this->request->get('/gateways/' . $identifier, $this->setHeaders())->getBody());
            $gateway = new Gateway();
            $gateway->fill((array)$response);
            return $gateway;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }

    /**
     * @return array|null
     */
    public function findAll()
    {
        try {
            $response = json_decode($this->request->get('', $this->setHeaders())->getBody());
            $gateways = [];
            foreach ($response as $g) {
                $gateway = new Gateway();
                $gateway->fill((array)$g);
                $gateways[] = $gateway;
            }
            return $gateways;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }

    public function findAllFromDevice($device)
    {
        try {
            $response = json_decode($this->request->get('', array_merge($this->setHeaders(), [
                'query' => 'deviceId=' . $device
            ]))->getBody());
            $gateways = [];
            foreach ($response as $g) {
                $gateway = new Gateway();
                $gateway->fill((array)$g);
                $gateways[] = $gateway;
            }
            return $gateways;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }

    public function store(string $body)
    {
        try {
            $this->request->post('', array_merge($this->setHeaders(), [
                'body' => $body
            ]));
            return true;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return false;
        }
    }

    public function update(string $who, string $body)
    {
        try {
            $this->request->put('/gateways/' . $who, array_merge($this->setHeaders(), [
                'body' => $body
            ]));
            return true;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return false;
        }
    }

    /**
     * @param string $who
     * @return bool
     */
    public function destroy(string $who)
    {
        try {
            $this->request->delete('/gateways/' . $who, $this->setHeaders());
            return true;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return false;
        }
    }

    public function sendConfig(string $who)
    {
        try {
            $this->request->put('/gateways/' . $who, array_merge($this->setHeaders(), [
                'body' => '{"reconfig":true}'
            ]));
            return true;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return false;
        }
    }
}
