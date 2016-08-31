<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjTransferenciasSaldoRubricas */
$idProjeto = Yii::$app->request->get('idProjeto');
$origem = \yii\helpers\ArrayHelper::map(\backend\models\ContProjRubricasdeProjetos::find()->where("projeto_id=$idProjeto")->all(), 'id', 'descricao');
$destino = \yii\helpers\ArrayHelper::map(\backend\models\ContProjRubricasdeProjetos::find()->where("projeto_id=$idProjeto")->all(), 'id', 'descricao');
$modelProjeto = \backend\models\ContProjProjetos::find()->where("id=$idProjeto")->one();
$coordenador = \app\models\User::find()->select("*")->where("id=$modelProjeto->coordenador_id")->one();

$this->title = "Transferêmcia de Saldo";
$this->params['breadcrumbs'][] = ['label' => 'Transferencias de Saldo', 'url' => ['index','idProjeto'=>$idProjeto]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-transferencias-saldo-rubricas-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto'=>$idProjeto], ['class' => 'btn btn-warning']) ?>
        <!--<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>-->
        <?= Html::a('Deletar', ['delete', 'id' => $model->id,'idProjeto'=>$idProjeto,'detalhe'=>true], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
            [
                'attribute'=>'nomeRubricaOrigem',
                'value'=>$origem[$model->rubrica_origem]
            ],
            [
                'attribute'=>'nomeRubricaDestino',
                'value'=>$origem[$model->rubrica_destino]
            ],
            'valor:currency',
            'autorizacao',
        ],
    ]) ?>

</div>
