<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\User;
use app\models\Aluno;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\IntegrityException;
use yii\base\Exception;
use app\models\UploadLattesForm;
use app\models\UploadDisciplinasForm;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                          return (Yii::$app->user->identity->checarAcesso('administrador') || Yii::$app->user->identity->checarAcesso('secretaria'));
                        }
                    ],
                    [   'actions' => ['perfil', 'lattes', 'uploadDisciplinas'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                      [ 'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                               return Yii::$app->user->identity->id == filter_input(INPUT_GET, 'id') ;
                        }
                    ],
                ],
            ], 
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionPerfil()
    {
        return $this->render('perfil', [
            'model' => $this->findModel(Yii::$app->user->identity->id),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword();
            if($model->save()){
                $this->mensagens('success', 'Usuário Alterado', 'Usuário alterado com sucesso.');
                return Yii::$app->user->identity->checarAcesso('administrador') ? $this->redirect(['view', 'id' => $model->id]) : $this->redirect(['perfil']);
            }
            else
                $this->mensagens('danger', 'Erro ao Alterar Usuário', 'Ocorreu um erro ao alterar o usuário. Verifique os campos e tente novamente.');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if($model->professor){
            $alunos = $model->getAlunos($model->id);
            if($alunos){
                $this->mensagens('warning', 'Usuário com alunos associados', 'O usuário corrente é professor e possui alunos.');
                return $this->redirect(['index']);
            }
        }

        try{
            $model->delete();
            $this->mensagens('success', 'Usuário Removido', 'Usuário removido com sucesso.');
        }catch(IntegrityException $e){
            $this->mensagens('danger', 'Erro ao Remover Usuário', 'Ocorreu um erro ao remover o Usuário.');
        }  

        return $this->redirect(['index']);
    }


    /**
     * Uploads Lattes.
     * @return mixed
     */
    public function actionLattes()
    {
        $model = new UploadLattesForm();

        if (Yii::$app->request->isPost) {
            $model->lattesFile = UploadedFile::getInstance($model, 'lattesFile');
            if ($model->upload(Yii::$app->user->identity->id)) {
                // file is uploaded successfully
				$this->mensagens('success', 'Sucesso', 'Upload realizado com sucesso.');
				//return $this->redirect(['lattes');
            }
        }

		//$this->mensagens('success', 'Sucesso', 'Upload vai ser realizado com sucesso.');

		return $this->render('lattes', ['model' => $model]);
    }
	
	/**
     * Uploads Lattes.
     * @return mixed
     */
	 
	 
    public function actionDisciplinas()
    {
        $model = new UploadDisciplinasForm();

        if (Yii::$app->request->isPost) {
            $model->csvDisciplinasFile = UploadedFile::getInstance($model, 'csvDisciplinasFile');
//            if ($model->upload(Yii::$app->user->identity->id)) {
                // file is uploaded successfully
				$this->mensagens('success', 'Sucesso', 'Upload realizado com sucesso.');
				//return $this->redirect(['lattes');
  //          }
        }


		return $this->render('disciplinas', ['model' => $model]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('A página solicitada não existe.');
        }
    }

    /* Envio de mensagens para views
   Tipo: success, danger, warning*/
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
