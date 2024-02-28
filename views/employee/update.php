<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

//$this->title = Yii::t('app', 'Update Employee: {name}', [
//    'name' => $model->full_name,
//]);
$this->title = "Gerenciador de Usuário";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->usual_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<div class="row employee-update">
    <div class="col">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <!--<h3 class="card-title"><?= Html::encode($this->title) ?></h3>-->
                <h3 class="card-title"><label>Perfil</label></h3>
            </div>
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>