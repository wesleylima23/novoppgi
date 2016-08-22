<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjReceitas */

$idProjeto = Yii::$app->request->get('idProjeto');
$modelProjeto = \backend\models\ContProjProjetos::find()->where("id=$idProjeto")->one();
$coordenador = \app\models\User::find()->select("*")->where("id=$modelProjeto->coordenador_id")->one();

$this->title = $model->tipo ." ". $model->descricao;
$this->params['breadcrumbs'][] = ['label' => 'Receitas', 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
$this->params['breadcrumbs'][] = $this->title;
$rub = \backend\models\ContProjRubricasdeProjetos::find()->orderBy('id')->all();
$rubricas = \yii\helpers\ArrayHelper::map($rub, 'id', 'descricao');
?>
<div class="cont-proj-receitas-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto'=>$idProjeto], ['class' => 'btn btn-warning']) ?>
        <!--<?= Html::a('Update', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto], ['class' => 'btn btn-primary']) ?>-->
        <?= Html::a('Delete', ['delete', 'id' => $model->id,'idProjeto'=>$idProjeto], [
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
            //'id',
            //'rubricasdeprojetos_id',
            [
                'attribute' => 'rubricasdeprojetos_id',
                'value' => $rubricas[$model->rubricasdeprojetos_id],
            ],
            'descricao',
            'valor_receita:currency',
            [
                'attribute'=>'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
        ],
    ]) ?>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Dados do Projeto</b></h3>
        </div>
        <div class="panel-body">
            <?= \yii\widgets\DetailView::widget([
                'model' => $modelProjeto,
                'attributes' => [
                    //'coordenador',
                    'nomeprojeto',
                    [
                        'attribute' => 'coordenador_id',
                        'value' => $coordenador->nome,
                    ],
                    'orcamento:currency',
                    'saldo:currency',
                    [
                        'attribute' => 'data_inicio',
                        'value' => date("d/m/Y", strtotime($modelProjeto->data_inicio)),

                    ],
                    [
                        'attribute' => 'data_fim',
                        'value' => date("d/m/Y", strtotime($modelProjeto->data_fim)),

                    ],
                ],
            ]) ?>
        </div>
    </div>


</div>
