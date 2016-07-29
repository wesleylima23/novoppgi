<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRegistraDatas */

$this->title = $model->evento;
$this->params['breadcrumbs'][] = ['label' => 'Registrar Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cont-proj-registra-datas-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'projetos'=>$projetos,
    ]) ?>

</div>
