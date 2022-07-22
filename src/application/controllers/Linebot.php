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
        if ($json_string) {
            $json_object = json_decode($json_string);
            $lineUserId = $json_object->{"events"}[0]->{"source"}->{"userId"};

            //取得データ
            $replyToken = $json_object->{"events"}[0]->{"replyToken"};        //返信用トークン
            $message_type = $json_object->{"events"}[0]->{"type"};    //メッセージタイプ

            switch ($message_type) {
                case 'follow':
                    $message = [
                        "type" => "text",
                        "text" => "お友達追加ありがとうございます。"
                    ];
                    break;
                case 'unfollow':
                    exit;
                case 'message':
                    $message = $this->linebot_action->textAction();
                    break;
                case 'location':
                    $message = $this->linebot_action->locationAction($json_object->{"events"}[0]);
                    debug_log($message);
                    break;
                case 'sticker':
                    exit;
                default:
                    exit;
            }

            //返信実行
            $this->sendMessage($replyToken, $lineUserId, $message);
        }

    }

    //メッセージの送信
    private function sendMessage($replyToken, $lineUserId, $response_format_text)
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

    public static function DummyJson()
    {
        return [
            'status'    => 200,
            'text'      => 'ok'
        ];
    }

    
}