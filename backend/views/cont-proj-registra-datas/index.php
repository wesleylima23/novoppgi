<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjRegistraDatasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = 'Registrar Datas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-registra-datas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



    <p>
        <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
            ['cont-proj-projetos/view', 'id' => $idProjeto], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Registrar Nova Data', ['create', 'idProjeto' => $idProjeto], ['class' => 'btn btn-success']) ?>
    </p>

    <?= $this->render('..\cont-proj-projetos\dados', [
        'idProjeto' => $idProjeto,
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'dataProvider' => $projetos,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute'=>'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
            'evento',
            /*[
                'attribute' => 'projeto_id',
                'value' => function ($model){
                    $projeto = \backend\models\ContProjProjetos::find()->select("*")->where("id=$model->projeto_id")->one();
                    return $projeto->nomeprojeto;
                },
                 'contentOptions'=>['style'=>'width: 30%;'],
            ],*/
            [
                'attribute' =>  'observacao',
                'contentOptions'=>['style'=>'width: 30%;'],
            ],
            //// 'tipo',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) use ($idProjeto) {
                        $url .= '&idProjeto=' . $idProjeto; //This is where I want to append the $lastAddress variable.
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
                        //Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ]);
                    },
                    'update' => function ($url, $model) use ($idProjeto) {
                        $url .= '&idProjeto=' . $idProjeto; //This is where I want to append the $lastAddress variable.
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                        //Html::a('Atualizar', ['update', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id,'idProjeto'=>$model->projeto_id], [
                            'data' => [
                                'confirm' => 'Deseja realmente remover o lembrete?',
                                'method' => 'post',
                            ],
                            'title' => Yii::t('yii', 'Remover'),
                        ]);
                    }
                ],

            ],
        ],
    ]); ?>
</div>
