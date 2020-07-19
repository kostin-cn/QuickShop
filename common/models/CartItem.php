<?php

namespace common\models;

use common\entities\Products;

class CartItem
{
    private $product;
    public $quantity;

    public function __construct(Products $product, $quantity = 1)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getId()
    {
        return md5(serialize([$this->product->id]));
    }

    public function getProductId()
    {
        return $this->product->id;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getPrice()
    {
        return $this->product->price;
    }

    public function getCost()
    {
        return $this->getPrice() * $this->quantity;
    }

    public function getWeight()
    {
        return $this->product->getProductWeight() * $this->quantity;
    }

    public function plus($quantity)
    {
        return new static($this->product, $this->quantity + $quantity);
    }

    public function changeQuantity($quantity)
    {
        return new static($this->product, $quantity);
    }
}