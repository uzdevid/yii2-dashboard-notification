<?php

use uzdevid\dashboard\base\helpers\Url;
use uzdevid\dashboard\notification\models\Notification;
use uzdevid\dashboard\notification\models\services\NotificationService;
use yii\web\View;

/**
 * @var View $this
 * @var Notification[] $notifications
 */
?>

<ul>
    <?php foreach ($notifications as $notification): ?>
        <li data-notice-type="<?php echo $notification->notificationType->name; ?>">
            <h6 class="d-flex align-items-center position-relative">
                <?php echo NotificationService::icon($notification); ?>
                <span class="ms-3"><?php echo NotificationService::title($notification); ?></span>
                <a class="stretched-link  <?php echo $notification->is_read ? 'collapsed' : 'show'; ?>" data-bs-toggle="collapse" href="#notice-<?php echo $notification->id; ?>" role="button" aria-expanded="true"></a>
            </h6>

            <div class="position-relative collapse <?php echo $notification->is_read ? '' : 'show'; ?>" id="notice-<?php echo $notification->id; ?>">
                <p class="m-0 fs-6"><?php echo NotificationService::highlightDescription($notification); ?></p>
                <p class="m-0 text-end small text-muted"><?php echo NotificationService::sendTime($notification); ?></p>
                <a class="stretched-link" href="<?php echo Url::to(['/notification/follow', 'id' => $notification->id]); ?>"></a>
            </div>
        </li>
        <hr>
    <?php endforeach; ?>
</ul>
