<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProjetos */

$this->title = $model->nomeprojeto;
$this->params['breadcrumbs'][] = ['label' => 'Projetos de Pesquisa e desenvolvimento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$coordenadores = ArrayHelper::map(\app\models\User::find()->orderBy('nome')->all(), 'id', 'nome');
$agencias = ArrayHelper::map(\backend\models\ContProjAgencias::find()->orderBy('nome')->all(), 'id', 'nome');
$bancos = ArrayHelper::map(\backend\models\ContProjBancos::find()->orderBy('nome')->all(), 'id', 'nome');

?>
<div class="cont-proj-projetos-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id], [
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
            'nomeprojeto',
            'orcamento:currency',
            'saldo:currency',
            'data_inicio:datetime',
            'data_fim:datetime',
            'data_fim_alterada:datetime',
            [
                'attribute' => 'coordenador_id',
                'label' => 'Coordenador',
                'value' => $coordenadores[$model->coordenador_id],
            ],
            [
                'attribute' => 'agencias_id',
                'label' => 'Agencia de Fomento',
                'value' => $agencias[$model->agencia_id],
            ],
            [
                'attribute' => 'bancos_id',
                'label' => 'Banco',
                'value' => $bancos[$model->banco_id],
            ],
            'agencia',
            'conta',
            'edital',
            'proposta',
            'status',
        ],
    ]) ?>

</div>
