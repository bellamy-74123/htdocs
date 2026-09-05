<?php
namespace Patterns\Creational;

class Prototype {
 public string $name;
 public float $price;
 public string $category;

 public function __construct(string $name = "", float $price = 0.0, string $category = "") {
 $this->name = $name;
 $this->price = $price;
 $this->category = $category;
 }

 public function cloneItem(): Prototype {
 return clone $this;
 }
}
