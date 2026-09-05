<?php
namespace Patterns\Structural;

class Decorator {
 // Adds tax and delivery fee decorators to base order pricing
 public static function applyDiscount(float $basePrice, float $discountPercentage): float {
 return $basePrice * (1 - ($discountPercentage / 100));
 }
}
