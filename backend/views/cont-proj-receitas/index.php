<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjReceitasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cont Proj Receitas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-receitas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cont Proj Receitas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'rubricasdeprojetos_id',
            'descricao',
            'valor_receita',
            'data',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
