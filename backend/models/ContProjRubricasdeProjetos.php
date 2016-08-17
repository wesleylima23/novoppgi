<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_rubricasdeprojetos".
 *
 * @property integer $id
 * @property integer $projeto_id
 * @property integer $rubrica_id
 * @property string $descricao
 * @property double $valor_total
 * @property double $valor_gasto
 * @property double $valor_disponivel
 */
class ContProjRubricasdeProjetos extends \yii\db\ActiveRecord
{

    public $nomeprojeto;
    public $nomerubrica;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_rubricasdeprojetos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto_id', 'rubrica_id', 'descricao', 'valor_total', 'valor_gasto'], 'required'],
            [['projeto_id', 'rubrica_id'], 'integer'],
            [['valor_total', 'valor_gasto', 'valor_disponivel'], 'number'],
            [['descricao'], 'string', 'max' => 200],
            ['valor_disponivel','validar_valor'],
            //['valor_total','checarOrcamento'],
            ['valor_total','checarOrcamentoTotal'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomeprojeto' => 'Projeto',
            'nomerubrica' => 'Rubrica',
            'projeto_id' => 'Projeto',
            'rubrica_id' => 'Rubrica',
            'descricao' => 'Descrição',
            'valor_total' => 'Valor Previsto',
            'valor_gasto' => 'Valor Gasto',
            'valor_disponivel' => 'Saldo Inicial',
        ];
    }

    public function validar_valor($attribute,$params){
        if($this->$attribute > $this->valor_total ){
            $this->addError($attribute, 'O Saldo inicial não pode ser maior que o valor previsto para a rubrica');
        }
    }

    public function checarOrcamento($attribute,$params){
        $orcamento = \backend\models\ContProjProjetos::find()->where("id=$this->projeto_id")->one();
        //echo $orcamento->orcamento;
        if($this->$attribute > $orcamento->orcamento ){
            $this->addError($attribute, 'O Valor disponivel não pode ser maior que o orçamento do projeto');
        }
    }

    public function checarOrcamentoTotal($attribute,$params){
        $projeto = \backend\models\ContProjProjetos::find()->where("id=$this->projeto_id")->one();
        $valorRubricas =  \backend\models\ContProjRubricasdeProjetos::find()->where("projeto_id=$this->projeto_id")->sum("valor_total");

        $permitido = $projeto->orcamento - $valorRubricas;
        if( $permitido >0){
            $permitido = number_format ( $permitido , 2 );
            $messagem = "O Valor disponivel não pode ser maior que o orçamento do projeto o valor ainda disponivél para cadastro é R$ $permitido";
        }else{
            $messagem = "limite atingido";
        }

        $total = $this->$attribute + $valorRubricas;

        echo '<script language="javascript">';
        echo 'alert("'.$projeto->orcamento." ".$total.'")';
        echo '</script>';


        if ($total > $projeto->orcamento) {
            $this->addError($attribute, $messagem);
        }
    }
}
