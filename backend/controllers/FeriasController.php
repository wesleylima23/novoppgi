<?php

namespace backend\controllers;

use Yii;
use app\models\Ferias;
use yii\filters\AccessControl;
use app\models\Professor;
use app\models\Funcionario;
use common\models\User;
use app\models\FeriasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FeriasController implements the CRUD actions for Ferias model.
 */
class FeriasController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return (Yii::$app->user->identity->checarAcesso('professor') || Yii::$app->user->identity->checarAcesso('secretaria'));
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'deletesecretaria' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Ferias models.
     * @return mixed
     */
    public function actionIndex()
    {

     $idUser = Yii::$app->user->identity->id;


        $searchModel = new FeriasSearch();
        $dataProvider = $searchModel->searchMinhasFerias(Yii::$app->request->queryParams , $idUser);
        


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListar($ano)
    {


        $idUser = Yii::$app->user->identity->id;

        if (Yii::$app->user->identity->professor == 1 || Yii::$app->user->identity->coordenador == 1){
            $direitoQtdFerias = 45;
        }
        else{
            $direitoQtdFerias = 30;   
        }


        $model = new Ferias();
        $todosAnosFerias = $model->anosFerias($idUser);

        $qtd_usufruto_ferias = $model->feriasAno($idUser,$ano,1);
        $qtd_ferias_oficiais = $model->feriasAno($idUser,$ano,2);



        $searchModel = new FeriasSearch();
        $dataProvider = $searchModel->searchMinhasFerias(Yii::$app->request->queryParams , $idUser ,$ano);

        $model_do_usuario = User::find()->where(["id" => $idUser])->one();

        return $this->render('index', [
            'model_do_usuario' => $model_do_usuario,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'todosAnosFerias' => $todosAnosFerias,
            'direitoQtdFerias' => $direitoQtdFerias,
            "qtd_ferias_oficiais" => $qtd_ferias_oficiais,
            "qtd_usufruto_ferias" => $qtd_usufruto_ferias,

        ]);
    }

    public function actionDetalhar($ano,$id,$prof)
    {


        $idUser = $id;

        $model = new Ferias();

        $ehProf = $prof;

        if ($ehProf == 1){
            $direitoQtdFerias = 45;
        }
        else{
            $direitoQtdFerias = 30;   
        }
       
        $todosAnosFerias = $model->anosFerias($idUser);

        $qtd_usufruto_ferias = $model->feriasAno($idUser,$ano,1);
        $qtd_ferias_oficiais = $model->feriasAno($idUser,$ano,2);


        $searchModel = new FeriasSearch();
        $dataProvider = $searchModel->searchMinhasFerias(Yii::$app->request->queryParams , $idUser ,$ano);

        $model_do_usuario = User::find()->where(["id" => $idUser])->one();

        return $this->render('detalhar', [
            'model_do_usuario' => $model_do_usuario,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'todosAnosFerias' => $todosAnosFerias,
            'direitoQtdFerias' => $direitoQtdFerias,
            "qtd_ferias_oficiais" => $qtd_ferias_oficiais,
            "qtd_usufruto_ferias" => $qtd_usufruto_ferias,
            "id" => $id,

        ]);
    }
    
    public function actionListartodos($ano)
    {


        $idUser = Yii::$app->user->identity->id;

        if (Yii::$app->user->identity->professor == 1 || Yii::$app->user->identity->coordenador == 1){
            $direitoQtdFerias = 45;
        }
        else{
            $direitoQtdFerias = 30;   
        }


        $model = new Ferias();
        $todosAnosFerias = $model->anosFerias(null);

        $qtd_usufruto_ferias = $model->feriasAno($idUser,$ano,1);
        $qtd_ferias_oficiais = $model->feriasAno($idUser,$ano,2);



        $searchModel = new FeriasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams , $ano);

        $searchModel2 = new FeriasSearch();
        $dataProvider2 = $searchModel2->searchFuncionarios(Yii::$app->request->queryParams , $ano);

        return $this->render('listarTodos', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'todosAnosFerias' => $todosAnosFerias,
        ]);
    }

    /**
     * Displays a single Ferias model.
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
     * Creates a new Ferias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($ano)
    {

        $model = new Ferias();
        $model->idusuario = Yii::$app->user->identity->id;
        $model->nomeusuario = Yii::$app->user->identity->nome;
        $model->emailusuario = Yii::$app->user->identity->email;
        $model->dataPedido = date("Y-m-d H:i:s");

        $ehProfessor = Yii::$app->user->identity->professor;
        $ehSecretario = Yii::$app->user->identity->secretaria;

        if($ehProfessor == 1){
            $limiteDias = 45;
        }
        else{
            $limiteDias = 30;
        }

        if ($model->load(Yii::$app->request->post())) {


                $model->dataSaida = date('Y-m-d', strtotime($model->dataSaida));
                $model->dataRetorno =  date('Y-m-d', strtotime($model->dataRetorno));


                $feriasAno = new Ferias();
                $anoSaida = date('Y', strtotime($model->dataSaida));
                $totalDiasFeriasAno = $feriasAno->feriasAno($model->idusuario,$anoSaida,$model->tipo);


                $datetime1 = new \DateTime($model->dataSaida);
                $datetime2 = new \DateTime($model->dataRetorno);
                $interval = $datetime1->diff($datetime2);
                $diferencaDias =  $interval->format('%a');
                $diferencaDias++;

                if( $diferencaDias < 0 || $interval->format('%R') == "-"){

                    $this->mensagens('danger', 'Registro Férias',  'Datas inválidas!');

                        $model->dataSaida = date('d-m-Y', strtotime($model->dataSaida));
                        $model->dataRetorno =  date('d-m-Y', strtotime($model->dataRetorno));

                    return $this->render('create', [
                            'model' => $model,
                        ]);

                }

                    if( ($totalDiasFeriasAno+$diferencaDias) <= $limiteDias && $model->save()){

                        $this->mensagens('success', 'Registro Férias',  'Registro de Férias realizado com sucesso!');

                        return $this->redirect(['listar', 'ano' => $anoSaida]);

                    }
                    else {

                        $this->mensagens('danger', 'Registro Férias', 'Não foi possível registrar o pedido de férias. Você ultrapassou o limite de '.$limiteDias.'  dias');

                    }

                $model->dataSaida = date('d-m-Y', strtotime($model->dataSaida));
                $model->dataRetorno =  date('d-m-Y', strtotime($model->dataRetorno));

                return $this->render('create', [
                        'model' => $model,
                    ]);


        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionCreatesecretaria($id)
    {

        $model = new Ferias();

        $model_User = User::find()->where(["id" => $id])->one();
        
        
        if($model_User->professor == 1){
            $limiteDias = 45;
        }
        else{
            $limiteDias = 30;
        }


        $model->idusuario = $id;
        $model->nomeusuario = $model_User->nome;
        $model->emailusuario = $model_User->email;
        $model->dataPedido = date("Y-m-d H:i:s");


        if ($model->load(Yii::$app->request->post())) {


                $model->dataSaida = date('Y-m-d', strtotime($model->dataSaida));
                $model->dataRetorno =  date('Y-m-d', strtotime($model->dataRetorno));


                $feriasAno = new Ferias();
                $anoSaida = date('Y', strtotime($model->dataSaida));
                $totalDiasFeriasAno = $feriasAno->feriasAno($model->idusuario,$anoSaida,$model->tipo);

                $datetime1 = new \DateTime($model->dataSaida);
                $datetime2 = new \DateTime($model->dataRetorno);
                $interval = $datetime1->diff($datetime2);
                $diferencaDias =  $interval->format('%a');
                $diferencaDias++;


                if( $diferencaDias < 0 || $interval->format('%R') == "-"){

                    $this->mensagens('danger', 'Registro Férias',  'Datas inválidas!');

                        $model->dataSaida = date('d-m-Y', strtotime($model->dataSaida));
                        $model->dataRetorno =  date('d-m-Y', strtotime($model->dataRetorno));

                    return $this->render('createsecretaria', [
                            'model' => $model,
                        ]);

                }

                    if( ($totalDiasFeriasAno+$diferencaDias) <= $limiteDias && $model->save()){

                        $this->mensagens('success', 'Registro Férias',  'Registro de Férias realizado com sucesso!');

                        return $this->redirect(['detalhar', 'id' => $model->idusuario, 'ano' => date("Y") ,"prof" => $model_User->professor]);

                    }

                    else {

                        $this->mensagens('danger', 'Registro Férias', 'Não foi possível registrar o pedido de férias. Você ultrapassou o limite de '.$limiteDias.' dias');

                    }

                $model->dataSaida = date('d-m-Y', strtotime($model->dataSaida));
                $model->dataRetorno =  date('d-m-Y', strtotime($model->dataRetorno));

                return $this->render('createsecretaria', [
                        'model' => $model,
                        'nome' => $model->nomeusuario,
                    ]);


        } else {

            return $this->render('createsecretaria', [
                'model' => $model,
                'nome' => $model->nomeusuario,
            ]);
        }
    }

    /**
     * Updates an existing Ferias model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $ehProfessor = Yii::$app->user->identity->professor;
        $ehCoordenador = Yii::$app->user->identity->coordenador;
        $ehSecretario = Yii::$app->user->identity->secretaria;

        $datetime1Anterior = new \DateTime($model->dataSaida);
        $datetime2Anterior = new \DateTime($model->dataRetorno);
        $intervalAnterior = $datetime1Anterior->diff($datetime2Anterior);
        $AnteriordiferencaDias =  $intervalAnterior->format('%a');
        $AnteriordiferencaDias++;

        $anteriorTipo = $model->tipo;

        if ($model->load(Yii::$app->request->post())) {

                $model->dataSaida = date('Y-m-d', strtotime($model->dataSaida));
                $model->dataRetorno =  date('Y-m-d', strtotime($model->dataRetorno));


                $feriasAno = new Ferias();
                $anoSaida = date('Y', strtotime($model->dataSaida));
                $totalDiasFeriasAno = $feriasAno->feriasAno($model->idusuario,$anoSaida,$model->tipo);


                $datetime1 = new \DateTime($model->dataSaida);
                $datetime2 = new \DateTime($model->dataRetorno);
                $interval = $datetime1->diff($datetime2);
                $diferencaDias =  $interval->format('%a');
                $diferencaDias++;

                if($anteriorTipo == $model->tipo) {
                    $diferencaDiasUpdate = $diferencaDias - $AnteriordiferencaDias;
                }
                else{
                    $diferencaDiasUpdate = $diferencaDias;
                }


    
                if( $diferencaDias < 0 || $interval->format('%R') == "-"){

                    $this->mensagens('danger', 'Registro Férias',  'Datas inválidas!');

                        $model->dataSaida = date('d-m-Y', strtotime($model->dataSaida));
                        $model->dataRetorno =  date('d-m-Y', strtotime($model->dataRetorno));

                    return $this->render('update', [
                            'model' => $model,
                        ]);

                }

                    if( ($ehProfessor == 1 ) && ($totalDiasFeriasAno+$diferencaDiasUpdate) <=45 && $model->save()){

                        $this->mensagens('success', 'Registro Férias',  'Registro de Férias realizado com sucesso!');

                        return $this->redirect(['listar', 'ano' => $anoSaida]);

                    }
                    else if( $ehSecretario == 1  && ($totalDiasFeriasAno+$diferencaDiasUpdate) <= 30 && $model->save()){

                        $this->mensagens('success', 'Registro Férias',  'Registro de Férias realizado com sucesso!');
                    
                        return $this->redirect(['listar', "ano" => $anoSaida]);
                    
                    }
                    else if ((($ehProfessor == 1) && ($totalDiasFeriasAno+$diferencaDiasUpdate) >45)) {

                        $this->mensagens('danger', 'Registro Férias', 'Não foi possível registrar o pedido de férias. Você ultrapassou o limite de 45 dias');
                    }

                    else if (( $ehSecretario == 1  && ($totalDiasFeriasAno+$diferencaDiasUpdate) > 30)) {

                        $this->mensagens('danger', 'Registro Férias', 'Não foi possível registrar o pedido de férias. Você ultrapassou o limite de 30 dias');

                    }

                $model->dataSaida = date('d-m-Y', strtotime($model->dataSaida));
                $model->dataRetorno =  date('d-m-Y', strtotime($model->dataRetorno));

                return $this->render('update', [
                        'model' => $model,
                    ]);

        } else {

                $model->dataSaida = date('d-m-Y', strtotime($model->dataSaida));
                $model->dataRetorno =  date('d-m-Y', strtotime($model->dataRetorno));

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Ferias model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     //funcao usada por cada professor/técnico
    public function actionDelete($id,$ano)
    {
        $this->findModel($id)->delete();

        $this->mensagens('success', 'Registro Férias',  'Registro de Férias excluído com sucesso!');

        return $this->redirect(['listar','ano'=> $ano]);
    }
    //função usada na view da Secretaria, o qual lista todos os membros
    public function actionDeletesecretaria($id,$ano,$idUsuario,$prof)
    {

        $this->findModel($id)->delete();

        $this->mensagens('success', 'Registro Férias',  'Registro de Férias excluído com sucesso!');
        
        return $this->redirect(['detalhar','id' => $idUsuario , 'ano'=> $ano, 'prof' => $prof]);
    }

    /**
     * Finds the Ferias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ferias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ferias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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
