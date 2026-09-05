<?php
namespace Patterns\Behavioral;

class State {
 // Manages Order Lifecycle State Transitions: Pending -> Processing -> Completed / Cancelled
 private string $currentState;

 public function __construct(string $initialState = 'pending') {
 $this->currentState = $initialState;
 }

 public function getState(): string {
 return $this->currentState;
 }

 public function transitionTo(string $newState): void {
 $allowed = [
 'pending' => ['processing', 'cancelled'],
 'processing' => ['completed', 'cancelled'],
 'completed' => ['refunded'],
 'cancelled' => []
 ];

 if (isset($allowed[$this->currentState]) && in_array($newState, $allowed[$this->currentState])) {
 $this->currentState = $newState;
 }
 }
}
