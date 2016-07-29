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
            [['data'], 'safe'],
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
            'rubricasdeprojetos_id' => 'Rubricasdeprojetos ID',
            'descricao' => 'Descricao',
            'valor_receita' => 'Valor Receita',
            'data' => 'Data',
        ];
    }
}
