<?php
namespace Patterns\Behavioral;

abstract class MiddlewareHandler {
 protected ?MiddlewareHandler $next = null;

 public function setNext(MiddlewareHandler $handler): MiddlewareHandler {
 $this->next = $handler;
 return $handler;
 }

 abstract public function handle(array $request): bool;
}

class AuthMiddleware extends MiddlewareHandler {
 public function handle(array $request): bool {
 if (!isset($request['token'])) return false;
 if ($this->next) return $this->next->handle($request);
 return true;
 }
}

class ChainOfResponsibility {
 private MiddlewareHandler $head;

 public function __construct(MiddlewareHandler $head) {
 $this->head = $head;
 }

 public function process(array $request): bool {
 return $this->head->handle($request);
 }
}
