<?php

use uzdevid\dashboard\widgets\OffCanvasPage\OffCanvasPage;
use uzdevid\dashboard\widgets\SideBar\View;
use uzdevid\dashboard\base\helpers\Url;
use uzdevid\dashboard\notification\models\Notification;
use uzdevid\dashboard\notification\models\services\NotificationService;

/**
 * @var View $this
 * @var Notification[] $notification_list
 */
?>

<?php if (count($notification_list) > 4): ?>
    <li class="dropdown-header">
        <?php echo Yii::t('system.content', 'You have {0} new notifications', [count($notification_list)]); ?>
    </li>
<?php elseif (empty($notification_list)): ?>
    <li class="dropdown-header pb-3">
        <i class="fs-2 bi bi-bell-slash text-primary"></i>
        <p class="m-0"><?php echo Yii::t('system.content', 'No new notifications'); ?></p>
    </li>
<?php endif; ?>

<li>
    <hr class="dropdown-divider">
</li>

<?php foreach (array_slice($notification_list, 0, 4) as $notification): ?>
    <li class="notification-item">
        <?php echo NotificationService::icon($notification); ?>
        <div>
            <h4><?php echo NotificationService::title($notification); ?></h4>
            <p><?php echo NotificationService::highlightDescription($notification); ?></p>
            <p><?php echo NotificationService::sendTime($notification); ?></p>
        </div>
    </li>
    <li>
        <hr class="dropdown-divider">
    </li>
<?php endforeach; ?>

<li class="dropdown-footer">
    <?php echo OffCanvasPage::link(Yii::t('system.content', 'Show all notification'), Url::to(['/system/notification/index'])); ?>
</li>