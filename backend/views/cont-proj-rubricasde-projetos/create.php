<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetos */


$idProjeto = Yii::$app->request->get('idProjeto');
$modelProjeto = \backend\models\ContProjProjetos::find()->where("id=$idProjeto")->one();
$coordenador = \app\models\User::find()->select("*")->where("id=$modelProjeto->coordenador_id")->one();

$this->title = $modelProjeto->nomeprojeto;

$this->params['breadcrumbs'][] = ['label' => "$modelProjeto->nomeprojeto", 'url' => ['index','idProjeto'=>$idProjeto]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-rubricasde-projetos-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->



    <?= $this->render('_form', [
        'idProjeto'=>$idProjeto,
        'model' => $model,
        'rubricas' => $rubricas,
    ]) ?>

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

</div>
