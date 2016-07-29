<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjTransferenciasSaldoRubricasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cont Proj Transferencias Saldo Rubricas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-transferencias-saldo-rubricas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cont Proj Transferencias Saldo Rubricas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'projeto_id',
            'rubrica_origem',
            'rubrica_destino',
            'valor',
            // 'data',
            // 'autorizacao',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
