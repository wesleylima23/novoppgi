<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "j17_banca_has_membrosbanca".
 *
 * @property integer $banca_id
 * @property integer $membrosbanca_id
 * @property string $funcao
 * @property string $passagem
 */
class Banca extends \yii\db\ActiveRecord
{

    public $membro_nome;
    public $membro_filiacao;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_banca_has_membrosbanca';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['membrosbanca_id'], 'required'],
            [['membrosbanca_id'], 'integer'],
            [['funcao'], 'string'],
            [['passagem'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'banca_id' => 'Banca ID',
            'membrosbanca_id' => 'Membrosbanca ID',
            'funcao' => 'Funcao',
            'passagem' => 'Passagem',
        ];
    }


    public function getFuncaoMembro(){

        if($this->funcao == "P"){
            return "Presidente";
        }
        else if($this->funcao == "I"){

            return "Membro Interno";
        }
        else{
            return "Membro Externo";
        }
    }
}
