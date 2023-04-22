<?php

namespace uzdevid\dashboard\notification;

use yii\web\AssetBundle;
use yii\web\View;

class NotificationAsset extends AssetBundle {

    public $sourcePath = '@vendor/uzdevid/yii2-dashboard-notification/assets';

    public $js = [
        [
            'js/notification.js',
            'position' => View::POS_HEAD,
        ],
        [
            'js/onNotify.js',
            'position' => View::POS_HEAD,
        ],
        [
            'js/saveToken.js',
            'position' => View::POS_HEAD,
        ]
    ];

    public $depends = [
        'uzdevid\fcm\FcmAsset'
    ];
}