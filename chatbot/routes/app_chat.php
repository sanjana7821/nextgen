<?php

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Gemini\Enums\Role;
use Gemini\Data\Content;

require __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    $history = $data['history'] ?? [];
    $message = $data['message'] ?? null;

        $formattedHistory = [];
    foreach (($history ?? []) as $entry) {
        $role = ($entry['role'] === 'user') ? Role::USER : Role::MODEL;

        $formattedHistory[] = Gemini\Data\Content::parse(
            part: $entry['parts'][0]['text'], 
            role: $role
        );
    }

    if (!$message) {
        throw new Exception("No message provided");
    }

    $apiKey = $_ENV['GEMINI_API_KEY'];
    $client = Gemini::client($apiKey);

    $Context = file_get_contents(__DIR__ . '/../assets/res/context.txt');

    $model = $client->generativeModel(
        model: 'gemini-2.5-flash'
    )->withSystemInstruction(Content::parse($Context));

    $chat = $model->startChat(history: $formattedHistory);
    $result = $chat->sendMessage($message);

    echo json_encode(['reply' => $result->text()]);

} catch (\Exception $e) {
    if (!headers_sent()) {
        http_response_code(500);
    }
    
    echo json_encode([
        'error' => true,
        'reply' => "AI Error: " . $e->getMessage()
    ]);
}