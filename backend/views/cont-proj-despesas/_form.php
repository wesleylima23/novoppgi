<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjDespesas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cont-proj-despesas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rubricasdeprojetos_id')->textInput() ?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_despesa')->textInput() ?>

    <?= $form->field($model, 'tipo_pessoa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_emissao')->textInput() ?>

    <?= $form->field($model, 'ident_nf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ident_cheque')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data_emissao_cheque')->textInput() ?>

    <?= $form->field($model, 'valor_cheque')->textInput() ?>

    <?= $form->field($model, 'favorecido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cnpj_cpf')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comprovante')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
