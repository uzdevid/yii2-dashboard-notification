<?php

namespace uzdevid\dashboard\notification\widgets\Notification;

use Yii;
use yii\base\Widget;

class Notification extends Widget {
    /**
     * @return string
     */
    public function run(): string {
        $user = Yii::$app->user->identity;

        $unread_notifications = \uzdevid\dashboard\notification\models\Notification::find()->where(['user_id' => $user->id])->andWhere(['is_read' => 0])->all();
        return $this->render('index', compact('unread_notifications'));
    }
}