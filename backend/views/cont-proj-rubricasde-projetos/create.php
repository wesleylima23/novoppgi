<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetos */

$this->title = 'Create Cont Proj Rubricasde Projetos';
$this->params['breadcrumbs'][] = ['label' => 'Cont Proj Rubricasde Projetos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-rubricasde-projetos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
