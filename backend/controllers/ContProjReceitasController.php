<?php

namespace backend\controllers;

use backend\models\ContProjProjetos;
use backend\models\ContProjRubricasdeProjetos;
use Yii;
use backend\models\ContProjReceitas;
use backend\models\ContProjReceitasSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContProjReceitasController implements the CRUD actions for ContProjReceitas model.
 */
class ContProjReceitasController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ContProjReceitas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContProjReceitasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $receita= $this->calcularRreceita();
        return $this->render('index', [
            'receita' => $receita,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function calcularRreceita(){
        $receita = 0;

        return $receita;
    }
    /**
     * Displays a single ContProjReceitas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $nomeProjeto = Yii::$app->request->get('nomeProjeto');
        $idProjeto = Yii::$app->request->get('idProjeto');
        $data = Yii::$app->request->get('data');
        return $this->render('view', [
            'model' => $this->findModel($id),
            'idProjeto'=> $idProjeto,
            'nomeProjeto' => $nomeProjeto,
        ]);
    }

    /**
     * Creates a new ContProjReceitas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function titulo()
    {
        return $this->descricao;
    }

    public function cadastrarReceita($model){

        $model->data = date('Y-m-d', strtotime($model->data));
        $rubrica = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubricasdeprojetos_id")->one();
        $projeto = ContProjProjetos::find()->select("*")->where("id=$rubrica->projeto_id")->one();
        $projeto->saldo += $model->valor_receita;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($projeto->save() && $model->save()) {
                $transaction->commit();
                return true;
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
        $transaction->rollBack();
        return false;
    }



    public function actionCreate()
    {
        $model = new ContProjReceitas();

        $nomeProjeto= Yii::$app->request->get('nomeProjeto');
        $idProjeto= Yii::$app->request->get('idProjeto');

        if ($model->load(Yii::$app->request->post()) && $this->cadastrarReceita($model) ) {

            return $this->redirect(['index', 'id' => $model->id,'idProjeto' => $idProjeto, 'nomeProjeto' => $nomeProjeto]);
        } else {
            $rubricasdeProj = ContProjRubricasdeProjetos::find()->select(["j17_contproj_rubricasdeprojetos.id",
                "CONCAT_WS(':',j17_contproj_rubricas.tipo,j17_contproj_rubricas.nome, j17_contproj_rubricasdeprojetos.valor_total) 
                AS descricao"])
                ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricasdeprojetos.rubrica_id = j17_contproj_rubricas.id")
                ->where("j17_contproj_rubricasdeprojetos.projeto_id = $idProjeto")->all();
            $rubricasdeProjeto = ArrayHelper::map($rubricasdeProj, 'id', 'descricao');
            return $this->render('create', [
                'rubricasdeProjeto' => $rubricasdeProjeto,
                'model' => $model,
                'idProjeto' => $idProjeto,
                'nomeProjeto' => $nomeProjeto
            ]);
        }
    }

    /**
     * Updates an existing ContProjReceitas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ContProjReceitas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $idProjeto = Yii::$app->request->get("idProjeto");
        $nomeProjeto = Yii::$app->request->get("nomeProjeto");
        $rubricaProjeto = ContProjRubricasdeProjetos::find()->select("*")->where("id=$model->rubricasdeprojetos_id")->one();
        $rubricaProjeto->valor_disponivel =  $rubricaProjeto->valor_disponivel - $model->valor_receita;
        $rubricaProjeto->save(false);
        $projeto = ContProjProjetos::find()->select("*")->where("id=$rubricaProjeto->projeto_id")->one();
        $projeto->saldo = $projeto->saldo - $model->valor_receita;
        $projeto->save(false);

        $this->findModel($id)->delete();
        return $this->redirect(['index','idProjeto'=>$idProjeto,'nomeProjeto'=>$nomeProjeto]);
    }

    /**
     * Finds the ContProjReceitas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjReceitas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjReceitas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
