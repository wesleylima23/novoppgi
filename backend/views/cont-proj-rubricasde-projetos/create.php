<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetos */
$nomeProjeto = Yii::$app->request->get('nomeProjeto');
$idProjeto = Yii::$app->request->get('idProjeto');
$this->title = $nomeProjeto;
$this->params['breadcrumbs'][] = ['label' => "$nomeProjeto", 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-rubricasde-projetos-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'idProjeto'=>$idProjeto,
        'nomeProjeto' =>$nomeProjeto,
        'model' => $model,
        'rubricas' => $rubricas,
    ]) ?>

</div>
