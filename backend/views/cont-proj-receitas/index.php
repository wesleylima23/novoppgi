<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjReceitasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$idProjeto = Yii::$app->request->get('idProjeto');
$modelProjeto = \backend\models\ContProjProjetos::find()->where("id=$idProjeto")->one();
$coordenador = \app\models\User::find()->select("*")->where("id=$modelProjeto->coordenador_id")->one();

$this->title = "Receitas do projeto";
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-receitas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Cadastrar nova receita', ['create','idProjeto'=>$idProjeto], ['class' => 'btn btn-success']) ?>
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
	
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'codigo',
            'nomeRubrica',
            [
                'attribute'=>'descricao',
                'format'=>'text',
                'contentOptions'=>['style'=>'max-width: 40%;'],
            ],
            'valor_receita:currency',
            [
                'attribute'=>'data',
                'format' => ['date', 'php:d/m/Y'],
            ],

            //'tipo',
            [
                'attribute' => 'tipo',
                'filter' => Html::activeDropDownList($searchModel, 'tipo', \yii\helpers\ArrayHelper::map(\backend\models\ContProjRubricas::find()->distinct()->all(), 'tipo', 'tipo'),[  'class'=>'form-control','prompt' => 'Todos os tipos ']),

                'format' => 'html',
                'value' => function ($model){
                    return $model->tipo;
                    //return "<span class='fa ". $model->tipo ." fa-lg'/> ". $model->siglaLinhaPesquisa;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) use ($idProjeto) {
                        $url .= '&idProjeto=' . $idProjeto; //This is where I want to append the $lastAddress variable.
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        //Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ]);
                    },
                    'update' => function ($url, $model) {
                        return false;
                    },
                    'delete' => function ($url, $model) use ($idProjeto) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id,'idProjeto'=>$idProjeto], [
                            'data' => [
                                'confirm' => 'Deseja realmente remover esta rubrica?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover Aluno'),
                        ]);
                    }
                ],
                //['class' => 'yii\grid\ActionColumn'],
            ],
        ],
    ]); ?>

    


</div>
