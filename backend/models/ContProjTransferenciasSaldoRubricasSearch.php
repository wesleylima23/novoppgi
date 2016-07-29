<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContProjTransferenciasSaldoRubricas;

/**
 * ContProjTransferenciasSaldoRubricasSearch represents the model behind the search form about `backend\models\ContProjTransferenciasSaldoRubricas`.
 */
class ContProjTransferenciasSaldoRubricasSearch extends ContProjTransferenciasSaldoRubricas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'projeto_id', 'rubrica_origem', 'rubrica_destino'], 'integer'],
            [['valor'], 'number'],
            [['data', 'autorizacao'], 'safe'],
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
        $query = ContProjTransferenciasSaldoRubricas::find();

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
            'rubrica_origem' => $this->rubrica_origem,
            'rubrica_destino' => $this->rubrica_destino,
            'valor' => $this->valor,
            'data' => $this->data,
        ]);

        $query->andFilterWhere(['like', 'autorizacao', $this->autorizacao]);

        return $dataProvider;
    }
}
