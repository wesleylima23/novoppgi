<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjReceitasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$nomeProjeto = Yii::$app->request->get('nomeProjeto');
$idProjeto = Yii::$app->request->get('idProjeto');

$this->title = mb_strimwidth("Receitas do projeto: ".$nomeProjeto,0,60,"...");
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="cont-proj-receitas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Cadastrar nova receita', ['create','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto], ['class' => 'btn btn-success']) ?>
    </p>
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
                    'view' => function ($url, $model) use ($idProjeto,$nomeProjeto) {
                        $url .= '&idProjeto=' . $idProjeto.'&nomeProjeto='.$nomeProjeto; //This is where I want to append the $lastAddress variable.
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        //Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ]);
                    },
                    'update' => function ($url, $model) use ($idProjeto,$nomeProjeto) {
                        return false;
                    },
                    'delete' => function ($url, $model) use ($idProjeto,$nomeProjeto) {

                    },
                ],
                //['class' => 'yii\grid\ActionColumn'],
            ],
        ],
    ]); ?>
</div>
