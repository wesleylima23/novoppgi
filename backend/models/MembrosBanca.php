<?php

namespace app\models;

use Yii;
use yiibr\brvalidator\CpfValidator;

/**
 * This is the model class for table "j17_membrosbanca".
 *
 * @property integer $id
 * @property string $nome
 * @property string $email
 * @property string $filiacao
 * @property string $telefone
 * @property string $cpf
 * @property string $dataCadastro
 * @property integer $idProfessor
 */
class Membrosbanca extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_membrosbanca';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'email', 'filiacao', 'telefone', 'cpf'], 'required'],
            [['dataCadastro'], 'safe'],
            [['idProfessor'], 'integer'],
            [['nome', 'email', 'filiacao'], 'string', 'max' => 100],
            [['telefone'], 'string', 'max' => 20],
            [['cpf'], CpfValidator::className(), 'message' => 'CPF Inválido'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'email' => 'E-mail',
            'filiacao' => 'Filiacão',
            'telefone' => 'Telefone',
            'cpf' => 'CPF',
            'dataCadastro' => 'Data de Cadastro',
            'idProfessor' => 'Id Professor',
        ];
    }
}
