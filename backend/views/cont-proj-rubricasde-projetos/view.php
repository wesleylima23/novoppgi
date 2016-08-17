<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetos */

$nomeProjeto = Yii::$app->request->get('nomeProjeto');
$idProjeto = Yii::$app->request->get('idProjeto');

$this->title = $model->descricao;
$this->params['breadcrumbs'][] = ['label' => 'Rubricas do Projeto', 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
$this->params['breadcrumbs'][] = $this->title;

$rubricas = \yii\helpers\ArrayHelper::map(\backend\models\ContProjRubricas::find()->orderBy('id')->all(), 'id', 'nome');
$projetos = \yii\helpers\ArrayHelper::map(\backend\models\ContProjProjetos::find()->orderBy('id')->all(), 'id', 'nomeprojeto');
?>
<div class="cont-proj-rubricasde-projetos-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php
         $orcamento = \backend\models\ContProjProjetos::find()->where("id=58")->one();
        $total = \backend\models\ContProjRubricasdeProjetos::find()->where("projeto_id=58")->sum("valor_total");
        echo "orcamento = ".$orcamento->orcamento." total= ".$total;
    ?>
    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id, 'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto], [
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
            [
                'attribute' => 'nomerubrica',
                'value' => $rubricas[$model->rubrica_id],
            ],
            [
                'attribute' => 'nomeProjeto',
                'value' => $projetos[$model->projeto_id],
            ],
            //'nomeprojeto',
            //'nomerubrica',
            'descricao',
            'valor_total:currency',
            'valor_gasto:currency',
            'valor_disponivel:currency',
        ],
    ]) ?>

</div>
