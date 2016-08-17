<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ContProjDespesas */

$this->title = $model->descricao;
$this->params['breadcrumbs'][] = ['label' => 'Despesas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cont-proj-despesas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'rubricasdeprojetos_id',
            'descricao',
            'valor_despesa:currency',
            'tipo_pessoa',
            'data_emissao',
            'ident_nf',
            'nf',
            'ident_cheque',
            'data_emissao_cheque',
            'valor_cheque:currency',
            'favorecido',
            'cnpj_cpf',
            'comprovante',
        ],
    ]) ?>

</div>
