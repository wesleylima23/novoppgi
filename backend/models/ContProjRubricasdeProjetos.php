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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'projeto_id' => 'Projeto ID',
            'rubrica_id' => 'Rubrica ID',
            'descricao' => 'Descricao',
            'valor_total' => 'Valor Total',
            'valor_gasto' => 'Valor Gasto',
            'valor_disponivel' => 'Valor Disponivel',
        ];
    }
}
