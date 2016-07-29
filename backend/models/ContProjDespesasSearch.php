<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContProjDespesas;

/**
 * ContProjDespesasSearch represents the model behind the search form about `backend\models\ContProjDespesas`.
 */
class ContProjDespesasSearch extends ContProjDespesas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rubricasdeprojetos_id'], 'integer'],
            [['descricao', 'tipo_pessoa', 'data_emissao', 'ident_nf', 'nf', 'ident_cheque', 'data_emissao_cheque', 'favorecido', 'cnpj_cpf', 'comprovante'], 'safe'],
            [['valor_despesa', 'valor_cheque'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ContProjDespesas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'rubricasdeprojetos_id' => $this->rubricasdeprojetos_id,
            'valor_despesa' => $this->valor_despesa,
            'data_emissao' => $this->data_emissao,
            'data_emissao_cheque' => $this->data_emissao_cheque,
            'valor_cheque' => $this->valor_cheque,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'tipo_pessoa', $this->tipo_pessoa])
            ->andFilterWhere(['like', 'ident_nf', $this->ident_nf])
            ->andFilterWhere(['like', 'nf', $this->nf])
            ->andFilterWhere(['like', 'ident_cheque', $this->ident_cheque])
            ->andFilterWhere(['like', 'favorecido', $this->favorecido])
            ->andFilterWhere(['like', 'cnpj_cpf', $this->cnpj_cpf])
            ->andFilterWhere(['like', 'comprovante', $this->comprovante]);

        return $dataProvider;
    }
}
