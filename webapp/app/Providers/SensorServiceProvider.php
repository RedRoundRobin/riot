<?php

namespace App\Providers;

use App\Models\Sensor;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Class SensorServiceProvider
 * @package App\Providers
 */
class SensorServiceProvider extends BasicProvider
{
    //si occupa di prendere i device dal database
    /**
     * @var Client
     */
    private $request;

    /**
     * SensorServiceProvider constructor.
     */
    public function __construct()
    {
        parent::__construct(app());
        $this->request = new Client([
            'base_uri' => config('app.api') . '/devices/',
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    /**
     * @param mixed $identifier
     * @return Sensor
     */
    public function find($deviceId, $sensorId)
    {
        try {
            $response = json_decode($this->request->get(
                $deviceId . '/sensors/' . $sensorId,
                $this->setHeaders()
            )->getBody());
            $sensor = new Sensor();
            $sensor->fill((array)$response);
            return $sensor;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }

    public function findFromLogicalId($sensorId)
    {
        try {
            $response = json_decode($this->request->get(
                '/sensors/' . $sensorId,
                $this->setHeaders()
            )->getBody());
            $sensor = new Sensor();
            $sensor->fill((array)$response);
            return $sensor;
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
            $response = json_decode($this->request->get('/sensors', $this->setHeaders())->getBody());
            $sensors = [];
            foreach ($response as $d) {
                $sensor = new Sensor();
                $sensor->fill((array)$d);
                $sensors[] = $sensor;
            }
            return $sensors;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }

    /**
     * @param $deviceId
     * @return array
     */
    public function findAllFromDevice($deviceId)
    {
        try {
            $response = json_decode($this->request->get(
                $deviceId . '/sensors',
                $this->setHeaders()
            )->getBody());
            $sensors = [];
            foreach ($response as $d) {
                $sensor = new Sensor();
                $sensor->fill((array)$d);
                $sensors[] = $sensor;
            }
            return $sensors;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }

    public function findAllFromEntity($entityId)
    {
        try {
            $response = json_decode($this->request->get(
                '/sensors/',
                array_merge(
                    $this->setHeaders(),
                    [
                        'query' => [
                            'entityId' => $entityId
                        ]
                    ]
                )
            )->getBody());
            $sensors = [];
            foreach ($response as $d) {
                $sensor = new Sensor();
                $sensor->fill((array)$d);
                $sensors[] = $sensor;
            }
            return $sensors;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }

    /**
     * @param $sensorId
     * @return mixed
     */
    public function fetch($sensorId)
    {
        try {
            return $this->request->get(
                '/data/' . $sensorId,
                $this->setHeaders()
            )->getBody();
        } catch (RequestException $e) {
            $this->isExpired($e);
            return json_encode(array(
                'time' => date("c"),
                'value' => 0
            ));
        }
    }
    public function fetchMoar()
    {
        $limit = request()->query('limit');
        $sensors = request()->query('sensors');
        $toObj = [];
        try {
            return $this->request->get(
                '/data',
                array_merge(
                    $this->setHeaders(),
                    [
                        'query' => [
                            'sensors' => is_array($sensors) ? $sensors[0] . ',' . $sensors[1] : $sensors,
                            'limit' => $limit
                            ]
                    ]
                )
            )->getBody();
        } catch (RequestException $e) {
            $this->isExpired($e);
            foreach ($sensors as $sensor) {
                $data = [];
                for ($i = 0; $i < $limit; $i++) {
                    $data[] = array(
                        'time' => date("c"),
                        'value' => 0
                    );
                }
                $toObj[$sensor] = $data;
            }
            return json_encode((object) $toObj);
        }
    }


    public function store($deviceId, string $body)
    {
        try {
            $this->request->post('/devices/' . $deviceId . '/sensors', array_merge($this->setHeaders(), [
                'body' => $body
            ]));
            return true;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return false;
        }
    }

    public function update($deviceId, $sensorId, string $body)
    {
        try {
            $this->request->put(
                '/devices/' . $deviceId . '/sensors/' . $sensorId,
                array_merge($this->setHeaders(), [
                'body' => $body
                ])
            );
            return true;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return false;
        }
    }
    public function destroy(string $deviceId, string $sensorId)
    {
        try {
            $this->request->delete(
                '/devices/' . $deviceId . '/sensors/' . $sensorId,
                $this->setHeaders()
            );
            return true;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return false;
        }
    }
    // ===================================================
    // Mockup per un utente
    // Funzione da rimuovere in production

    /**
     * @return Sensor
     */
    public static function setASensor()
    {
        $sensor = new Sensor();
        $arr = array_combine(
            array('sensorId', 'type', 'realSensorId', 'device'),
            array("0", "Tipo", "0", '0')
        );
        $sensor->fill($arr);
        return $sensor;
    }
}
