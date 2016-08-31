<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjDespesasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = 'Despesas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-despesas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Cadastrar despesa', ['create', 'idProjeto' => $idProjeto], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('..\cont-proj-projetos\dados', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'ident_nf',
            'favorecido',
            'nomeRubrica',
            //'rubricasdeprojetos_id',
            'data_emissao',
            'descricao',
            'valor_despesa:currency',
            //'tipo_pessoa',
            'ident_cheque',
            // 'data_emissao',
            // 'nf',
            // 'ident_cheque',
            // 'data_emissao_cheque',
            // 'valor_cheque',
            // 'favorecido',
            // 'cnpj_cpf',
            // 'comprovante',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) use ($idProjeto) {
                        $url .= '&idProjeto=' . $idProjeto;
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                    },
                    'update' => function ($url, $model) {
                        return false;
                    },
                    'delete' => function ($url, $model) use ($idProjeto) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id, 'idProjeto' => $idProjeto], [
                            'data' => [
                                'confirm' => 'Deseja realmente remover esta receita?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover Receita'),
                        ]);
                    }
                ],
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
