<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjBancos */

$this->title = "";//'Update Cont Proj Bancos: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Bancos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar Informações';
?>
<div class="cont-proj-bancos-update">

    <h1><?= Html::encode($model->nome) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
