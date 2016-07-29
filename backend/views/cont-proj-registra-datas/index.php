<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjRegistraDatasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registrar Datas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-registra-datas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Registrar Nova Data', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'dataProvider' => $projetos,
        'filterModel' => $searchModel,
        'columns' => [
            'evento',
            'data',
            'projeto_id',
            'observacao',
            //// 'tipo',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
