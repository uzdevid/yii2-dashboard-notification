<?php

use yii\db\Migration;

/**
 * Class m230420_152948_notification
 */
class m230420_152948_notification extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m230420_152948_notification cannot be reverted.\n";

        return false;
    }


    public function up() {
        $this->createTable('notification_type', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'icon' => $this->string(255)->notNull(),
            'behavior' => $this->string(255)->notNull()->defaultValue('default'),
        ]);

        $this->createTable('notification_type_role', [
            'id' => $this->primaryKey(),
            'notification_type_id' => $this->integer(11)->notNull(),
            'role_id' => $this->integer(11)->notNull(),
        ]);

        $this->createTable('notification', [
            'id' => $this->primaryKey(),
            'notification_type_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'description' => $this->text()->notNull(),
            'arguments' => $this->json()->null()->defaultValue(null),
            'is_read' => $this->boolean()->notNull()->defaultValue(false),
            'send_time' => $this->integer(11)->notNull(),
        ]);

        $this->addForeignKey('fk_notification_type_role_notification_type_id', 'notification_type_role', 'notification_type_id', 'notification_type', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_notification_type_role_role_id', 'notification_type_role', 'role_id', 'role', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_notification_notification_type_id', 'notification', 'notification_type_id', 'notification_type', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_notification_user_id', 'notification', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->addColumn('device', 'notification_token', $this->string(255)->null()->defaultValue(null)->after('type'));

        $this->insert('menu', [
            'role_id' => 1,
            'parent_id' => null,
            'icon' => 'bi bi-circle',
            'title' => 'Notification',
            'link' => '/system/default/index',
            'order' => 1
        ]);

        $this->insert('menu', [
            'role_id' => 1,
            'parent_id' => null,
            'icon' => 'bi bi-circle',
            'title' => 'Notification Types',
            'link' => '/system/notification-type/index',
            'order' => 1
        ]);
    }

    public function down() {
        echo "m230420_152948_notification cannot be reverted.\n";

        return false;
    }
}
