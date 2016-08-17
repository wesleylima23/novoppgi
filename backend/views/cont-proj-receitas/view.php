<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjReceitas */

$idProjeto = yii::$app->request->get('idProjeto');
$nomeProjeto = yii::$app->request->get('nomeProjeto');

$this->title = $model->tipo ." ". $model->descricao;
$this->params['breadcrumbs'][] = ['label' => 'Receitas', 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
$this->params['breadcrumbs'][] = $this->title;
$rub = \backend\models\ContProjRubricasdeProjetos::find()->orderBy('id')->all();
$rubricas = \yii\helpers\ArrayHelper::map($rub, 'id', 'descricao');
?>
<div class="cont-proj-receitas-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <!--<?= Html::a('Update', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto], ['class' => 'btn btn-primary']) ?>-->
        <?= Html::a('Delete', ['delete', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
                'idProjeto'=> $idProjeto,
                'nomeProjeto' => $nomeProjeto,
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'rubricasdeprojetos_id',
            /*[
                'attribute' => 'rubricasdeprojetos_id',
                'value' => $rubricas[$model->rubricasdeprojetos_id],
            ],*/
            'descricao',
            'valor_receita:currency',
            [
                'attribute'=>'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
        ],
    ]) ?>

</div>
