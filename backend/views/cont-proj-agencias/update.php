<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjAgencias */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Gerenciar Agências', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar Informações';
?>
<div class="cont-proj-agencias-update">

    <!--<h1><?= Html::encode($model->nome) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
