<?php

namespace app\models;

use Yii;
use yiibr\brvalidator\CpfValidator;


class Aluno extends \yii\db\ActiveRecord
{
    public $siglaLinhaPesquisa;
    public $corLinhaPesquisa;
    public $nomeOrientador;
    public $icone;
    public $username;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'j17_aluno';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nome', 'email', 'curso', 'cpf', 'cep', 'endereco', 'datanascimento', 'sexo', 'uf', 'cidade', 'bairro', 'telresidencial', 'regime', 'matricula', 'orientador', 'dataingresso', 'curso', 'area'], 'required'],
            [['financiadorbolsa', 'dataimplementacaobolsa'], 'required', 'when' => function ($model) { return $model->bolsista; }, 'whenClient' => "function (attribute, value) {
                    return $('#form_bolsista').val() == '1';
                }"],
            [['area', 'curso', 'regime', 'status', 'egressograd', 'idUser', 'orientador'], 'integer'],
            [['nome'], 'string', 'max' => 60],
            [['email'],'email'],
            [['cidade'], 'string', 'max' => 40],
            [['senha'], 'string', 'max' => 255],
            [['matricula'], 'string', 'max' => 15],
            [['endereco'], 'string', 'max' => 160],
            [['bairro'], 'string', 'max' => 50],
            [['uf'], 'string', 'max' => 5],
            [['cep', 'conceitoExameProf'], 'string', 'max' => 9],
            [['datanascimento', 'dataExameProf'], 'string', 'max' => 10],
            [['sexo'], 'string', 'max' => 1],
            [['cpf'], CpfValidator::className(), 'message' => 'CPF Inválido'],
            [['telresidencial', 'telcelular'], 'string', 'max' => 18],
            [['bolsista'], 'string', 'max' => 3],
            [['financiadorbolsa'], 'string', 'max' => 45],
            [['idiomaExameProf'], 'string', 'max' => 20],
            [['cursograd', 'instituicaograd'], 'string', 'max' => 100],
            [['sede'], 'string', 'max' => 2],
            [['dataingresso', 'dataimplementacaobolsa'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nome' => 'Nome',
            'email' => 'Email',
            'senha' => 'Senha',
            'matricula' => 'Matrícula',
			'name' => 'Nome do Aluno',
            'area' => 'Linha de Pesquisa',
            'curso' => 'Curso',
            'endereco' => 'Endereço',
            'bairro' => 'Bairro',
            'cidade' => 'Cidade',
            'uf' => 'UF',
            'cep' => 'CEP',
            'datanascimento' => 'Data de Nascimento',
            'sexo' => 'Sexo',
            'cpf' => 'CPF',
            'rg' => 'RG',
            'telresidencial' => 'Telefone Principal',
            'telcelular' => 'Telefone Alternativo',
            'regime' => 'Regime',
            'bolsista' => 'Bolsista',
            'status' => 'Status',
            'dataingresso' => 'Data de Ingresso',
            'idiomaExameProf' => 'Idioma do Exame de Proficiência',
            'conceitoExameProf' => 'Conceito do Exame de Proficiência',
            'dataExameProf' => 'Data do Exame de Proficiência',
            'cursograd' => 'Curso da Graduação',
            'instituicaograd' => 'Instituicão da Graduação',
            'egressograd' => 'Ano de Egresso',
            'orientador' => 'Orientador',
            'anoconclusao' => 'Ano de Conclusão',
            'sede' => 'Sede',
            'financiadorbolsa' => 'Financiador da Bolsa',
            'dataimplementacaobolsa' => 'Início da Vigência',
            'orientador1.nome' => 'Orientador',
        ];
    }

    public function beforeSave(){
        if($this->dataingresso) $this->dataingresso = date('Y-m-d', strtotime($this->dataingresso));
		if($this->datanascimento) $this->datanascimento = date('Y-m-d', strtotime($this->datanascimento));
        if($this->dataExameProf) $this->dataExameProf =  date('Y-m-d', strtotime($this->dataExameProf));
		if($this->dataimplementacaobolsa) $this->dataimplementacaobolsa =  date('Y-m-d', strtotime($this->dataimplementacaobolsa));
		
        return true;
    }

    public function getlinhaPesquisa()
    {
        return $this->hasOne(LinhaPesquisa::className(), ['id' => 'area']);
    }

    public function getOrientador1()
    {
        return $this->hasOne(User::className(), ['id' => 'orientador']);
    }

    public function orientados($idusuario){
       $alunos = Aluno::find()->where(["orientador" => $idusuario])->all();
       return $alunos;
    }

}
