<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContProjRubricasdeProjetos;

/**
 * ContProjRubricasdeProjetosSearch represents the model behind the search form about `backend\models\ContProjRubricasdeProjetos`.
 */
class ContProjRubricasdeProjetosSearch extends ContProjRubricasdeProjetos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id', 'rubrica_id'], 'integer'],
            [['descricao'], 'safe'],
            [['valor_total', 'valor_gasto', 'valor_disponivel'], 'number'],
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
        $query = ContProjRubricasdeProjetos::find();

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
            'projeto_id' => $this->projeto_id,
            'rubrica_id' => $this->rubrica_id,
            'valor_total' => $this->valor_total,
            'valor_gasto' => $this->valor_gasto,
            'valor_disponivel' => $this->valor_disponivel,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao]);

        return $dataProvider;
    }
}
