<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjBancos */

$this->title = 'Cadastrar Banco';
$this->params['breadcrumbs'][] = ['label' => 'Cont Proj Bancos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-bancos-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
