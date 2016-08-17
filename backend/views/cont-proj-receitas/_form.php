<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
/* @var $this yii\web\View */
/* @var $model backend\models\ContProjReceitas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cont-proj-receitas-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--<?= $form->field($model, 'rubricasdeprojetos_id')->textInput() ?>-->
    <?= $form->field($model, 'rubricasdeprojetos_id')->dropDownList($rubricasdeProjeto, ['prompt' => 'Selecione uma rubrica']) ?>

    <?= $form->field($model, 'descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_receita')->widget(\kartik\money\MaskMoney::classname(), [
        'pluginOptions' => [
            'value' => 0.00,
            'prefix' => 'R$ ',
            'suffix' => '',
            'allowNegative' => false
        ]
    ]); ?>

    <!--<?= $form->field($model, 'data')->textInput() ?>-->

    <!--<div class="row">
        <?= $form->field($model, 'data', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
            'language' => 'pt-BR',
            'options' => ['placeholder' => 'Escolha uma data ...',],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true
            ]
        ])->label("<font color='#FF0000'>*</font> <b>Data:</b>")
        ?>
    </div>-->
   <div class="row">
        <?= $form->field($model, 'data', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
            'language' => 'pt-BR',
            'options' => ['placeholder' => 'Selecione a Data Inicial ...',],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true
            ]
        ])->label("<font color='#FF0000'>*</font> <b>Data Inicial:</b>")
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
