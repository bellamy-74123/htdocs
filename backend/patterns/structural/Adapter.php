<?php
namespace Patterns\Structural;

// Converts XML payloads to JSON array or vice versa
class Adapter {
 public static function xmlToJsonArray(string $xmlString): array {
 $xml = simplexml_load_string($xmlString);
 return json_decode(json_encode($xml), true);
 }
}
