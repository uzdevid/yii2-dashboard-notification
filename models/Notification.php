<?php

namespace uzdevid\dashboard\notification\models;

use uzdevid\dashboard\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "notification".
 *
 * @property int $id
 * @property int $notification_type_id
 * @property int $user_id
 * @property string $description
 * @property object|null $arguments
 * @property int $is_read
 * @property int $send_time
 *
 * @property NotificationType $notificationType
 * @property User $user
 * @property User $sender
 *
 */
class Notification extends ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string {
        return 'notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array {
        return [
            [['notification_type_id', 'user_id', 'description'], 'required'],
            [['notification_type_id', 'user_id', 'is_read'], 'integer'],
            [['description'], 'string'],
            [['arguments', 'send_time'], 'safe'],
            [['notification_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationType::class, 'targetAttribute' => ['notification_type_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array {
        return [
            'id' => Yii::t('system.model', 'ID'),
            'notification_type_id' => Yii::t('system.model', 'Notification Type ID'),
            'user_id' => Yii::t('system.model', 'User ID'),
            'description' => Yii::t('system.model', 'Description'),
            'arguments' => Yii::t('system.model', 'Arguments'),
            'is_read' => Yii::t('system.model', 'Is Read'),
            'send_time' => Yii::t('system.model', 'Send Time'),
        ];
    }

    /**
     * Gets query for [[NotificationType]].
     *
     * @return ActiveQuery
     */
    public function getNotificationType(): ActiveQuery {
        return $this->hasOne(NotificationType::class, ['id' => 'notification_type_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Sender]].
     *
     * @return User
     */
    public function getSender(): User {
        $this->parseArguments();
        return User::findOne($this->arguments?->sender_id);
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => 'yii\behaviors\TimestampBehavior',
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['send_time'],
            ],
        ];

        $behaviors['format'] = [
            'class' => 'yii\behaviors\AttributeBehavior',
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['arguments'],
            ],
            'value' => function ($event) {
                return json_encode($this->arguments);
            },
        ];

        return $behaviors;
    }

    public function afterFind() {
        $this->parseArguments();
        parent::afterFind();
    }

    private function parseArguments() {
        if (is_string($this->arguments)) {
            $this->arguments = json_decode($this->arguments);
        } else {
            $this->arguments = (object)$this->arguments;
        }
    }
}
