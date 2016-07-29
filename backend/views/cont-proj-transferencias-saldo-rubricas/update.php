<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjTransferenciasSaldoRubricas */

$this->title = 'Update Cont Proj Transferencias Saldo Rubricas: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cont Proj Transferencias Saldo Rubricas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cont-proj-transferencias-saldo-rubricas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
