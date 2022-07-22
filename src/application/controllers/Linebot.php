<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Linebot extends CI_Controller
{
    const CURL_URL = 'https://api.line.me/v2/bot/message/reply';

    private $accessToken;
    private $channelSecret;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('my');
        $this->load->library('linebot_action');

        $this->accessToken   = getenv('LINE_ACCESS_TOKEN');
        $this->channelSecret = getenv('LINE_CHANNEL_SECRET');
    }

    public function webhook()
    {
        $json_string = file_get_contents('php://input');
        if (!$json_string) return;

        $json   = json_decode($json_string, true);
        $events = $json["events"];

        foreach ($events as $event) {
            //取得データ
            $lineUserId     = $event["source"]["userId"];
            $replyToken     = $event["replyToken"];//返信用トークン
            $message_type   = $event["message"]["type"] ?? $event["type"]; //メッセージタイプ

            switch ($message_type) {
                case 'follow':
                    $messageArray = [
                        "type" => "text",
                        "text" => "お友達追加ありがとうございます。"
                    ];
                    break;
                case 'unfollow':
                    exit;
                case 'text':
                    $messageArray = $this->linebot_action->textAction();
                    break;
                case 'location':
                    $messageArray = $this->linebot_action->locationAction($event);
                    break;
                case 'sticker':
                    $messageArray = $this->linebot_action->stampAction();
                    break;
                default:
                    exit;
            }
        }

        //返信実行
        $this->sendMessage($replyToken, $lineUserId, $messageArray);
    }

    //メッセージの送信
    private function sendMessage(string $replyToken, string $lineUserId, array $response_format_text)
    {
        //ポストデータ
        $post_data = [
            "replyToken" => $replyToken,
            "to" => $lineUserId,
            "messages" => [ $response_format_text ]
        ];

        //curl実行
        $ch = curl_init( self::CURL_URL );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charser=UTF-8',
            'Authorization: Bearer ' . $this->accessToken
        ));
        $result = curl_exec($ch);
        curl_close($ch);
    }
}