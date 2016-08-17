<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjRubricasdeProjetosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$nomeProjeto = Yii::$app->request->get('nomeProjeto');
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = mb_strimwidth("Rubricas do projeto - ".$nomeProjeto,0,60,"...");
//'Cont Proj Rubricasde Projetos';
$this->params['breadcrumbs'][] = $this->title;
//$this->params['breadcrumbs'][] = ['label' => "$nomeProjeto", 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-rubricasde-projetos-index">

    <!--<h1><?= Html::encode("Rubricas de projeto - ".$this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Cadastrar novo rubrica para o projeto', ['create','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'projeto_id',
            //'rubrica_id',
            'nomerubrica',
            'descricao',
            //'nomeprojeto',
            'valor_total:currency',
            'valor_gasto:currency',
            'valor_disponivel:currency',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) use ($idProjeto,$nomeProjeto) {
                        $url .= '&idProjeto=' . $idProjeto.'&nomeProjeto='.$nomeProjeto; //This is where I want to append the $lastAddress variable.
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        //Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ]);
                    },
                    'update' => function ($url, $model) use ($idProjeto,$nomeProjeto) {
                        $url .= '&idProjeto=' . $idProjeto.'&nomeProjeto='.$nomeProjeto; //This is where I want to append the $lastAddress variable.
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                        //Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ]);
                    },
                    'delete' => function ($url, $model) use ($idProjeto,$nomeProjeto) {

                    },
                ],

            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
