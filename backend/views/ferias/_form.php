<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Ferias */
/* @var $form yii\widgets\ActiveForm */

$arrayTipoferias = array ("1" => "Oficial", "2" => "Usufruto"); 

?>

<div class="ferias-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class = "row">
    <?= $form->field($model, 'tipo' , ['options' => ['class' => 'col-md-3']])->dropDownlist($arrayTipoferias, ['prompt' => 'Selecione um tipo de Férias'])->label("<font color='#FF0000'>*</font> <b>Tipo:</b>") ?>
    </div>

    <div class = "row">
	        <?= $form->field($model, 'dataSaida', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
	                'language' => Yii::$app->language,
	                'options' => ['placeholder' => 'Selecione a Data de Saída ...',],
				    'pluginOptions' => [
				        'format' => 'dd-mm-yyyy',
				        'todayHighlight' => true
				    ]
		        ])->label("<font color='#FF0000'>*</font> <b>Data Início:</b>")
		    ?>

	</div>
	<div class = "row">
		    
	        <?= $form->field($model, 'dataRetorno', ['options' => ['class' => 'col-md-3']])->widget(DatePicker::classname(), [
	                'language' => Yii::$app->language,
	                'options' => ['placeholder' => 'Selecione a Data de Retorno ...',],
				    'pluginOptions' => [
				        'format' => 'dd-mm-yyyy',
				        'todayHighlight' => true
				    ]
		        ])->label("<font color='#FF0000'>*</font> <b>Data Término:</b>")
		    ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Registrar Férias' : 'Editar Registro de Férias', ['id' => $model->idusuario,'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
