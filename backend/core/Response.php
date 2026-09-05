<?php
namespace Core;

class Response {
    /**
     * إرسال استجابة HTTP تدعم صيغتي JSON و XML ديناميكياً
     */
    public static function send(int $statusCode, bool $success, string $message, $data = null): void {
        http_response_code($statusCode);

        // التحقق مما إذا كان العميل يطلب صيغة XML
        $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
        $formatQuery = $_GET['format'] ?? '';
        $isXml = ($formatQuery === 'xml') || (strpos($acceptHeader, 'application/xml') !== false);

        if ($isXml) {
            header('Content-Type: application/xml; charset=utf-8');
            $xml = new \SimpleXMLElement('<response/>');
            $xml->addChild('status', (string)$statusCode);
            $xml->addChild('success', $success ? 'true' : 'false');
            $xml->addChild('message', htmlspecialchars($message, ENT_XML1, 'UTF-8'));

            if ($data !== null) {
                $dataNode = $xml->addChild('data');
                self::arrayToXml($data, $dataNode);
            }
            echo $xml->asXML();
        } else {
            header('Content-Type: application/json; charset=utf-8');
            $responseArray = [
                'status' => $statusCode,
                'success' => $success,
                'message' => $message,
                'data' => $data
            ];
            echo json_encode($responseArray, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        exit();
    }

    private static function arrayToXml($data, \SimpleXMLElement &$xml): void {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item';
            }
            if (is_array($value)) {
                $subNode = $xml->addChild($key);
                self::arrayToXml($value, $subNode);
            } else {
                $xml->addChild($key, htmlspecialchars((string)$value, ENT_XML1, 'UTF-8'));
            }
        }
    }
}
