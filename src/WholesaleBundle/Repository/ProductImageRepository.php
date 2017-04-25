<?php

namespace WholesaleBundle\Repository;

use DateTime;
use Exception;
use GuzzleHttp\Client;
use WholesaleBundle\Model\ProductImage;
use WholesaleBundle\Model\ProductImageCollection;

class ProductImageRepository {

    private $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function findAll($limit = 100, $offset = 0) {

        $res = $this->client->get('/rest/product-images', [
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

        $response = new ProductImageCollection();
        $response->setOffset($matches[1]);
        $response->setLimit($matches[2]);
        $response->setTotal($matches[3]);

        $productImages = array();

        foreach ($data->images as $productImage) {
            $productImages[] = $this->loadProductImage($productImage);
        }

        $response->setItems($productImages);

        return $response;
    }

    public function findBySku($sku) {

        $res = $this->client->get('/rest/product-images/' . $sku, [
            'query' => [
                'format' => 'json'
            ]
        ]);

        if ($res->getStatusCode() != 200) {
            throw new Exception("Could not get data");
        }

        $range = $res->getHeader('X-Content-Range');

        $matches = array();

        preg_match('/items (\d+)-(\d+)\/(\d+)/', $range[0], $matches);

        $data = json_decode($res->getBody());

        $response = new ProductImageCollection();
        $response->setOffset($matches[1]);
        $response->setLimit($matches[2]);
        $response->setTotal($matches[3]);

        $productImages = array();

        foreach ($data->images as $productImage) {
            $productImages[] = $this->loadProductImage($productImage);
        }

        $response->setItems($productImages);

        return $response;
    }

    public function find($id) {

        $res = $this->client->get('/rest/product-images/' . $id, [
            'query' => [
                'format' => 'json'
            ]
        ]);

        if ($res->getStatusCode() != 200) {
            throw new Exception("Could not get data");
        }

        $data = json_decode($res->getBody());

        return $this->loadProductImage($data->image);
    }

    private function loadProductImage($data) {
        $t = new ProductImage();
        $t->setId($data->id)
                ->setAltText($data->alt_text)
                ->setCreatedOn(new DateTime($data->created_on))
                ->setDescription($data->description)
                ->setExplicit($data->explicit)
                ->setFileType($data->file_type)
                ->setFilename($data->filename)
                ->setImageLargeUrl($data->image_large_url)
                ->setImageMediumUrl($data->image_medium_url)
                ->setImageThumbUrl($data->image_thumb_url)
                ->setImageUrl($data->image_url)
                ->setPrimary($data->primary)
                ->setProductId($data->product_id)
                ->setUpdatedOn(new DateTime($data->updated_on));

        return $t;
    }

}
