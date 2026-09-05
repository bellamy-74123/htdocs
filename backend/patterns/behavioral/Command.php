<?php
namespace Patterns\Behavioral;

class Command {
 // Encapsulates an inventory adjustment command
 private string $action;
 private int $medicineId;
 private int $quantity;

 public function __construct(string $action, int $medicineId, int $quantity) {
 $this->action = $action;
 $this->medicineId = $medicineId;
 $this->quantity = $quantity;
 }

 public function execute(): array {
 return [
 'action' => $this->action,
 'medicine_id' => $this->medicineId,
 'quantity' => $this->quantity,
 'status' => 'EXECUTED'
 ];
 }
}
