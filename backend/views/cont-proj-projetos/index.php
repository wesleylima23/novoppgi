<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjProjetosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projetos de Pesquisa e desenvolvimento';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-projetos-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Cadastrar Novos Projetos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'nomeprojeto',
            'orcamento:currency',
            'saldo:currency',
            'data_inicio:date',
            // 'data_fim',
            // 'data_fim_alterada',
            // 'coordenador_id',
            // 'agencia_id',
            // 'banco_id',
            // 'agencia',
            // 'conta',
            // 'edital',
            // 'proposta',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
