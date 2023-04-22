<?php

namespace uzdevid\dashboard\notification\models\services;

use Mosquitto\Exception;
use uzdevid\dashboard\chat\models\ChatMessage;
use uzdevid\dashboard\models\Device;
use uzdevid\dashboard\models\User;
use uzdevid\dashboard\notification\models\Notification;
use uzdevid\dashboard\notification\models\NotificationType;
use Yii;
use yii\bootstrap5\Html;

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

    /**
     * @param Notification $notification
     * @return string
     */
    public static function highlightDescription(Notification $notification): string {
        return match ($notification->notificationType->name) {
            "chat.new_message" => Html::tag('em', $notification->description),
            default => $notification->description,
        };
    }

    /**
     * @param Notification $notification
     * @return User|null
     */
    public static function sender(Notification $notification): User|null {
        return User::findOne($notification->arguments?->sender_id);
    }

    /**
     * @param Notification $notification
     * @return string
     */
    public static function icon(Notification $notification): string {
        return Html::tag('i', '', ['class' => $notification->notificationType->icon]);
    }

    public static function title(Notification|NotificationType $notification): string {
        if ($notification instanceof NotificationType)
            return Yii::t('system.notification', $notification->name);

        return Yii::t('system.notification', $notification->notificationType->name, ['sender.fullname' => $notification->sender->fullName]);
    }

    public static function sendTime(Notification $notification): string {
        return Yii::$app->formatter->asRelativeTime($notification->send_time);
    }

    /**
     * @return array
     */
    public static function behaviorsList(): array {
        return [
            'default' => Yii::t('system.notification.behavior', 'default'),
            'hide.after_follow' => Yii::t('system.notification.behavior', 'hide.after_follow'),
        ];
    }
}