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
            [['descricao','nomeprojeto','nomerubrica'], 'safe'],
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
        $projeto_id = Yii::$app->request->get('idProjeto');
        $query = ContProjRubricasdeProjetos::find()->select("j17_contproj_projetos.nomeprojeto AS nomeprojeto,
        j17_contproj_rubricas.nome AS nomerubrica,j17_contproj_rubricasdeprojetos.*")
        ->leftJoin("j17_contproj_projetos","j17_contproj_projetos.id=j17_contproj_rubricasdeprojetos.projeto_id")
        ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricas.id=j17_contproj_rubricasdeprojetos.rubrica_id")
        ->where("j17_contproj_rubricasdeprojetos.projeto_id=$projeto_id");
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

        $dataProvider->sort->attributes['nomeprojeto'] = [
            'asc' => ['nomeprojeto' => SORT_ASC],
            'desc' => ['nomeprojeto' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['nomerubrica'] = [
            'asc' => ['nomerubrica' => SORT_ASC],
            'desc' => ['nomerubrica' => SORT_DESC],
        ];
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            //'nomeprojeto' => $this->nomeprojeto,
            //'nomerubrica' => $this->nomerubrica,
            'projeto_id' => $this->projeto_id,
            'rubrica_id' => $this->rubrica_id,
            'valor_total' => $this->valor_total,
            'valor_gasto' => $this->valor_gasto,
            'valor_disponivel' => $this->valor_disponivel,
        ]);

        $query->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'j17_contproj_projetos.nomeprojeto', $this->nomeprojeto])
            ->andFilterWhere(['like', 'j17_contproj_rubricas.nome', $this->nomerubrica]);
        return $dataProvider;
    }
}
