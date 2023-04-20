<?php

namespace uzdevid\dashboard\notification\models\services;

use Mosquitto\Exception;
use uzdevid\dashboard\chat\models\ChatMessage;
use uzdevid\dashboard\models\Device;
use uzdevid\dashboard\notification\models\Notification;
use uzdevid\dashboard\notification\models\NotificationType;
use Yii;

class NotificationService {
    public static function createNotification(NotificationType $type, int $user_id, object|array $data) {
        $notification = new Notification();
        $notification->notification_type_id = $type->id;
        $notification->user_id = $user_id;

        $data = match ($type->name) {
            NotificationTypeService::NEW_MESSAGE => self::onNewMessage($notification, $data),
            default => []
        };


        if (!$notification->save()) {
            Yii::error($notification->errors, __METHOD__);
            throw new Exception(Yii::t('system.message', 'Error while saving notification'));
        }

        foreach (self::getTokens($user_id) as $token) {
            self::sendPush($token, $data);
        }
    }

    protected static function onNewMessage(Notification &$notification, ChatMessage $model): array {
        $notification->description = mb_strimwidth($model->source, 0, 50, '...');
        $notification->arguments = json_encode([
            'chat_id' => $model->chat_id,
            'message_id' => $model->id,
            'sender_id' => $model->participant->user_id,
        ], JSON_UNESCAPED_UNICODE);

        return [
            'title' => $model->participant->user->fullname,
            'body' => $notification->description,
            'data' => [
                'type' => NotificationTypeService::NEW_MESSAGE,
            ]
        ];
    }

    protected static function getTokens(int $user_id): array {
        return Device::find()
            ->select('notification_token')
            ->where(['user_id' => $user_id])
            ->andWhere(['is not', 'notification_token', null])
            ->column();
    }

    protected static function sendPush(string $token, array $data) {
        Yii::$app->notifier->notify($token, $data);
    }
}