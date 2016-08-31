<?php

namespace backend\controllers;

use app\models\User;
use backend\models\ContProjAgencias;
use backend\models\ContProjBancos;
use backend\models\ContProjRubricasdeProjetos;
use backend\models\ContProjRubricasdeProjetosSearch;
use backend\models\ContProjTransferenciasSaldoRubricasSearch;
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

    public function actionRelatorio(){
        $searchModel = new ContProjRubricasdeProjetosSearch();
        $dataProviderCapital = $searchModel->searchCapital(Yii::$app->request->queryParams);
        $dataProviderCusteio = $searchModel->searchCusteio(Yii::$app->request->queryParams);
        $searchModelTransferencias = new ContProjTransferenciasSaldoRubricasSearch();
        $dataProviderTransferencias = $searchModelTransferencias->search(Yii::$app->request->queryParams);
        return $this->render('relatorio', [
            'searchModel' => $searchModel,
            'dataProviderCapital' => $dataProviderCapital,
            'dataProviderCusteio' => $dataProviderCusteio,
            'searchModelTransferencias' => $searchModelTransferencias,
            'dataProviderTransferencias' =>$dataProviderTransferencias,
        ]);
    }

    public function actionCancelar(){
        $this->mensagens('error', 'Cadastro de Projeto',  'O cadastro do projeto foi cancelado!');
        return $this->redirect(['index']);
    }

    public function actionCancelarr($id){
        //$this->mensagens('error', ' de Projeto',  'O cadastro do projeto foi cancelado!');
        return $this->redirect(['view','id' => $id]);
    }
    /**
     * Creates a new ContProjProjetos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContProjProjetos();
        $coordenadores = ArrayHelper::map(User::find()->orderBy('nome')->where('professor=1')->all(), 'id', 'nome');
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
        $model->saldo = 0;
        $model->status = "Cadastrado";
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->data_inicio = date('Y-m-d', strtotime($model->data_inicio));
            $model->data_fim = date('Y-m-d', strtotime($model->data_fim));
            $model->data_fim_alterada =  $model->data_fim;
            $model->save();
            $this->mensagens('success', 'Cadastro de Projeto',  'Projeto cadastrado com sucesso!');
            return $this->redirect(['index', 'id' => $model->id]);
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
        $coordenadores = ArrayHelper::map(User::find()->orderBy('nome')->where('professor=1')->all(), 'id', 'nome');
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
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->data_inicio = date('Y-m-d', strtotime($model->data_inicio));
            $model->data_fim = date('Y-m-d', strtotime($model->data_fim));
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->data_inicio = date("d-m-Y",strtotime($model->data_inicio));
            $model->data_fim = date("d-m-Y",strtotime($model->data_fim));
            return $this->render('update', [
                'model' => $model,
                'coordenadores' => $coordenadores,
                'agencias' => $agencias,
                'bancos' => $bancos,
            ]);
        }
    }

    public function actionProrrogar($id){
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate(false)) {
            $model->data_fim_alterada = date('Y-m-d', strtotime($model->data_fim_alterada));
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->data_fim_alterada = null;
            return $this->render('prorrogar', [
                'model' => $model,
            ]);
        }
    }

    public static function view($id){
        $model = ContProjProjetosController::findModel($id);
        return ContProjProjetosController::render('cont-proj-projetos/view', [
            'model' => $model,
        ]);

    }
    /**
     * Deletes an existing ContProjProjetos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$detalhe=false)
    {
        $model = $this->findModel($id);
        try{
            $model->delete();
            $this->mensagens('success', 'Excluir Projeto',  'Projeto excluido com sucesso!');
        }catch (\yii\base\Exception $e){
            $this->mensagens('error', 'Excluir Projeto', 'Projeto não pode ser excluido pois existem rubricas associadas ao projeto!');
            if($detalhe){
                return $this->redirect(['view','id' => $model->id]);
            }else{
                return $this->redirect(['index']);
            }
        }
        return $this->redirect(['index']);
        //return $this->redirect(['index']);
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

    protected function mensagens($tipo, $titulo, $mensagem){
        Yii::$app->session->setFlash($tipo, [
            'type' => $tipo,
            'icon' => 'home',
            'duration' => 5000,
            'message' => $mensagem,
            'title' => $titulo,
            'positonY' => 'top',
            'positonX' => 'center',
            'showProgressbar' => true,
        ]);
    }

}
