<?php

namespace App\Http\Services;

use App\Models\OrderItem;

class OrderItemService {

    public function store(array $data): OrderItem
    {
        $item = OrderItem::create($data);

        return $item;
    }

    public function update(array $data, OrderItem $item): OrderItem
    {
        $item->update($data);

        return $item;
    }
}