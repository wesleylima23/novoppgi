<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjTransferenciasSaldoRubricas */
$idProjeto = Yii::$app->request->get('idProjeto');

$this->title = 'Realizar nova transferencias saldo entre rubricas';
$this->params['breadcrumbs'][] = ['label' => 'Transferencias Saldo Rubricas', 'url' => ['index', 'idProjeto' => $idProjeto]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-transferencias-saldo-rubricas-create" xmlns="http://www.w3.org/1999/html">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Voltar  ',
        ['index', 'idProjeto' => $idProjeto], ['class' => 'btn btn-warning']) ?>
    </br></br>

    <?= $this->render('..\cont-proj-projetos\dados', [
        'idProjeto' => $idProjeto,
    ]) ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><b>Rubricas do projeto</b></h3>
        </div>
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProviderRubricas,
                //'filterModel' => $searchModel,
                'columns' => [
                    'nomerubrica',
                    'descricao',
                    'valor_total:currency',
                    'valor_gasto:currency',
                    'valor_disponivel:currency',
                ],
            ]); ?>
        </div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'idProjeto' => $idProjeto,
        'rubricas' => $rubricas,
    ]) ?>

</div>
