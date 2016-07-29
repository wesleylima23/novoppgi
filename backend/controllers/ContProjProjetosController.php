<?php

namespace backend\controllers;

use app\models\User;
use backend\models\ContProjAgencias;
use backend\models\ContProjBancos;
use Yii;
use backend\models\ContProjProjetos;
use backend\models\ContProjProjetosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;
use yii\web\Exception;
use yii\web\UploadedFile;

/**
 * ContProjProjetosController implements the CRUD actions for ContProjProjetos model.
 */
class ContProjProjetosController extends Controller
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
     * Lists all ContProjProjetos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContProjProjetosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContProjProjetos model.
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
     * Creates a new ContProjProjetos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContProjProjetos();
        $coordenadores = ArrayHelper::map(User::find()->orderBy('nome')->all(), 'id', 'nome');
        $agencias = ArrayHelper::map(ContProjAgencias::find()->orderBy('nome')->all(), 'id', 'nome');
        $bancos = ArrayHelper::map(ContProjBancos::find()->orderBy('nome')->all(), 'id', 'nome');
        $model->editalArquivo = UploadedFile::getInstance($model, 'editalArquivo');
        if($model->editalArquivo){
            $model->edital = "uploads/".date('dmYhms')."_".$model->editalArquivo->name;
            $model->editalArquivo->saveAs($model->edital);
        }
        $model->propostaArquivo = UploadedFile::getInstance($model, 'propostaArquivo');
        if($model->propostaArquivo) {
            $model->proposta = "uploads/".date('dmYhms')."_".$model->propostaArquivo->name;
            $model->propostaArquivo->saveAs($model->proposta);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'coordenadores' => $coordenadores,
                'agencias' => $agencias,
                'bancos' => $bancos,
            ]);
        }

    }

    /**
     * Updates an existing ContProjProjetos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $coordenadores = ArrayHelper::map(User::find()->orderBy('nome')->all(), 'id', 'nome');
        $agencias = ArrayHelper::map(ContProjAgencias::find()->orderBy('nome')->all(), 'id', 'nome');
        $bancos = ArrayHelper::map(ContProjBancos::find()->orderBy('nome')->all(), 'id', 'nome');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'coordenadores' => $coordenadores,
                'agencias' => $agencias,
                'bancos' => $bancos,
            ]);
        }
    }

    /**
     * Deletes an existing ContProjProjetos model.
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
     * Finds the ContProjProjetos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContProjProjetos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContProjProjetos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
