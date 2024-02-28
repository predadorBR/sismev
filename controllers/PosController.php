<?php

namespace app\controllers;

use app\components\factories\OrderFactory;
use app\components\factories\SaleFactory;
use app\components\Seller;
use app\models\Order;
use app\models\OrderItem;
use app\models\Pay;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ConflictHttpException;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class PosController extends Controller
{
    public $layout = 'main_alt';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'checkout', 'complete'],
                        'allow' => true,
                        'roles' => ['cashier']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'complete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($code = null)
    {
        if (is_null($code)) {
            return $this->redirect([
                'index',
                'code' => OrderFactory::create()->code
            ]);
        }

        $item = new OrderItem();
        $item->order_id = $this->findOrderUnsold($code)->id;

        return $this->render('index', [
            'item' => $item,
            'code' => $code,
        ]);
    }

    public function actionCheckout($code)
    {
        $order = $this->findOrderUnsold($code);

        if (!$order->orderItems) {
            Yii::$app->session->setFlash('info', Yii::t('app', "You cannot go to checkout without at least one item in the order."));
            return $this->redirect(['index', 'code' => $order->code]);
        }

        $sale = $order->sale;
        if ($sale === null) {
            $sale = SaleFactory::create([
                'order_id' => $order->id,
                'employee_id' => Yii::$app->user->id,
            ]);
        }

        $payment = new Pay();
        $payment->sale_id = $sale->id;

        return $this->render('/checkout/index', [
            'pay' => $payment,
            'code' => $code
        ]);
    }

    public function actionComplete($code)
    {
        try {
            $sale = $this->findOrderUnsold($code)->sale;
            $sale->trigger(Seller::EVENT_COMPLETE_SALE);

            return $this->redirect(['sale/invoice', 'id' => $sale->id]);
        } catch (HttpException $e) {
            Yii::$app->session->setFlash('warning', $e->getMessage());

            return $this->redirect([
                'checkout',
                'code' => $code
            ]);
        }
    }

    protected function findOrderUnsold($code)
    {
        $model = Order::findByCode($code);

        if ($model === null) {
            throw new NotFoundHttpException(Yii::t('app', 'The requested order does not exist.'));
        } else if ($model->sale !== null && $model->sale->is_sold) {
            throw new ConflictHttpException(Yii::t('app', 'The order has already been sold.'));
        }

        return $model;
    }
}
