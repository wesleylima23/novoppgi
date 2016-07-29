<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjRubricasdeProjetosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cont Proj Rubricasde Projetos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-rubricasde-projetos-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Cont Proj Rubricasde Projetos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'projeto_id',
            'rubrica_id',
            'descricao',
            'valor_total',
            // 'valor_gasto',
            // 'valor_disponivel',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
