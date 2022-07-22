<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Linebot_action
{
    const LOCATION_URI = 'line://nv/location';

    public function __construct()
    {
        $this->load->helper('my');
    }

    public function textAction()
    {
        $tz = timezone();
        switch ($tz) {
            case 'morning':
                $message = "おはようございます。\n今日も一日張り切っていきましょう。\n";
                break;
            case 'noon':
                $message = "こんにちは。\nお昼の時間でございます。\n";
                break;
            case 'night':
                $message = "こんばんは。\n今晩の夕食はこちらになります。\n";
                break;
            case 'midnight':
                $message = "早くお帰りになられたほうがよろしいかと...\n";
                break;
            default:
                debug_log($tz);
                eixt;
        }
        
        $postArray = $this->buttonArray($message);
        return $postArray;
        // return $message;
    }

    public function locationAction($event)
    {
        return;
    }

    private function buttonArray($message)
    {
        return [
            "type" => "template",
            "altText" => "位置情報を送信してください。",
            "template" => [
                "type" => "buttons",
                "title" => "Please send location",
                "text" => "{$message}\n近くの飲食店を探します。\n現在の位置を送信してください。",
                "defaultAction" => [
                    "type" => "uri",
                    "label" => "現在地を送信",
                    "uri" => self::LOCATION_URI
                ]
            ]
        ];
    }
}