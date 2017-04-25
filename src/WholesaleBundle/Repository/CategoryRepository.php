<?php

namespace WholesaleBundle\Repository;

use Exception;
use GuzzleHttp\Client;
use WholesaleBundle\Model\Category;
use WholesaleBundle\Model\CategoryCollection;
use function GuzzleHttp\json_decode;

class CategoryRepository {

    private $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function findAll($limit = 100, $offset = 0) {

        $res = $this->client->get('/rest/categories', [
            'query' => [
                'format' => 'json',
                'start' => $offset,
                'limit' => $limit
            ]
        ]);

        if ($res->getStatusCode() != 200) {
            throw new Exception("Could not get data");
        }

        $range = $res->getHeader('X-Content-Range');

        $matches = array();

        preg_match('/items (\d+)-(\d+)\/(\d+)/', $range[0], $matches);

        $data = json_decode($res->getBody());

        $response = new CategoryCollection();
        $response->setOffset($matches[1]);
        $response->setLimit($matches[2]);
        $response->setTotal($matches[3]);

        $categories = array();

        foreach ($data->categories as $category) {
            $categories[] = $this->loadCategory($category);
        }

        $response->setItems($categories);

        return $response;
    }

    public function findByParent($parentId = 0) {

        $res = $this->client->get('/rest/categories', [
            'query' => [
                'format' => 'json',
                'parent_id' => $parentId
            ]
        ]);

        if ($res->getStatusCode() != 200) {
            throw new Exception("Could not get data");
        }

        $range = $res->getHeader('X-Content-Range');

        $matches = array();

        preg_match('/items (\d+)-(\d+)\/(\d+)/', $range[0], $matches);

        $data = json_decode($res->getBody());

        $response = new CategoryCollection();
        $response->setOffset($matches[1]);
        $response->setLimit($matches[2]);
        $response->setTotal($matches[3]);

        $categories = array();

        foreach ($data->categories as $category) {
            $categories[] = $this->loadCategory($category);
        }

        $response->setItems($categories);

        return $response;
    }

    public function find($id) {

        $res = $this->client->get('/rest/categories/' . $id, [
            'query' => [
                'format' => 'json'
            ]
        ]);

        if ($res->getStatusCode() != 200) {
            throw new Exception("Could not get data");
        }

        $data = json_decode($res->getBody());

        return $this->loadCategory($data->category);
    }

    private function loadCategory($data) {
        $t = new Category();
        $t->setId($data->id)
                ->setCode($data->code)
                ->setName($data->name)
                ->setDescription($data->description)
                ->setActive($data->active)
                ->setParentId($data->parent_id)
                ->setVideo($data->video)
                ->setLft($data->lft)
                ->setRgt($data->rgt);
        return $t;
    }

}
