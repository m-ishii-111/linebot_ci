<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Linebot_action
{
    const LOCATION_URI = 'line://nv/location';
    const GOOGLE_MAP_URI = 'https://www.google.com/maps/search/';
    const NOIMAGE_URI = 'https://gyazo.com/7b6bf728a87623915dce1be1a1549b92';

    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('hotpepper');
    }

    /**
     * テキストメッセージアクション
     */
    public function textAction(): array
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
    }

    private function buttonArray(string $message): array
    {
        return [
            "type" => "template",
            "altText" => "位置情報を送信してください。",
            "template" => [
                "type" => "buttons",
                "text" => "{$message}\n近くの飲食店を探します。\n現在の位置を送信してください。",
                "actions" => [
                    [
                        "type" => "uri",
                        "label" => "現在地を送信",
                        "uri" => self::LOCATION_URI
                    ]
                ]
            ]
        ];
    }

    /**
     * スタンプアクション
     */
    public function stampAction(): array
    {
        return [
            'type' => 'sticker',
            'packageId' => '11538',
            'stickerId' => '51626501'
        ];
    }

    /**
     * 位置情報アクション
     */
    public function locationAction(array $event): array
    {
        $latitude    = $event["message"]["latitude"];
        $longitude   = $event["message"]["longitude"];
        $restaurants = $this->CI->hotpepper->callRestaurants($latitude, $longitude);

        if ($restaurants['results_returned'] < 1) {
            return ['type' => 'text', 'text' => "お店の情報が見つかりませんでした。\n別の場所から再度試してみてください。"];
        }

        $shops = $restaurants["shop"];

        // lunch filter
        if (timezone() == 'noon') {
            $lunch_shops = array_filter($shops, function ($shop) {
                return $shop['lunch'] == 'あり';
            });
            $shops = $lunch_shops;
        }

        // midnight filter
        if (timezone() == 'midnight') {
            $midnight_shops = array_filter($shops, function ($shop) {
                return $shop['midnight'] == '営業している';
            });
            $shops = $midnight_shops;
        }

        $shop = $shops[array_rand($shops, 1)];

        $response = [
            'type'      => 'flex',
            'altText'   => $shop['name'],
            'contents'  => $this->flexJsonArray($shop)
        ];
        return $response;
    }

    private function flexJsonArray(array $shop): array
    {
        $thumbnail    = $shop['photo']['mobile']['l'] ?? self::NOIMAGE_URI;
        $shopUrl      = $shop['urls']['sp'] ?? $shop['urls']['pc'];
        $name         = $shop['name'] ?? '-';
        $catch        = $shop['catch'] ?? '-';
        $genre        = $shop['genre']['name'] ?? '-';
        $budget       = $shop['budget']['average'] ?? '-';
        $open         = $shop['open'] ?? '-';
        $close        = $shop['close'] ?? '-';
        $lunch        = $shop['lunch'] ?? '';
        $address      = $shop['address'] ?? '-';
        $coupon       = $shop['coupon_urls']['sp'] ?? $shop['coupon_urls']['sp'];
        $googleMapUri = self::GOOGLE_MAP_URI.'?api=1&query='.$shop['lat'].','.$shop['lng'].'&zoom=20';

        $content = [
            'type' => 'bubble',
            'hero' => [
                'type' => 'image',
                'url'  => $thumbnail,
                'size' => 'full',
                'aspectRatio' => '20:13',
                'aspectMode'  => 'cover',
                'action' => [
                    'type' => 'uri',
                    'uri'  => $shopUrl,
                ],
            ],
            'body' => [
                'type'     => 'box',
                'layout'   => 'vertical',
                'contents' => [
                    [
                        'type'   => 'text',
                        'text'   => $name,
                        'weight' => 'bold',
                        'size'   => 'xl'
                    ],
                    [
                        'type'     => 'box',
                        'layout'   => 'vertical',
                        'contents' => [
                            [
                                'type' => 'text',
                                'text' => $catch,
                                'wrap' => true
                            ]
                        ],
                    ],
                    [
                        'type'     => 'box',
                        'layout'   => 'vertical',
                        'margin'   => 'lg',
                        'spacing'  => 'sm',
                        'contents' => [
                            [
                                'type'     => 'box',
                                'layout'   => 'baseline',
                                'spacing'  => 'sm',
                                'paddingBottom' => 'sm',
                                'contents' => [
                                    [
                                        'type'  => 'text',
                                        'text'  => "ジャンル",
                                        'wrap'  => true,
                                        'color' => '#aaaaaa',
                                        'size'  => 'sm',
                                        'flex'  => 1,
                                    ],
                                    [
                                        'type'  => 'text',
                                        'text'  => $genre,
                                        'wrap'  => true,
                                        'color' => '#666666',
                                        'size'  => 'sm',
                                        'flex'  => 5,
                                    ],
                                ],
                            ],
                            [
                                'type'     => 'box',
                                'layout'   => 'baseline',
                                'spacing'  => 'sm',
                                'paddingBottom' => 'sm',
                                'contents' => [
                                    [
                                        'type'  => 'text',
                                        'text'  => "金額",
                                        'wrap'  => true,
                                        'color' => '#aaaaaa',
                                        'size'  => 'sm',
                                        'flex'  => 1,
                                    ],
                                    [
                                        'type'  => 'text',
                                        'text'  => $budget,
                                        'wrap'  => true,
                                        'color' => '#666666',
                                        'size'  => 'sm',
                                        'flex'  => 5,
                                    ],
                                ],
                            ],
                            [
                                'type'     => 'box',
                                'layout'   => 'baseline',
                                'spacing'  => 'sm',
                                'paddingBottom' => 'sm',
                                'contents' => [
                                    [
                                        'type'  => 'text',
                                        'text'  => "営業\n時間",
                                        'wrap'  => true,
                                        'color' => '#aaaaaa',
                                        'size'  => 'sm',
                                        'flex'  => 1,
                                    ],
                                    [
                                        'type'  => 'text',
                                        'text'  => $open,
                                        'wrap'  => true,
                                        'color' => '#666666',
                                        'size'  => 'sm',
                                        'flex'  => 5,
                                    ],
                                ],
                            ],
                            [
                                'type'     => 'box',
                                'layout'   => 'baseline',
                                'spacing'  => 'sm',
                                'paddingBottom' => 'sm',
                                'contents' => [
                                    [
                                        'type'  => 'text',
                                        'text'  => "定休日",
                                        'wrap'  => true,
                                        'color' => '#aaaaaa',
                                        'size'  => 'sm',
                                        'flex'  => 1,
                                    ],
                                    [
                                        'type'  => 'text',
                                        'text'  => $close,
                                        'wrap'  => true,
                                        'color' => '#666666',
                                        'size'  => 'sm',
                                        'flex'  => 5,
                                    ],
                                ],
                            ],
                            [
                                'type'     => 'box',
                                'layout'   => 'baseline',
                                'spacing'  => 'sm',
                                'paddingBottom' => 'sm',
                                'contents' => [
                                    [
                                        'type'  => 'text',
                                        'text'  => '住所',
                                        'wrap'  => true,
                                        'color' => '#aaaaaa',
                                        'size'  => 'sm',
                                        'flex'  => 1
                                    ],
                                    [
                                        'type'  => 'text',
                                        'text'  => $address,
                                        'wrap'  => true,
                                        'color' => '#666666',
                                        'size'  => 'sm',
                                        'flex'  => 5
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'footer' => [
                'type'     => 'box',
                'layout'   => 'horizontal',
                'contents' => [
                    [
                        'type'   => 'button',
                        'action' => [
                            'type'  => 'uri',
                            'label' => 'クーポン',
                            'uri'   => $coupon,
                        ]
                    ],
                    [
                        'type'   => 'button',
                        'action' => [
                            'type'  => 'uri',
                            'label' => 'Google Map',
                            'uri'  => $googleMapUri,
                        ]
                    ]
                ]
            ]
        ];

        // お昼時はランチ情報を追加
        if (timezone() == 'noon') {
            $content['body']['contents'][2]['contents'][] = [
                'type'     => 'box',
                'layout'   => 'baseline',
                'spacing'  => 'sm',
                'paddingBottom' => 'sm',
                'contents' => [
                    [
                        'type'  => 'text',
                        'text'  => "ランチ",
                        'wrap'  => true,
                        'color' => '#aaaaaa',
                        'size'  => 'sm',
                        'flex'  => 1,
                    ],
                    [
                        'type'  => 'text',
                        'text'  => $lunch,
                        'wrap'  => true,
                        'color' => '#666666',
                        'size'  => 'sm',
                        'flex'  => 5,
                    ],
                ],
            ];
        }

        // 情報提供元の追加
        $content['body']['contents'][2]['contents'][] = [
            'type'     => 'box',
            'layout'   => 'baseline',
            'spacing'  => 'sm',
            'paddingTop' => 'md',
            'contents' => [
                [
                    'type'  => 'text',
                    'text'  => "Powered by ホットペッパー Webサービス",
                    'color' => '#aaaaaa',
                    'size'  => 'xxs',
                ],
            ],
        ];

        return $content;
    }
}