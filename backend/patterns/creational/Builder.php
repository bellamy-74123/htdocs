<?php
namespace Patterns\Creational;

class Builder {
 private array $order = [];

 public function setUserId(int $userId): self {
 $this->order['user_id'] = $userId;
 return $this;
 }

 public function addItem(int $medicineId, int $quantity, float $price): self {
 $this->order['items'][] = [
 'medicine_id' => $medicineId,
 'quantity' => $quantity,
 'unit_price' => $price
 ];
 return $this;
 }

 public function build(): array {
 return $this->order;
 }
}
