<?php

namespace App\Providers;

use App\Models\ViewGraph;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ViewGraphServiceProvider extends BasicProvider
{
    /**
     * @var Client
     */
    private $request;

    public function __construct()
    {
        parent::__construct(app());
        $this->request = new Client([
            'base_uri' => config('app.api') . '/viewGraphs',
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    /**
     * @param mixed $identifier
     * @return ViewGraph
     */
    public function find($identifier)
    {
        try {
            $response = json_decode($this->request->get('/viewGraphs/' . $identifier, $this->setHeaders())->getBody());
            $graph = new ViewGraph();
            $graph->fill((array)$response);
            return $graph;
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
            $graphs = [];
            foreach ($response as $g) {
                $graph = new ViewGraph();
                $graph->fill((array)$g);
                $graphs[] = $graph;
            }
            return $graphs;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return null;
        }
    }
    public function findAllFromView($viewId)
    {
        try {
            $response = json_decode($this->request->get('', array_merge($this->setHeaders(), [
                'query' => 'viewId=' . $viewId
            ]))->getBody());
            $graphs = [];
            foreach ($response as $g) {
                $graph = new ViewGraph();
                $graph->fill((array)$g);
                $graphs[] = $graph;
            }
            return $graphs;
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
    public function destroy(string $who)
    {
        try {
            $this->request->delete('/viewGraphs/' . $who, $this->setHeaders());
            return true;
        } catch (RequestException $e) {
            $this->isExpired($e);
            return false;
        }
    }
}
