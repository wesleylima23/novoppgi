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
            [['rubricasdeprojetos_id', 'descricao', 'valor_despesa', 'tipo_pessoa', 'data_emissao', 'ident_nf', 'nf', 'ident_cheque', 'data_emissao_cheque', 'valor_cheque', 'favorecido', 'cnpj_cpf', 'comprovante'], 'required'],
            [['rubricasdeprojetos_id'], 'integer'],
            [['valor_despesa', 'valor_cheque'], 'number'],
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
            'rubricasdeprojetos_id' => 'Rubricasdeprojetos ID',
            'descricao' => 'Descricao',
            'valor_despesa' => 'Valor Despesa',
            'tipo_pessoa' => 'Tipo Pessoa',
            'data_emissao' => 'Data Emissao',
            'ident_nf' => 'Ident Nf',
            'nf' => 'Nf',
            'ident_cheque' => 'Ident Cheque',
            'data_emissao_cheque' => 'Data Emissao Cheque',
            'valor_cheque' => 'Valor Cheque',
            'favorecido' => 'Favorecido',
            'cnpj_cpf' => 'Cnpj Cpf',
            'comprovante' => 'Comprovante',
        ];
    }
}
