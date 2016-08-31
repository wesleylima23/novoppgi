<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ContProjReceitas;

/**
 * ContProjReceitasSearch represents the model behind the search form about `backend\models\ContProjReceitas`.
 */
class ContProjReceitasSearch extends ContProjReceitas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rubricasdeprojetos_id'], 'integer'],
            [['descricao', 'data','tipo','nomeRubrica','codigo'], 'safe'],
            [['valor_receita'], 'number'],
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
        $query = ContProjReceitas::find()->select("j17_contproj_receitas.*, j17_contproj_rubricas.codigo as codigo, 
        j17_contproj_rubricas.nome as nomeRubrica, j17_contproj_rubricas.tipo as tipo ")
        ->leftJoin("j17_contproj_rubricasdeprojetos","j17_contproj_receitas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id")
        ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricasdeprojetos.rubrica_id = j17_contproj_rubricas.id")
        ->where("j17_contproj_rubricasdeprojetos.projeto_id = $projeto_id")->orderBy("tipo");

        /*$projeto_id = Yii::$app->request->get('idProjeto');
        $query = ContProjRubricasdeProjetos::find()->select("j17_contproj_projetos.nomeprojeto AS nomeprojeto,
        j17_contproj_rubricas.nome AS nomerubrica,j17_contproj_rubricasdeprojetos.*")
            ->leftJoin("j17_contproj_projetos","j17_contproj_projetos.id=j17_contproj_rubricasdeprojetos.projeto_id")
            ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricas.id=j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id=$projeto_id");*/

        /*SELECT j17_contproj_receitas.*, j17_contproj_rubricas.codigo as codigo,  j17_contproj_rubricas.nome as nomeRubrica
        FROM j17_contproj_receitas
        JOIN j17_contproj_rubricasdeprojetos
        ON j17_contproj_receitas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id
        JOIN j17_contproj_rubricas
        ON j17_contproj_rubricasdeprojetos.rubrica_id = j17_contproj_rubricas.id
        WHERE j17_contproj_rubricasdeprojetos.projeto_id = 50*/

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

        $dataProvider->sort->attributes['tipo'] = [
            'asc' => ['j17_contproj_rubricas.tipo' => SORT_ASC],
            'desc' => ['j17_contproj_rubricas.tipo' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['nomeRubrica'] = [
            'asc' => ['j17_contproj_rubricas.nome' => SORT_ASC],
            'desc' => ['j17_contproj_rubricas.nome' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['codigo'] = [
            'asc' => ['j17_contproj_rubricas.codigo' => SORT_ASC],
            'desc' => ['j17_contproj_rubricas.codigo' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'rubricasdeprojetos_id' => $this->rubricasdeprojetos_id,
            'valor_receita' => $this->valor_receita,
            'data' => $this->data,
        ]);

        $query->andFilterWhere(['like', 'j17_contproj_receitas.descricao', $this->descricao])
            ->andFilterWhere(['like', 'j17_contproj_rubricas.tipo', $this->tipo])
            ->andFilterWhere(['like', 'j17_contproj_rubricas.nome', $this->nomeRubrica])
            ->andFilterWhere(['like', 'j17_contproj_rubricas.codigo', $this->codigo])
            ->andFilterWhere(['like', 'j17_contproj_receitas.data', $this->data])
            ->andFilterWhere(['<=', 'j17_contproj_receitas.valor_receita', $this->valor_receita]);
        return $dataProvider;
    }
}
