<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjRubricasdeProjetos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cont-proj-rubricasde-projetos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'projeto_id')->textInput() ?>

    <?= $form->field($model, 'rubrica_id')->textInput() ?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_total')->textInput() ?>

    <?= $form->field($model, 'valor_gasto')->textInput() ?>

    <?= $form->field($model, 'valor_disponivel')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
