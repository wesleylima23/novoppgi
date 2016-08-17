<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjDespesasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$nomeProjeto = Yii::$app->request->get('nomeProjeto');
$idProjeto = Yii::$app->request->get('idProjeto');

$this->title = 'Despesas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-despesas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Cadastrar nova despesa', ['create','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'rubricasdeprojetos_id',
            'descricao',
            'valor_despesa:currency',
            'tipo_pessoa',
            // 'data_emissao',
            // 'ident_nf',
            // 'nf',
            // 'ident_cheque',
            // 'data_emissao_cheque',
            // 'valor_cheque',
            // 'favorecido',
            // 'cnpj_cpf',
            // 'comprovante',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
