<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjTransferenciasSaldoRubricas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cont-proj-transferencias-saldo-rubricas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'projeto_id')->textInput() ?>

    <?= $form->field($model, 'rubrica_origem')->textInput() ?>

    <?= $form->field($model, 'rubrica_destino')->textInput() ?>

    <?= $form->field($model, 'valor')->textInput() ?>

    <?= $form->field($model, 'data')->textInput() ?>

    <?= $form->field($model, 'autorizacao')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
