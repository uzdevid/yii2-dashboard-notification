<?php

namespace uzdevid\dashboard\notifications\widgets;

use yii\base\Widget;

class Notification extends Widget {
    /**
     * @return string
     */
    public function run(): string {
        $user = Yii::$app->user->identity;

        $unread_notifications = \uzdevid\dashboard\models\Notification::find()->where(['user_id' => $user->id])->andWhere(['is_read' => 0])->all();
        return $this->render('index', compact('unread_notifications'));
    }
}