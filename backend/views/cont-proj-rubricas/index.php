<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContProjRubricasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Gerenciar Rubricas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-rubricas-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Cadastrar Novas Rubricas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'codigo',
            'nome',
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
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
