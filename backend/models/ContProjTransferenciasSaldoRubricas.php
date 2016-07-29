<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "j17_contproj_transferenciassaldorubricas".
 *
 * @property integer $id
 * @property integer $projeto_id
 * @property integer $rubrica_origem
 * @property integer $rubrica_destino
 * @property double $valor
 * @property string $data
 * @property string $autorizacao
 */
class ContProjTransferenciasSaldoRubricas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_contproj_transferenciassaldorubricas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projeto_id', 'rubrica_origem', 'rubrica_destino', 'valor', 'data', 'autorizacao'], 'required'],
            [['projeto_id', 'rubrica_origem', 'rubrica_destino'], 'integer'],
            [['valor'], 'number'],
            [['data'], 'safe'],
            [['autorizacao'], 'string', 'max' => 100],
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
            'rubrica_origem' => 'Rubrica Origem',
            'rubrica_destino' => 'Rubrica Destino',
            'valor' => 'Valor',
            'data' => 'Data',
            'autorizacao' => 'Autorizacao',
        ];
    }
}
