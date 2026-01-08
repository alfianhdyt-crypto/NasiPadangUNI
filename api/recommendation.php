<?php
header('Content-Type: application/json');
require_once '../includes/db.php';
require_once '../includes/config.php';

$input = json_decode(file_get_contents('php://input'), true);
$time = $input['time'] ?? 'day';
$history = $input['history'] ?? [];

$db = new DB();
$menu = $db->getMenu();

// Prepare prompt context
$menuData = json_encode($menu);
$historyData = json_encode($history);
$prompt = "You are an AI waiter in a Nasi Padang restaurant. Analysis the context:
- Time of day: $time
- User Order History IDs: $historyData
- Available Menu: $menuData

Task: Recommend 3 best items from the menu for this user right now.
Rules:
1. Return ONLY valid JSON array of objects.
2. Each object must be exactly: {\"id\": <id>, \"name\": \"<name>\", \"reason\": \"<short reason>\"}
3. If history exists, prefer related items but add variety.
4. If morning/night, filter appropriate items.
5. NO markdown formatting. NO explanation outside the JSON.";

$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . OPENROUTER_API_KEY,
    'Content-Type: application/json',
    'HTTP-Referer: http://localhost', // Optional for OpenRouter
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => OPENROUTER_MODEL,
    'messages' => [
        ['role' => 'system', 'content' => 'You are a helpful JSON-only API assistant.'],
        ['role' => 'user', 'content' => $prompt]
    ]
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error || $httpCode !== 200) {
    // Fallback if API fails
    $candidates = array_filter($menu, fn($i) => $i['popularity'] > 80);
    echo json_encode(array_slice($candidates, 0, 3));
    exit;
}

$result = json_decode($response, true);
if (isset($result['choices'][0]['message']['content'])) {
    $content = $result['choices'][0]['message']['content'];

    // Clean potential markdown code blocks
    $content = preg_replace('/^```json\s*|\s*```$/', '', $content);

    $recommendations = json_decode($content, true);

    // Merge full details from menu
    $finalDetails = [];
    if (is_array($recommendations)) {
        foreach ($recommendations as $rec) {
            foreach ($menu as $item) {
                if ($item['id'] == $rec['id']) {
                    $item['ai_reason'] = $rec['reason'];
                    $finalDetails[] = $item;
                    break;
                }
            }
        }
    }

    // If AI hallucinates IDs or formatting fails, fallback
    if (empty($finalDetails)) {
        echo json_encode(array_slice($menu, 0, 3));
    } else {
        echo json_encode($finalDetails);
    }
} else {
    echo json_encode(array_slice($menu, 0, 3));
}
