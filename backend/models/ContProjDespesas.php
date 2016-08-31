<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_despesas".
 *
 * @property integer $id
 * @property integer $rubricasdeprojetos_id
 * @property string $descricao
 * @property double $valor_despesa
 * @property string $tipo_pessoa
 * @property string $data_emissao
 * @property string $ident_nf
 * @property string $nf
 * @property string $ident_cheque
 * @property string $data_emissao_cheque
 * @property double $valor_cheque
 * @property string $favorecido
 * @property string $cnpj_cpf
 * @property string $comprovante
 */
class ContProjDespesas extends \yii\db\ActiveRecord
{
    public $nomeRubrica;
    public $codigo;
    public $tipo;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_despesas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rubricasdeprojetos_id', 'descricao', 'valor_despesa', 'tipo_pessoa', 'data_emissao', 'ident_nf', 'valor_cheque'], 'required'],
            [['rubricasdeprojetos_id'], 'integer'],
            [['valor_despesa', 'valor_cheque'], 'number'],
            [['valor_despesa'],'validar_despesa'],
            [['data_emissao', 'data_emissao_cheque'], 'safe'],
            [['descricao', 'favorecido'], 'string', 'max' => 150],
            [['tipo_pessoa'], 'string', 'max' => 11],
            [['ident_nf', 'nf'], 'string', 'max' => 100],
            [['ident_cheque'], 'string', 'max' => 70],
            [['cnpj_cpf'], 'string', 'max' => 20],
            [['comprovante'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rubricasdeprojetos_id' => 'Rubricas',
            'descricao' => 'Descrição',
            'valor_despesa' => 'Valor da Despesa',
            'tipo_pessoa' => 'Tipo  de pessoa',
            'data_emissao' => 'Data Emissao',
            'ident_nf' => 'Tipo de documento fiscal',
            'nf' => 'Nota Fiscal',
            'ident_cheque' => 'Identidade do cheque',
            'data_emissao_cheque' => 'Data de emissao do cheque',
            'valor_cheque' => 'Valor do cheque',
            'favorecido' => 'Favorecido',
            'cnpj_cpf' => 'CNPJ/CPF',
            'comprovante' => 'Comprovante',
            'nomeRubrica' => 'Item de Despêndio'
        ];
    }

    public function validar_despesa($attribute,$params){
        $saldoRubrica = ContProjRubricasdeProjetos::find()->select(["j17_contproj_rubricasdeprojetos.valor_total"])
            ->where("j17_contproj_rubricasdeprojetos.id=$this->rubricasdeprojetos_id")->sum("valor_total");
        $despesas = ContProjDespesas::find()->select(["valor_despesa"])
            ->where("rubricasdeprojetos_id=$this->rubricasdeprojetos_id")->sum("valor_despesa");
        $permitido = $saldoRubrica - $despesas;
        if($this->valor_despesa > $permitido ){
            $permitido = number_format ( $permitido , 2 );
            $messagem = "limite atingido maximo ainda permitido é $permitido";
            $this->addError($attribute, $messagem);
        }
    }


}
