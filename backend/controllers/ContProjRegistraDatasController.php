<?php

namespace backend\controllers;

use backend\models\ContProjProjetos;
use backend\models\Projetos;
use Yii;
use backend\models\ContProjRegistraDatas;
use backend\models\ContProjRegistraDatasSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContProjRegistraDatasController implements the CRUD actions for ContProjRegistraDatas model.
 */
class ContProjRegistraDatasController extends Controller
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
     * Lists all ContProjRegistraDatas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContProjRegistraDatasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$projetos = new ArrayDataProvider([
        //    'allModels' => Projetos::find()->all(),
        //]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContProjRegistraDatas model.
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
     * Creates a new ContProjRegistraDatas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContProjRegistraDatas();
        $projetos = ArrayHelper::map(ContProjProjetos::find()->orderBy('nomeprojeto')->all(), 'id', 'nomeprojeto');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'projetos' => $projetos,
            ]);
        }
    }

    /**
     * Updates an existing ContProjRegistraDatas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $projetos = ArrayHelper::map(ContProjProjetos::find()->orderBy('nomeprojeto')->all(), 'id', 'nomeprojeto');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'projetos' => $projetos,
            ]);
        }
    }

    /**
     * Deletes an existing ContProjRegistraDatas model.
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
     * Finds the ContProjRegistraDatas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjRegistraDatas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjRegistraDatas::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
