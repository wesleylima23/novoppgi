<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetos */

$idProjeto = Yii::$app->request->get('idProjeto');
$nomeProjeto = Yii::$app->request->get('nomeProjeto');
$this->title = 'Rubricas do Projetos: ' . $nomeProjeto;
$this->params['breadcrumbs'][] = ['label' => "$nomeProjeto", 'url' => ['index', 'idProjeto'=>$idProjeto, 'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
$this->params['breadcrumbs'][] = ['label' => $model->nomerubrica, 'url' => ['view', 'id' => $model->id,'idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
$this->params['breadcrumbs'][] = 'Atualizar Dados';
?>
<div class="cont-proj-rubricasde-projetos-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'rubricas'=>$rubricas,
        'update' => true,
    ]) ?>

</div>
