<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRegistraDatas */

$this->title = $model->evento;
$this->params['breadcrumbs'][] = ['label' => 'Registrar Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-registra-datas-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id], [
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
            'evento',
            'data',
            'projeto_id',
            'observacao',
            'tipo',
        ],
    ]) ?>

</div>
