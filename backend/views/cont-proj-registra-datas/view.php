<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \backend\models\ContProjProjetos;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRegistraDatas */
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = $model->evento;
$this->params['breadcrumbs'][] = ['label' => 'Registrar Datas', 'url' => ['index','idProjeto'=>$idProjeto]];
$this->params['breadcrumbs'][] = $this->title;
$projetos = ArrayHelper::map(ContProjProjetos::find()->orderBy('id')->all(), 'id', 'nomeprojeto');
?>
<div class="cont-proj-registra-datas-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['index', 'idProjeto' => $idProjeto], ['class' => 'btn btn-warning']) ?>
    </p>
    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id, 'idProjeto' => $idProjeto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id, 'idProjeto' => $idProjeto, 'detalhe' => true], [
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
            [
                'attribute' => 'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
            [
                'attribute' => 'projeto_id',
                'value' => $projetos[$model->projeto_id],
                'contentOptions' => ['style' => 'width: 30%;'],
            ],
            'observacao',
            'tipo',
        ],
    ]) ?>

</div>
