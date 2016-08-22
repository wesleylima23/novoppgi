<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProjetos */

$coordenador = \app\models\User::find()->select("*")->where("id=$model->coordenador_id")->one();
$agencia = \backend\models\ContProjAgencias::find()->select("*")->where("id=$model->agencia_id")->one();
$banco =\backend\models\ContProjBancos::find()->select("*")->where("id=$model->banco_id")->one();

$this->title = $model->nomeprojeto;
$this->params['breadcrumbs'][] = ['label' => 'Projetos de Pesquisa e desenvolvimento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//$coordenadores = ArrayHelper::map(\app\models\User::find()->orderBy('nome')->all(), 'id', 'nome');
//$agencias = ArrayHelper::map(\backend\models\ContProjAgencias::find()->orderBy('nome')->all(), 'id', 'nome');
//$bancos = ArrayHelper::map(\backend\models\ContProjBancos::find()->orderBy('nome')->all(), 'id', 'nome');


?>
<div class="cont-proj-projetos-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
        ['index'], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Rubricas do projeto', ['cont-proj-rubricasde-projetos/index', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Receitas', ['cont-proj-receitas/index', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Despesas', ['cont-proj-despesas/index', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Transferencia de saldo de rubricas', ['cont-proj-transferencias-saldo-rubricas/index', 'idProjeto' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <!--['label' => 'transferencia de rubricas', 'icon' => 'fa fa-calendar',
    'url' => ['cont-proj-rubricasde-projetos/index'], 'visible' => Yii::$app->user->identity->checarAcesso('professor'),],-->
    <!--['label' => 'rubricas de projeto', 'icon' => '', 'url' => ['cont-proj-rubricasde-projetos/index'],
    'visible' => Yii::$app->user->identity->checarAcesso('professor'),],-->
    <?= DetailView::widget([
        'model' => $model,
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
                'label' => 'data_inicio',
                'attribute' => 'data_inicio',
                'value' => date("d/m/Y", strtotime($model->data_inicio)),

            ],
            [
                'attribute' => 'data_fim',
                'value' => date("d/m/Y", strtotime($model->data_fim)),

            ],
            [
                'attribute' => 'agencias_id',
                'label' => 'Agencia de Fomento',
                'value' => $agencia->nome,
            ],
            [
                'attribute' => 'bancos_id',
                'label' => 'Banco',
                'value' => $banco->nome,
            ],
            'agencia',
            'conta',
            [
                'attribute' => 'edital',
                //'value' => "<a href=localhost/novoppgi/backend/web/".$model->edital."' target = '_blank'> Baixar </a>",
                'format'=>'raw',
                'value' => "<a href='".$model->edital."' target = '_blank'> Baixar </a>"
            ],
            [
                'attribute' => 'proposta',
                //'value' => "<a href=localhost/novoppgi/backend/web/".$model->edital."' target = '_blank'> Baixar </a>",
                'format'=>'raw',
                'value' => "<a href='".$model->edital."' target = '_blank'> Baixar </a>"
            ],

            'status',
        ],
    ]) ?>

</div>
