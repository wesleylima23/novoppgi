<?php
//$items = ['Aula' => 'Aula', 'Defesa' => 'Defesa', 'Exame' => 'Exame', 'Reunião' => 'Reunião'];
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\datecontrol\DateControl;
/* @var $this yii\web\View */
/* @var $model backend\models\ContProjProjetos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cont-proj-projetos-form">


    <?php $form = ActiveForm::begin(([
        'options' => [ 'enctype' => 'multipart/form-data']
    ])); ?>

    <?= $form->field($model, 'nomeprojeto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'orcamento')->textInput() ?>

    <?= $form->field($model, 'saldo')->textInput() ?>

    <!--<?= $form->field($model, 'data_inicio')->textInput() ?>-->

    <div class="row">
        <?= $form->field($model, 'data_inicio', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
            'language' => Yii::$app->language,
            'options' => ['placeholder' => 'Selecione a Data Inicial ...',],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true
            ]
        ])->label("<font color='#FF0000'>*</font> <b>Data Inicial:</b>")
        ?>
    </div>

    <!--<?= $form->field($model, 'data_fim')->textInput() ?>-->

    <div class="row">
        <?= $form->field($model, 'data_fim', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
            'language' => Yii::$app->language,
            'options' => ['placeholder' => 'Selecione a Data Final ...',],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true
            ]
        ])->label("<font color='#FF0000'>*</font> <b>Data Final:</b>")
        ?>
    </div>

    <!--<?= $form->field($model, 'data_fim_alterada')->textInput() ?>-->

    <div class="row">
        <?= $form->field($model, 'data_fim_alterada', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
            'language' => Yii::$app->language,
            'options' => ['placeholder' => 'Selecione a Data Final Alterada ...',],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true
            ]
        ])->label("<font color='#FF0000'>*</font> <b>Data Final Alterada:</b>")
        ?>
    </div>

    <?= $form->field($model, 'coordenador_id')->dropDownList($coordenadores, ['prompt' => 'Selecione um Coordenador']) ?>

    <?= $form->field($model, 'agencia_id')->dropDownList($agencias, ['prompt' => 'Selecione uma Agência de Fomento']) ?>

    <?= $form->field($model, 'banco_id')->dropDownList($bancos, ['prompt' => 'Selecione um Banco']) ?>

    <?= $form->field($model, 'agencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'conta')->textInput(['maxlength' => true]) ?>

    <!--<?= $form->field($model, 'edital')->textInput(['maxlength' => true]) ?>-->
    <!--<?= $form->field($model, 'proposta')->textInput(['maxlength' => true]) ?>-->

    <?= $form->field($model, 'editalArquivo', ['options' => ['class' => 'col-md-6']])->fileInput(['accept' => '.pdf'])->label("Edital do Projeto: ")?>


    <?= $form->field($model, 'propostaArquivo', ['options' => ['class' => 'col-md-6']])->fileInput(['accept' => '.pdf'])->label("Proposta do Projeto: ")?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Cadastrar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
