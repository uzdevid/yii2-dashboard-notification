<?php

namespace uzdevid\dashboard\notification\controllers;

use uzdevid\dashboard\models\Device;
use uzdevid\dashboard\models\Menu;
use uzdevid\dashboard\notification\models\Notification;
use uzdevid\dashboard\widgets\ModalPage\ModalPage;
use uzdevid\dashboard\widgets\OffCanvasPage\OffCanvasPage;
use uzdevid\dashboard\widgets\OffCanvasPage\OffCanvasPageOptions;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MenuController implements the CRUD actions for Menu model.
 */
class NotificationController extends Controller {

    public function __construct($id, $module, $config = []) {
        parent::__construct($id, $module, $config);

        $this->viewPath = '@vendor/uzdevid/yii2-dashboard-notification/views/notification';
    }

    /**
     * @inheritDoc
     */
    public function behaviors(): array {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'delete' => ['POST'],
                'save-token' => ['POST'],
            ],
        ];

        return $behaviors;
    }

    /**
     * @return array|string
     */
    public function actionIndex(): array|string {
        $notifications = Notification::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['send_time' => SORT_DESC])->all();

        if (Yii::$app->request->isAjax) {
            $offcanvas = OffCanvasPage::options(OffCanvasPageOptions::SIDE_RIGHT);
            $view = $this->renderAjax('offcanvas/index', compact('notifications'));

            foreach ($notifications as $notification) {
                if ($notification->is_read == 0) {
                    $notification->is_read = 1;
                    $notification->save();
                }
            }

            return [
                'success' => true,
                'offcanvas' => $offcanvas,
                'body' => [
                    'title' => ModalPage::title(Yii::t('system.content', 'Notifications'), '<i class="bi bi-bell"></i>'),
                    'view' => $view
                ]
            ];
        }

        return $this->render('index', compact('notifications'));
    }

    /**
     * @return array
     */
    public function actionMiniList(): array {
        $notifications = Notification::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['is_read' => 0])->all();

        $notification_list = array_slice($notifications, 0, 4);

        return [
            'success' => true,
            'body' => [
                'badge' => count($notifications),
                'view' => $this->renderAjax('ajax/mini-list', compact('notification_list'))
            ]
        ];
    }

    public function actionSaveToken() {
        $token = Yii::$app->request->post('token');

        $user = Yii::$app->user->identity;

        $device = Device::find()->where(['access_token' => Yii::$app->request->cookies->get('device_id')])->one();

        $device->notification_token = $token;
        $device->save(false);
    }

    /**
     * @param int $id ID
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Menu {
        if (($model = Menu::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('system.message', 'The requested page does not exist.'));
    }
}
