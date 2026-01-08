<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/config.php';

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';
$history = $input['history'] ?? [];

if (empty($userMessage)) {
    echo json_encode(['error' => 'No message provided']);
    exit;
}

$db = new DB();
$menu = $db->getMenu();
$menuContext = json_encode($menu);

// System prompt with context
$systemPrompt = "You are 'Uni', a friendly AI assistant for a Nasi Padang restaurant.
You help customers choose food, explain the menu, and take orders playfully.
Context:
- Menu: $menuContext
- Style: Friendly, Indonesian slang (gaul) mixed with Padang style (uni).
- Currency: Rupiah (Rp).

Rules:
1. Keep answers short (max 2-3 sentences).
2. Recommend specific items from the menu.
3. If asked about prices, look up the menu.
4. If the user wants to order, tell them to click the 'Add to Cart' button or visit the restaurant.";

// Construct messages array for API
$messages = [
    ['role' => 'system', 'content' => $systemPrompt]
];

// Append history (limit last 4 turns to save context)
$recentHistory = array_slice($history, -4);
foreach ($recentHistory as $msg) {
    if (isset($msg['role']) && isset($msg['content'])) {
        $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
    }
}

// Append current user message
$messages[] = ['role' => 'user', 'content' => $userMessage];

$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . OPENROUTER_API_KEY,
    'Content-Type: application/json',
    'HTTP-Referer: http://localhost',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => OPENROUTER_MODEL,
    'messages' => $messages
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error || $httpCode !== 200) {
    echo json_encode(['reply' => 'Maaf, Uni lagi pusing nih (Server Error). Coba lagi ya!']);
    exit;
}

$result = json_decode($response, true);
$reply = $result['choices'][0]['message']['content'] ?? 'Uni bingung mau jawab apa...';

echo json_encode(['reply' => $reply]);
?>