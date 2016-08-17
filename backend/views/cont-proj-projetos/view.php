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

echo $model->coordenador." cordenador";
if(isset($mensagem)){
    echo '<script language="javascript">';
    echo 'alert(Não foi possivel excluir os itens)';
    echo '</script>';
}

?>
<div class="cont-proj-projetos-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Rubricas do projeto', ['cont-proj-rubricasde-projetos/index', 'idProjeto' => $model->id, 'nomeProjeto'=>$model->nomeprojeto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Receitas', ['cont-proj-receitas/index', 'idProjeto' => $model->id, 'nomeProjeto'=>$model->nomeprojeto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Despesas', ['cont-proj-despesas/index', 'idProjeto' => $model->id, 'nomeProjeto'=>$model->nomeprojeto], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Transferencia de saldo de rubricas', ['cont-proj-transferencias-saldo-rubricas/index', 'idProjeto' => $model->id, 'nomeProjeto'=>$model->nomeprojeto], ['class' => 'btn btn-primary']) ?>
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
                'attribute' => 'agencias_id',
                'label' => 'Agencia de Fomento',
                'value' => $coordenadores[$model->coordenador_id],
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
