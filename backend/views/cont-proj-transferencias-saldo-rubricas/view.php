<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjTransferenciasSaldoRubricas */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cont Proj Transferencias Saldo Rubricas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-transferencias-saldo-rubricas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'projeto_id',
            'rubrica_origem',
            'rubrica_destino',
            'valor',
            'data',
            'autorizacao',
        ],
    ]) ?>

</div>
