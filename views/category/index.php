<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Categories');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/modal.js', ['depends' => [yii\web\JqueryAsset::class]]);

$gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    'name',
    [
        'class' => 'kartik\grid\ActionColumn',
        'deleteOptions' => [
            'data-confirm' => Yii::t('app', 'Are you sure you want to delete this category?'),
        ],
        'buttons' => [
            'update' => function($url, $model) {
                return Html::a(
                    '<span class="fas fa-pencil-alt" aria-hidden="true"></span>',
                    $url,
                    ['value' => $url, 'title' => Yii::t('kvgrid', 'Update'), 'class' => 'btn-modal', 'data-toggle' => 'modal']
                );
            }
        ],
        'width' => '100px',
    ],
];
?>
<div class="category-index">
    <div class="row">
        <div class="col">
            <?= $this->render('@app/views/layouts/modal.php', ['options' => ['title' => Yii::t('app', 'Category')]]) ?>

            <?= GridView::widget([
                'id' => 'grid_categories',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => $gridColumns,
                'responsive' => true,
                'responsiveWrap' => false,
                'hover' => true,
                'toolbar' =>  [
                    [
                        'content' =>
                            Html::button('<i class="fas fa-plus"></i>', [
                                'value' => Url::to(['create']),
                                'class' => 'btn btn-success btn-modal',
                                'title' => Yii::t('app', 'Add Category'),
                            ]),
                        'options' => ['class' => 'btn-group mr-2']
                    ],
                    '{toggleData}',
                ],
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                    'heading' => Html::encode($this->title),
                    'afterOptions' => ['class' => ''],
                ],
            ]); ?>
        </div>
    </div>
</div>
