<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjRubricasdeProjetosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Rubricas do projeto";
//'Cont Proj Rubricasde Projetos';
$coordenador = \yii\helpers\ArrayHelper::map(\app\models\User::find()->orderBy('nome')->all(),
    'id', mb_strimwidth('nome',0,50,"..."));
$this->params['breadcrumbs'][] = $this->title;
//$this->params['breadcrumbs'][] = ['label' => "$nomeProjeto", 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-rubricasde-projetos-index">

    <!--<h1><?= Html::encode("Rubricas de projeto - " . $this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'projeto_id',
                'filter'=>\yii\helpers\ArrayHelper::map(\backend\models\ContProjProjetos::find()->asArray()->all(), 'id', mb_strimwidth('nomeprojeto',0,20,"...")),
                'value'=>'nomeprojeto',
                'contentOptions'=>['style'=>'width: 23%;'],
            ],
            //'rubrica_id',
            [
                'attribute'=>'rubrica_id',
                'filter'=>\yii\helpers\ArrayHelper::map(\backend\models\ContProjRubricas::find()->asArray()->all(), 'id', mb_strimwidth('nome',0,20,"...")),
                'value' =>'nomerubrica',
                'contentOptions'=>['style'=>'width: 23%;'],
            ],
            //'descricao',
            [
                'attribute'=>'descricao',
                'contentOptions'=>['style'=>'width: 23%;'],
            ],
            [
                'attribute'=>'coordenador',
                'filter'=>$coordenador,
                'value'=> function ($model) {
                    $coordenador = \yii\helpers\ArrayHelper::map(\app\models\User::find()->orderBy('nome')->all(),
                        'id', mb_strimwidth('nome',0,50,"..."));
                    return $coordenador[$model->coordenador];
                },
                'contentOptions'=>['style'=>'width: 23%;'],
            ],
            //'valor_disponivel:currency',
            [
                'attribute'=>'valor_disponivel',
                'format'=>'currency',
                'contentOptions'=>['style'=>'width: 50px;'],
            ],
        ],
    ]); ?>

</div>
