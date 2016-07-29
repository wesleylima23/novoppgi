<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjTransferenciasSaldoRubricas */

$this->title = 'Create Cont Proj Transferencias Saldo Rubricas';
$this->params['breadcrumbs'][] = ['label' => 'Cont Proj Transferencias Saldo Rubricas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-transferencias-saldo-rubricas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
