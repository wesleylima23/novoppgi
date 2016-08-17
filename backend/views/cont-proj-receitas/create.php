<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjReceitas */

$this->title = 'Nova receita';
$this->params['breadcrumbs'][] = ['label' => 'Receitas', 'url' => ['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-receitas-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'rubricasdeProjeto' => $rubricasdeProjeto,
        'model' => $model,
    ]) ?>

</div>
