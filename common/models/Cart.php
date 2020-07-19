<?php

namespace common\models;

use common\entities\Products;
use common\models\storage\StorageInterface;
use yii\helpers\Json;

class Cart
{
    private $storage;
    private $items;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function getItems()
    {
        $this->loadItems();
        return $this->items;
    }

    public function getItem($id)
    {
        /* @var $item CartItem */
        $this->loadItems();
        foreach ($this->items as $item) {
            if ($item->getProductId() == $id) {
                return $item;
            }
        }
        return false;
    }

    public function getAmount()
    {
        $this->loadItems();
        return count($this->items);
    }

    public function getTotalAmount()
    {
        /* @var $item CartItem */
        $this->loadItems();
        $count = 0;
        foreach ($this->items as $item) {
            $count += $item->getQuantity();
        }
        return $count;
    }

    public function add(CartItem $item)
    {
        /* @var $current CartItem */
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getProductId() == $item->getProductId()) {
                $this->items[$i] = $current->plus($item->getQuantity());
                if ($this->items[$i]->quantity < 1) {
                    unset($this->items[$i]);
                }
                $this->saveItems();
                return;
            }
        }
        $this->items[] = $item;
        $this->saveItems();
    }

    public function set($id, $quantity)
    {
        /* @var $current CartItem */
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getId() == $id) {
                $this->items[$i] = $current->changeQuantity($quantity);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Блюдо не найдено.');
    }

    public function remove($id)
    {
        /* @var $current CartItem */
        $this->loadItems();
        foreach ($this->items as $i => $current) {
            if ($current->getId() == $id) {
                unset($this->items[$i]);
                $this->saveItems();
                return;
            }
        }
        throw new \DomainException('Блюдо не найдено.');
    }

    public function clear()
    {
        $this->items = [];
        $this->saveItems();
    }

    public function getCost()
    {
        /* @var  $item CartItem */
        $this->loadItems();
        $cost = 0;
        foreach ($this->items as $item) {
            $cost += $item->getCost();
        }
        return $cost;
    }

    public function getTotalCost()
    {
        $cost = $this->getCost();
        if ($cost < \Yii::$app->params['minSum']) {
            throw new \DomainException('Сумма заказа слишком низкая.');
        }
        return $cost;
    }

    public function getWeight()
    {
        $this->loadItems();
        return array_sum(array_map(function (CartItem $item) {
            return $item->getWeight();
        }, $this->items));
    }

    public function setArrayJson(Cart $cart)
    {
        /* @var $item CartItem */
        /* @var $product Products */
        $array = [];
        foreach ($cart->getItems() as $item) {
            $product = $item->getProduct();
            array_push($array, [
                'id' => $product->id,
                'title' => $product->title,
                'price' => $product->price,
                'description' => $product->description,
                'weight' => $product->weight,
                'image' => $product->image,
                'quantity' => $item->getQuantity(),
                'cost' => $item->getCost(),
            ]);
        }
        $array_json = Json::encode($array);
        return $array_json;
    }

    private function loadItems()
    {
        if ($this->items === null) {
            $this->items = $this->storage->load();
        }
    }

    private function saveItems()
    {
        $this->storage->save($this->items);
    }
} 