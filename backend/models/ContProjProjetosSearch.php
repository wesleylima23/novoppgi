<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContProjProjetos;

/**
 * ContProjProjetosSearch represents the model behind the search form about `backend\models\ContProjProjetos`.
 */
class ContProjProjetosSearch extends ContProjProjetos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'coordenador_id', 'agencia_id', 'banco_id'], 'integer'],
            [['nomeprojeto', 'data_inicio', 'data_fim', 'data_fim_alterada', 'agencia', 'conta', 'edital', 'proposta', 'status'], 'safe'],
            [['orcamento', 'saldo'], 'number'],
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
        $query = ContProjProjetos::find();

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
            'orcamento' => $this->orcamento,
            'saldo' => $this->saldo,
            'data_inicio' => $this->data_inicio,
            'data_fim' => $this->data_fim,
            'data_fim_alterada' => $this->data_fim_alterada,
            'coordenador_id' => $this->coordenador_id,
            'agencia_id' => $this->agencia_id,
            'banco_id' => $this->banco_id,
        ]);

        $query->andFilterWhere(['like', 'nomeprojeto', $this->nomeprojeto])
            ->andFilterWhere(['like', 'agencia', $this->agencia])
            ->andFilterWhere(['like', 'conta', $this->conta])
            ->andFilterWhere(['like', 'edital', $this->edital])
            ->andFilterWhere(['like', 'proposta', $this->proposta])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
