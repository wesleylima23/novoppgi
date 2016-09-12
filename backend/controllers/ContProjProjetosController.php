<?php

namespace backend\controllers;

use app\models\User;
use backend\models\ContProjAgencias;
use backend\models\ContProjBancos;
use backend\models\ContProjDespesas;
use backend\models\ContProjReceitas;
use backend\models\ContProjRubricas;
use backend\models\ContProjRubricasdeProjetos;
use backend\models\ContProjRubricasdeProjetosSearch;
use backend\models\ContProjTransferenciasSaldoRubricasSearch;
use PhpOffice\PhpWord\PhpWord;
use Yii;
use backend\models\ContProjProjetos;
use backend\models\ContProjProjetosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use NumberFormatter;
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

    public function actionRelatorio()
    {
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
            'dataProviderTransferencias' => $dataProviderTransferencias,
        ]);
    }

    public function actionCancelar()
    {
        $this->mensagens('error', 'Cadastro de Projeto', 'O cadastro do projeto foi cancelado!');
        return $this->redirect(['index']);
    }

    public function actionCancelarr($id)
    {
        //$this->mensagens('error', ' de Projeto',  'O cadastro do projeto foi cancelado!');
        return $this->redirect(['view', 'id' => $id]);
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
        if ($model->editalArquivo) {
            $model->edital = "uploads/" . date('dmYhms') . "_" . $model->editalArquivo->name;
            $model->editalArquivo->saveAs($model->edital);
        }
        $model->propostaArquivo = UploadedFile::getInstance($model, 'propostaArquivo');
        if ($model->propostaArquivo) {
            $model->proposta = "uploads/" . date('dmYhms') . "_" . $model->propostaArquivo->name;
            $model->propostaArquivo->saveAs($model->proposta);
        }
        $model->saldo = 0;
        $model->status = "Cadastrado";
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->data_inicio = date('Y-m-d', strtotime($model->data_inicio));
            $model->data_fim = date('Y-m-d', strtotime($model->data_fim));
            $model->data_fim_alterada = $model->data_fim;
            $model->save();
            $this->mensagens('success', 'Cadastro de Projeto', 'Projeto cadastrado com sucesso!');
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
        if ($model->editalArquivo) {
            $model->edital = "uploads/" . date('dmYhms') . "_" . $model->editalArquivo->name;
            $model->editalArquivo->saveAs($model->edital);
        }
        $model->propostaArquivo = UploadedFile::getInstance($model, 'propostaArquivo');
        if ($model->propostaArquivo) {
            $model->proposta = "uploads/" . date('dmYhms') . "_" . $model->propostaArquivo->name;
            $model->propostaArquivo->saveAs($model->proposta);
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->data_inicio = date('Y-m-d', strtotime($model->data_inicio));
            $model->data_fim = date('Y-m-d', strtotime($model->data_fim));
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->data_inicio = date("d-m-Y", strtotime($model->data_inicio));
            $model->data_fim = date("d-m-Y", strtotime($model->data_fim));
            return $this->render('update', [
                'model' => $model,
                'coordenadores' => $coordenadores,
                'agencias' => $agencias,
                'bancos' => $bancos,
            ]);
        }
    }

    public function actionProrrogar($id)
    {
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

    public function actionForm2($id)
    {
        $projeto = ContProjProjetos::find()->select("*")->where("id=$id")->one();
        $coordenador = \app\models\User::find()->select("*")->where("id=$projeto->coordenador_id")->one();
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('uploads/Form2.docx');
        $templateProcessor->setValue('outorgado', $coordenador->nome);
        $templateProcessor->setValue('saldo', number_format ($projeto->saldo,2)." R$");
        $templateProcessor->setValue('reais', 'R$');
        $receitas = ContProjReceitas::find()->select(["*,j17_contproj_receitas.ordem_bancaria AS ordem, 
            SUM(j17_contproj_receitas.valor_receita) AS total"])
            ->leftJoin("j17_contproj_rubricasdeprojetos","j17_contproj_receitas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id = $id")
            ->groupBy("j17_contproj_receitas.ordem_bancaria")
            ->orderBy("data")->all();

        $total = 0;
        $tamanho = count($receitas);
        $templateProcessor->cloneRow('datReceita',$tamanho);
        for ($i = 0; $i<$tamanho ; $i++) {
            $templateProcessor->setValue('datReceita#'.($i+1), date("d/m/Y", strtotime($receitas[$i]->data)));
            $templateProcessor->setValue('ordem#'.($i+1), $receitas[$i]->ordem_bancaria);
            $templateProcessor->setValue('valorReceita#'.($i+1), number_format ( $receitas[$i]->total,2)." R$");
            $total = $total + $receitas[$i]->total;
        }
        $templateProcessor->setValue('valorTotal',number_format ( $total,2)." R$");
        $despesas = ContProjDespesas::find()->select(["*"])
            ->leftJoin("j17_contproj_rubricasdeprojetos","j17_contproj_despesas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id = $id")->all();
        $tamanho = count($despesas);
        $templateProcessor->cloneRow('num',$tamanho);
        for ($i = 0; $i<$tamanho ; $i++) {
            $templateProcessor->setValue('num#'.($i+1),$despesas[$i]->ident_cheque);
            $templateProcessor->setValue('dat#'.($i+1), date("d/m/Y", strtotime($despesas[$i]->data_emissao)));
            $templateProcessor->setValue('numero#'.($i+1),$despesas[$i]->nf);
            $templateProcessor->setValue('favorecido#'.($i+1),$despesas[$i]->favorecido);
            $templateProcessor->setValue('valorDespesa#'.($i+1),number_format ($despesas[$i]->valor_despesa,2)." R$");
        }

        header('Content-Disposition: attachment; filename="Form2.docx"');
        $templateProcessor->saveAs('php://output');
    }

    public function actionForm8($id){
        $projeto = ContProjProjetos::find()->select("*")->where("id=$id")->one();
        $coordenador = \app\models\User::find()->select("*")->where("id=$projeto->coordenador_id")->one();
        $rubricas = ContProjRubricasdeProjetos::find()->select("j17_contproj_rubricas.nome as nomerubrica,valor_total,valor_gasto")
            ->leftJoin("j17_contproj_rubricas","rubrica_id=j17_contproj_rubricas.id")
            ->where("projeto_id=$id")->all();
        $tamanho = count($rubricas);
        $totalSolicitado = 0;
        $totalGasto=0;
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('uploads/Form8.docx');
        $templateProcessor->cloneRow('rubrica',$tamanho);
        $templateProcessor->setValue('outorgado', $coordenador->nome);
        for ($i = 0; $i<$tamanho ; $i++) {
            $templateProcessor->setValue('rubrica#'.($i+1), $rubricas[$i]->nomerubrica);
            $templateProcessor->setValue('valorSolicitado#'.($i+1), "R$ ".number_format ($rubricas[$i]->valor_total,2));
            $templateProcessor->setValue('valorGasto#'.($i+1), "R$ ".number_format ($rubricas[$i]->valor_gasto,2));
            $totalSolicitado = $totalSolicitado + $rubricas[$i]->valor_total;
            $totalGasto = $totalGasto + $rubricas[$i]->valor_gasto;
        }
        $templateProcessor->setValue('totalSolicitado', "R$ ".number_format ($totalSolicitado,2));
        $templateProcessor->setValue('totalGasto', "R$ ".number_format ($totalGasto,2));
        header('Content-Disposition: attachment; filename="Form8-Tabela-de-custo-8.docx"');
        $templateProcessor->saveAs('php://output');
    }

    public function actionForm3($id){
        $projeto = ContProjProjetos::find()->select("*")->where("id=$id")->one();
        $coordenador = \app\models\User::find()->select("*")->where("id=$projeto->coordenador_id")->one();
        $despesas = ContProjDespesas::find()->select("*,j17_contproj_despesas.descricao as descricao")
            ->leftJoin("j17_contproj_rubricasdeprojetos","j17_contproj_despesas.rubricasdeprojetos_id = j17_contproj_rubricasdeprojetos.id")
            ->leftJoin("j17_contproj_rubricas","j17_contproj_rubricas.id = j17_contproj_rubricasdeprojetos.rubrica_id")
            ->where("j17_contproj_rubricasdeprojetos.projeto_id = $id AND j17_contproj_rubricas.tipo = 'Capital'")->all();
        $tamanho = count($despesas);
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('uploads/Form3.docx');
        $templateProcessor->setValue('reais', 'R$');
        $templateProcessor->cloneRow('qte',$tamanho);
        $templateProcessor->setValue('outorgado', $coordenador->nome);
        for ($i = 0; $i<$tamanho ; $i++) {
            $templateProcessor->setValue('qte#'.($i+1), $despesas[$i]->quantidade);
            $templateProcessor->setValue('numero#'.($i+1), $despesas[$i]->nf);
            $templateProcessor->setValue('dat#'.($i+1), date("d/m/Y", strtotime($despesas[$i]->data_emissao)));
            $templateProcessor->setValue('numero#'.($i+1), $despesas[$i]->nf);
            $templateProcessor->setValue('descricao#'.($i+1), $despesas[$i]->descricao);
            $templateProcessor->setValue('unidade#'.($i+1), $despesas[$i]->quantidade);
            $templateProcessor->setValue('total#'.($i+1), number_format ($despesas[$i]->valor_despesa,2)." R$");
            //${unidade#1}	${total#1} ${unidade#1}	${total#1}

        }
        header('Content-Disposition: attachment; filename="Form3-RelBensdeCapital-4.docx"');
        $templateProcessor->saveAs('php://output');
    }


    public function actionForm1($id){
        $projeto = ContProjProjetos::find()->select("*")->where("id=$id")->one();
        $coordenador = \app\models\User::find()->select("*")->where("id=$projeto->coordenador_id")->one();
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('uploads/Form1.docx');
        $templateProcessor->setValue('orcamento', 'R$ '.number_format ($projeto->orcamento,2));
        $templateProcessor->setValue('outorgado', $coordenador->nome);
        $f = new \NumberFormatter("pt-br", \NumberFormatter::SPELLOUT);
        //echo $f->format(1432);
        $templateProcessor->setValue('extenso', ucfirst(strtolower($f->format($projeto->orcamento))). " reais");
        header('Content-Disposition: attachment; filename="Form1.docx"');
        $templateProcessor->saveAs('php://output');
    }

    public static function view($id)
    {
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
    public function actionDelete($id, $detalhe = false)
    {
        $model = $this->findModel($id);
        try {
            $model->delete();
            $this->mensagens('success', 'Excluir Projeto', 'Projeto excluido com sucesso!');
        } catch (\yii\base\Exception $e) {
            $this->mensagens('error', 'Excluir Projeto', 'Projeto não pode ser excluido pois existem rubricas associadas ao projeto!');
            if ($detalhe) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
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

    protected function mensagens($tipo, $titulo, $mensagem)
    {
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
