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
            [['data', 'autorizacao','nomeprojeto','nomeRubricaOrigem','nomeRubricaDestino'], 'safe'],
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
        /*
        SELECT j17_contproj_transferenciassaldorubricas.*, j17_contproj_rubricasdeprojetos.descricao as Destino, j17_contproj_projetos.nomeprojeto as nomeprojeto
        FROM `j17_contproj_transferenciassaldorubricas`
        JOIN j17_contproj_rubricasdeprojetos
        ON j17_contproj_transferenciassaldorubricas.rubrica_destino =  j17_contproj_rubricasdeprojetos.id
        JOIN j17_contproj_projetos
        ON j17_contproj_transferenciassaldorubricas.projeto_id = j17_contproj_projetos.id
         */
        /*
         * $query = ContProjProjetos::find()->select("j17_user.nome as coordenador,j17_contproj_projetos.*")
        ->leftJoin("j17_user","j17_contproj_projetos.coordenador_id = j17_user.id");
         */


        //$query = ContProjTransferenciasSaldoRubricas::find();
        $query = ContProjTransferenciasSaldoRubricas::find()->select(" j17_contproj_transferenciassaldorubricas.*,
        j17_contproj_rubricasdeprojetos.descricao as nomeRubricaDestino")
        ->leftJoin("j17_contproj_rubricasdeprojetos",
            "j17_contproj_transferenciassaldorubricas.rubrica_destino =  j17_contproj_rubricasdeprojetos.id");

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

        $dataProvider->sort->attributes['nomeRubricaDestino'] = [
            'asc' => ['nomeRubricaDestino' => SORT_ASC],
            'desc' => ['nomeRubricaDestino' => SORT_DESC],
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'projeto_id' => $this->projeto_id,
            'rubrica_origem' => $this->rubrica_origem,
            'rubrica_destino' => $this->rubrica_destino,
            //'rubrica_nomeRubricaDestino' => $this->nomeRubricaDestino,
            'valor' => $this->valor,
            'data' => $this->data,
        ]);

        $query->andFilterWhere(['like', 'autorizacao', $this->autorizacao])
        ->andFilterWhere(['like', 'j17_contproj_rubricasdeprojetos.descricao', $this->nomeRubricaDestino]);
        return $dataProvider;
    }
}
