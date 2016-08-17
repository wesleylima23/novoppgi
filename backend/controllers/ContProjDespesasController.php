<?php

namespace backend\controllers;

use backend\models\ContProjBancos;
use backend\models\ContProjRubricas;
use backend\models\ContProjRubricasdeProjetos;
use Yii;
use backend\models\ContProjDespesas;
use backend\models\ContProjDespesasSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContProjDespesasController implements the CRUD actions for ContProjDespesas model.
 */
class ContProjDespesasController extends Controller
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
     * Lists all ContProjDespesas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContProjDespesasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContProjDespesas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ContProjDespesas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $projeto_id = Yii::$app->request->get('idProjeto');
        $model = new ContProjDespesas();
        $rubricasDeProjeto  = ArrayHelper::map(ContProjRubricasdeProjetos::find()
            ->where("projeto_id=".$projeto_id)->all(), 'id', 'descricao');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'rubricasDeProjeto'=>$rubricasDeProjeto ,
            ]);
        }
    }

    /**
     * Updates an existing ContProjDespesas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $rubricasDeProjeto = ArrayHelper::map(ContProjRubricasdeProjetos::find()->orderBy('nome'), 'id', 'descricao');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'rubricasDeProjeto'=>$rubricasDeProjeto,
            ]);
        }
    }

    /**
     * Deletes an existing ContProjDespesas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ContProjDespesas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjDespesas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjDespesas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
