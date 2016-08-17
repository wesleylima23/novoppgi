<?php

namespace backend\controllers;

use backend\models\ContProjProjetos;
use backend\models\ContProjReceitas;
use backend\models\ContProjRubricas;
use Yii;
use backend\models\ContProjRubricasdeProjetos;
use backend\models\ContProjRubricasdeProjetosSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContProjRubricasdeProjetosController implements the CRUD actions for ContProjRubricasdeProjetos model.
 */
class ContProjRubricasdeProjetosController extends Controller
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
     * Lists all ContProjRubricasdeProjetos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $idProjeto = Yii::$app->request->get("idProjeto");
        $nomeProjeto = Yii::$app->request->get("nomeProjeto");
        $id = Yii::$app->request->get('id');
        $searchModel = new ContProjRubricasdeProjetosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'idProjeto' => $idProjeto,
            'nomeProjeto' => $nomeProjeto,
        ]);
    }

    /**
     * Displays a single ContProjRubricasdeProjetos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $idProjeto = Yii::$app->request->get("idProjeto");
        $nomeProjeto = Yii::$app->request->get("nomeProjeto");
        return $this->render('view', [
            'model' => $this->findModel($id),
            'idProjeto' => $idProjeto,
            'nomeProjeto' => $nomeProjeto,
        ]);
    }

    public function cadastrarReceita($model){
        $receita = new ContProjReceitas();
        $receita->rubricasdeprojetos_id = $model->id;
        $receita->descricao = $model->descricao;
        $receita->valor_receita = $model->valor_disponivel;
        $receita->data = date("Y:m:d");
        $projeto = ContProjProjetos::find()->select("*")->where("id=$model->projeto_id")->one();
        $projeto->saldo = $model->valor_disponivel;
        $projeto->save();
        return $receita->save(false);

    }

    public function atualizarReceita($model){
        $receita = ContProjReceitas::find()->where("rubricasdeprojetos_id=$model->id")->one();
        //$receita->rubricasdeprojetos_id = $model->id;
        $receita->descricao = $model->descricao;
        $receita->valor_receita = $model->valor_disponivel;
        //$receita->data = date("Y:m:d");
        return $receita->save(false);
    }

    /**
     * Creates a new ContProjRubricasdeProjetos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContProjRubricasdeProjetos();
        $nomeProjeto= Yii::$app->request->get('nomeProjeto');
        $idProjeto= Yii::$app->request->get('idProjeto');
        $rubricas = ArrayHelper::map(ContProjRubricas::find()->all(), 'id', 'nome');
        $model->valor_gasto = 0.00;
        $model->valor_disponivel = 0.00;
        if ($model->load(Yii::$app->request->post()) && $model->save() ) {

            if($model->valor_disponivel > 0) {
                if ($this->cadastrarReceita($model)) {

                    return $this->redirect(['view', 'id' => $model->id, 'nomeProjeto' => $nomeProjeto,
                        'idProjeto' => $idProjeto,]);
                }
            }
            return $this->redirect(['view', 'id' => $model->id, 'idProjeto' => $idProjeto, 'nomeProjeto' => $nomeProjeto]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'rubricas'=>$rubricas,
                'nomeProjeto'=>$nomeProjeto,
                'idProjeto'=>$idProjeto,
            ]);
        }
    }

    /**
     * Updates an existing ContProjRubricasdeProjetos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $idProjeto = Yii::$app->request->get("idProjeto");
        $nomeProjeto = Yii::$app->request->get("nomeProjeto");
        $rubricas = ArrayHelper::map(ContProjRubricas::find()->orderBy('')->all(), 'id', 'nome');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->valor_disponivel > 0) {
                if ($this->atualizarReceita($model)) {

                    return $this->redirect(['view', 'id' => $model->id, 'nomeProjeto' => $nomeProjeto,
                        'idProjeto' => $idProjeto,]);
                }
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'rubricas'=>$rubricas,
                'nomeProjeto'=>$nomeProjeto,
                'idProjeto'=>$idProjeto,
            ]);
        }
    }



    /**
     * Deletes an existing ContProjRubricasdeProjetos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $idProjeto = Yii::$app->request->get("idProjeto");
        $nomeProjeto = Yii::$app->request->get("nomeProjeto");
        $projeto = ContProjProjetos::find()->select("*")->where("id=$model->projeto_id")->one();
        $projeto->saldo = $projeto->saldo - $model->valor_disponivel;
        $projeto->save();
        $receitas = ContProjReceitas::find()->where("rubricasdeprojetos_id=$model->id")->all();
        if($receitas) {
            return $this->redirect(['view',
                                    'id' => $model->id,
                                    'idProjeto' => $idProjeto,
                                    'nomeProjeto' => $nomeProjeto,
                                    'mensagem'=>true]);
        }else{
            $this->findModel($id)->delete();
            return $this->redirect(['index', 'idProjeto' => $idProjeto, 'nomeProjeto' => $nomeProjeto]);
            }
        }

    /**
     * Finds the ContProjRubricasdeProjetos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjRubricasdeProjetos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        if (($model = ContProjRubricasdeProjetos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
