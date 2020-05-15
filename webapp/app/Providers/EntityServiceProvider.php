<?php

namespace App\Providers;

use App\Models\Entity;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use function config;

/**
 * Class EntityServiceProvider
 * @package App\Providers
 */
class EntityServiceProvider extends BasicProvider
{
    //si occupa di prendere i device dal database
    /**
     * @var Client
     */
    private $request;

    /**
     * EntityServiceProvider constructor.
     */
    public function __construct()
    {
        parent::__construct(app());
        $this->request = new Client([
            'base_uri' => config('app.api') . '/entities',
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    /**
     * @param mixed $identifier
     * @return Entity
     */
    public function find($identifier)
    {
        try {
            $response = json_decode($this->request->get('/entities/' . $identifier, $this->setHeaders())->getBody());
            $entity = new Entity();
            $entity->fill((array)$response);
            return $entity;
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
            $entities = [];
            foreach ($response as $e) {
                $entity = new Entity();
                $entity->fill((array)$e);
                $entities[] = $entity;
            }
            return $entities;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }

    /**
     * @param $sensorId
     * @return array
     */
    public function findAllFromSensor($sensorId)
    {
        try {
            $response = json_decode($this->request->get('', array_merge($this->setHeaders(), [
                'query' => ['sensorId' => $sensorId]
            ]))->getBody());
            $entities = [];
            foreach ($response as $e) {
                $entity = new Entity();
                $entity->fill((array)$e);
                $entities[] = $entity;
            }
            return $entities;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }

    /**
     * @param $userId
     * @return Entity|null
     */
    public function findFromUser($userId)
    {
        try {
            $response = json_decode($this->request->get('', array_merge($this->setHeaders(), [
                'query' => ['userId' => $userId]
            ]))->getBody());
            if (empty($response)) {
                return null;
            }
            $entity = new Entity();
            $entity->fill((array)$response[0]);
            return $entity;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }

    public function update(string $who, string $body)
    {
        try {
            $this->request->put('/entities/' . $who, array_merge($this->setHeaders(), [
                'body' => $body
            ]));
            return true;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return false;
        }
    }
    public function destroy(string $who)
    {
        try {
            $this->request->delete('/entities/' . $who, $this->setHeaders());
            return true;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return false;
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
}
