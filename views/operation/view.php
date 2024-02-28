<?php

use kartik\dialog\Dialog;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Operation */

echo Dialog::widget();

$this->title = Yii::$app->formatter->asInputOrOutput($model->in_out) . " - " . $model->product;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/detailView.css');

?>
<div class="operation-view">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title"><?= Yii::t('app', 'General Data') ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div> <!-- /.card-body -->
                <div class="card-body table-responsive p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'in_out:inputOrOutput',
                            [
                                'attribute' => 'product_id',
                                'value' => $model->product
                            ],
                            'amount',
                            'reason',
                            [
                                'label' => Yii::t('app', 'Employee'),
                                'attribute' => 'employee.full_name',
                            ]
                        ],
                    ]) ?>
                </div><!-- /.card-body -->
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title"><?= Yii::t('app', 'System Data') ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div> <!-- /.card-body -->
                <div class="card-body table-responsive p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'is_deleted:boolean',
                            'created_at:datetime',
                            [
                                'attribute' => 'deleted_at',
                                'format' => 'datetime',
                                'visible' => $model->is_deleted
                            ]
                        ],
                    ]) ?>
                </div><!-- /.card-body -->
            </div>
        </div>
    </div>

    <?php if (!$model->is_deleted) : ?>
        <div class="row">
            <div class="col-12 d-flex justify-content-end">
                <p>
                    <?= Html::a(Yii::t('app', 'Undo'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to undo this operation?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>