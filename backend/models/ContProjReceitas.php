<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_receitas".
 *
 * @property integer $id
 * @property integer $rubricasdeprojetos_id
 * @property string $descricao
 * @property double $valor_receita
 * @property string $data
 */
class ContProjReceitas extends \yii\db\ActiveRecord
{

    public $nomeRubrica;
    public $codigo;
    public $tipo;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_receitas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rubricasdeprojetos_id', 'descricao', 'valor_receita', 'data'], 'required'],
            [['rubricasdeprojetos_id'], 'integer'],
            [['valor_receita'], 'number'],
            ['valor_receita','validar_receita'],
            [['data','tipo'], 'safe'],
            [['descricao'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo'=>'Tipo',
            'rubricasdeprojetos_id' => 'Rubricas',
            'descricao' => 'Descricao',
            'valor_receita' => 'Valor Receita',
            'data' => 'Data',
        ];
    }

    public function validar_receita($attribute,$params){
        $saldoRubrica = ContProjRubricasdeProjetos::find()->select(["j17_contproj_rubricasdeprojetos.valor_total"])
           ->where("j17_contproj_rubricasdeprojetos.id=$this->rubricasdeprojetos_id")->sum("valor_total");
        $receitas = ContProjReceitas::find()->select(["j17_contproj_rubricasdeprojetos.valor_receita"])
            ->where("rubricasdeprojetos_id=$this->rubricasdeprojetos_id")->sum("valor_receita");
        //$rubricaValorToral = 2000;

        $permitido = $saldoRubrica - $receitas;
        $messagem = "limite atingido maximo ainda permitido é $permitido";
        if($this->valor_receita > $permitido ){
            $permitido = number_format ( $permitido , 2 );
            $this->addError($attribute, $messagem);
        }
        /*$projeto = \backend\models\ContProjProjetos::find()->where("id=$this->projeto_id")->one();
        $valorRubricas =  \backend\models\ContProjRubricasdeProjetos::find()->where("projeto_id=58")->sum("valor_total");

        $permitido = $projeto->orcamento - $valorRubricas;
        if( $permitido >0){
            $permitido = number_format ( $permitido , 2 );
            $messagem = "O Valor disponivel não pode ser maior que o orçamento do projeto o valor ainda disponivél para cadastro é R$ $permitido";
        }
        $total = $this->$attribute + $valorRubricas;
        if($total > $projeto->orcamento ){
            $this->addError($attribute, $messagem);
        }*/
    }
}
