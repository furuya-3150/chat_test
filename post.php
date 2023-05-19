<?php

if (!isset($_POST['question'])) {
	header('Location: ./index.php');
	exit;
}
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$api_key = $_ENV['OPEN_AI_KEY']; 

function call_gpt_3_5_turbo_api($messages, $api_key) {
    // OpenAI API URL
    $url = "https://api.openai.com/v1/chat/completions";

    // リクエストヘッダー
    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $api_key
    );

    // リクエストボディ
    $data = array(
        "model" => "gpt-3.5-turbo",
        "messages" => $messages,
        "max_tokens" => 500, // 応答の最大トークン数（≒文字数）を設定
    );

    // cURLを初期化
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // APIにリクエストを送信し、応答を取得
    $response = curl_exec($ch);

    // cURLを閉じる
    curl_close($ch);

    return $response;
}

$messages = array(
    array("role" => "system", "content" => "You are a helpful assistant."),
    array("role" => "user", "content" => $_POST['question']),
);

$response = call_gpt_3_5_turbo_api($messages, $api_key);
$response_decoded = json_decode($response, true);

echo "Response: " . $response_decoded["choices"][0]["message"]["content"] . "\n";
echo '<a href="./index.php">戻る</a>';
?>
