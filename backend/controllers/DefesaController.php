<?php

namespace backend\controllers;

use Yii;
use app\models\Defesa;
use app\models\DefesaSearch;
use app\models\BancaControleDefesas;
use app\models\LinhaPesquisa;
use app\models\Banca;
use app\models\BancaSearch;
use app\models\MembrosBanca;
use app\models\MembrosBancaSearch;
use app\models\Aluno;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\db\IntegrityException;
use yii\base\Exception;
use yii\web\UploadedFile;
use mPDF;

/**
 * DefesaController implements the CRUD actions for Defesa model.
 */
class DefesaController extends Controller
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
     * Lists all Defesa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DefesaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Defesa model.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return mixed
     */
    public function actionView($idDefesa, $aluno_id)
    {

        $model = $this->findModel($idDefesa, $aluno_id);

        $model_banca = new BancaSearch();
        $dataProvider = $model_banca->search(Yii::$app->request->queryParams,$model->banca_id);

        if ($model->load(Yii::$app->request->post() ) ) {
            if($model->banca->status_banca == 1 && $model->save(false))
                $this->mensagens('success', 'Conceito Atribuído', 'Conceito atribuído com sucesso.');
            else
                $this->mensagens('danger', 'Conceito não Atribuído', 'Ocorreu um erro ao atribuir o conceito a defesa. Verifique se a banca foi avaliada.');
        }

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPendentes()
    {
        $searchModel = new DefesaSearch();
        $dataProvider = $searchModel->searchPendentes(Yii::$app->request->queryParams);

        return $this->render('pendentes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLembretependencia($idDefesa, $aluno_id){
        
        $model = $this->findModel($idDefesa, $aluno_id);
        if($this->enviaNotificacaoPendenciaDefesa($model))
            $this->mensagens('success', 'Lembretes Enviados', 'Os Lembretes de pendência de defesas foram enviados com sucesso.');

        $this->redirect(['defesa/view', 'idDefesa' => $idDefesa, 'aluno_id' => $aluno_id]);
    }

    /**
     * Creates a new Defesa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($aluno_id)
    {
        
        $membrosBancaInternos = ArrayHelper::map(MembrosBanca::find()->where("filiacao = 'PPGI/UFAM'")->orderBy('nome')->all(), 'id', 'nome','filiacao');

        $membrosBancaExternos = ArrayHelper::map(MembrosBanca::find()->where("filiacao <> 'PPGI/UFAM'")->orderBy('nome')->all(), 'id', 'nome','filiacao');
        
        $membrosExternos = ArrayHelper::map(MembrosBanca::find()->where("filiacao <> 'PPGI/UFAM'")->orderBy('nome')->all(), 'id', 'nome');
        
        $model = new Defesa();
        
        $conceitoPendente = $model->ConceitoPendente($aluno_id);
        
        if ($conceitoPendente == true){

                $this->mensagens('danger', 'Defesas c/ Pendências', 'Existem defesas que estão pendentes de conceito ou Bancas pendentes de Deferimento pelo Coordenador.');

                return $this->redirect(['aluno/orientandos',]);            
            
        }
        

        $model->aluno_id = $aluno_id;

        $cont_Defesas = Defesa::find()->where("aluno_id = ".$aluno_id." AND conceito is NOT NULL")->count();
        
        $curso = Aluno::find()->select("curso")->where("id =".$aluno_id)->one()->curso;

            if($cont_Defesas == 0 && $curso == 1){
                $model->tipoDefesa = "Q1";
                $tipodefesa = 1;
            }
            else if($cont_Defesas == 0 && $curso == 2){
                $model->tipoDefesa = "Q1";
                $tipodefesa = 2;
            }
            else if ($cont_Defesas == 1 && $curso == 1){
                $model->tipoDefesa = "D";
                $tipodefesa = 3;
            }
            else if ($cont_Defesas == 1 && $curso == 2){
                $model->tipoDefesa = "Q2";
                $tipodefesa = 4;
            }
            else if ($cont_Defesas == 2 && $curso == 2){
                $model->tipoDefesa = "T";
                $tipodefesa = 5;
            }

        if ($model->load(Yii::$app->request->post() ) ) {

            $model->auxiliarTipoDefesa = $tipodefesa;

            $model_ControleDefesas = new BancaControleDefesas();
            if($model->tipoDefesa == "Q1" && $curso == 2){
                $model_ControleDefesas->status_banca = 1;
            }
            else{
                $model_ControleDefesas->status_banca = null;
            }
            $model_ControleDefesas->save(false);

            $model->banca_id = $model_ControleDefesas->id;

            if (! $model->uploadDocumento(UploadedFile::getInstance($model, 'previa'))){
                $this->mensagens('danger', 'Erro ao salvar defesa', 'Ocorreu um erro ao salvar a defesa. Verifique os campos e tente novamente');
                return $this->redirect(['aluno/orientandos',]);
            }


            try{
                
                if($model->tipoDefesa == "Q1" && $model->curso == "Doutorado"){


                    if($model->save(false)){

                        $this->mensagens('success', 'Defesa salva', 'A defesa foi salva com sucesso.');
                        return $this->redirect(['view', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id]);
                    }

                }
                else{

                    $model->salvaMembrosBanca();


                    if($model->save()){

                        $this->mensagens('success', 'Defesa salva', 'A defesa foi salva com sucesso.');
                        
                        return $this->redirect(['passagens', 'banca_id' => $model->banca_id]);

                    }else{
                        $this->mensagens('danger', 'Erro ao salvar defesa', 'Ocorreu um erro ao salvar a defesa. Verifique os campos e tente novamente');
                    }

                }

            } catch(Exception $e){
                $this->mensagens('danger', 'Erro ao salvar Membros da banca', 'Ocorreu um Erro ao salvar os membros da bancas.');
            }

        }
        else if ( ($curso == 1 && $cont_Defesas >= 2) || ($curso == 2 && $cont_Defesas >= 3) ){
            $this->mensagens('danger', 'Solicitar Banca', 'Não foi possível solicitar banca, pois esse aluno já possui '.$cont_Defesas.' defesas cadastradas');
            return $this->redirect(['aluno/orientandos',]);
        }

        return $this->render('create', [
            'model' => $model,
            'tipodefesa' => $tipodefesa,
            'membrosBancaInternos' => $membrosBancaInternos,
            'membrosBancaExternos' => $membrosBancaExternos,
        ]);
    }
    
    public function actionPassagens($banca_id){
        

        $banca = Banca::find()->select("j17_banca_has_membrosbanca.* , mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
        ->where(["banca_id" => $banca_id , "funcao" => "E"])->all();
        
        return $this->render('passagens', [
            'model' => $banca,
        ]);
    
        
        
    }
    
    public function actionPassagens2(){

    $where = "";

    $banca_id = $_POST['banca_id'];

        if(!empty($_POST['check_list'])){
            // Loop to store and display values of individual checked checkbox.

           $arrayChecked = $_POST['check_list'];

            for($i=0; $i<count($arrayChecked)-1; $i++){
                $where = $where."membrosbanca_id = ".$arrayChecked[$i]." OR ";
            }
                $where = $where."membrosbanca_id = ".$arrayChecked[$i];
        }

  
        if ($where != ""){
            $sqlSim = "UPDATE j17_banca_has_membrosbanca SET passagem = 'S' WHERE ($where) AND banca_id = ".$banca_id;
            //$sqlNao = "UPDATE j17_banca_has_membrosbanca SET passagem = 'N' WHERE $where";

            try{
                echo Yii::$app->db->createCommand($sqlSim)->execute();

              //  echo Yii::$app->db->createCommand($sqlNao)->execute();

                $this->mensagens('success', 'Passagens', 'As alterações das passagens foram salvas com sucesso.');

                return $this->redirect(['aluno/orientandos',]);

            }
            catch(\Exception $e){

                $this->mensagens('danger', 'Erro ao salvar', 'Ocorreu um Erro ao salvar essas alterações no Banco. Tente Novamente.');
            }
        }
        else {
            $this->mensagens('success', 'Passagens', 'As alterações das passagens foram salvas com sucesso.');
            return $this->redirect(['aluno/orientandos',]);
        }


        
    }


    /**
     * Updates an existing Defesa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return mixed
     */
    public function actionUpdate($idDefesa, $aluno_id)
    {


        //SÓ PODE EDITAR A DEFESA SE ELA NÃO FOI CONCEITUADA! TEM DE CHECAR SE CONCEITO == NULL

        $model_aluno = Aluno::find()->where("id = ".$aluno_id)->one();

        $model = $this->findModel($idDefesa, $aluno_id);

        $model->data = date('d-m-Y', strtotime($model->data));

        if ($model->load(Yii::$app->request->post())) {
            
            $model->data = date('Y-m-d', strtotime($model->data));
            $model->save(false);
           
            
            return $this->redirect(['view', 'idDefesa' => $model->idDefesa, 'aluno_id' => $model->aluno_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'model_aluno' => $model_aluno,

            ]);
        }
    }

    /**
     * Deletes an existing Defesa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return mixed
     */
    public function actionDelete($idDefesa, $aluno_id)
    {

        //SÓ PODE EXCLUIR A DEFESA SE ELA NÃO NÃO POSSUIR BANCA! TEM DE CHECAR SE banca_id == 0
        $model = $this->findModel($idDefesa, $aluno_id);

        $banca = BancaControleDefesas::find()->where(["id" => $model->banca_id])->one();


        if($banca->status_banca != null){
            $this->mensagens('danger', 'Não Excluído', 'Não foi possível excluir, pois essa defesa já possui banca aprovada');
            return $this->redirect(['index']);
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    public function cabecalhoRodape($pdf){
            $pdf->SetHTMLHeader('
                <table style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; ">
                    <tr>
                        <td width="20%" align="center" style="font-family: Helvetica;font-weight: bold; font-size: 175%;"> <img src = "../../frontend/web/img/logo-brasil.jpg" height="90px" width="90px"> </td>
                        <td width="60%" align="center" style="font-family: Helvetica;font-weight: bold; font-size: 135%;">  PODER EXECUTIVO <br> MINISTÉRIO DA EDUCAÇÃO <br> INSTITUTO DE COMPUTAÇÃO <br><br> PROGRAMA DE PÓS-GRADUAÇÃO EM INFORMÁTICA </td>
                        <td width="20%" align="center" style="font-family: Helvetica;font-weight: bold; font-size: 175%;"> <img style="margin-left:8%" src = "../../frontend/web/img/ufam.jpg" height="90px" width="75px"> </td>
                    </tr>
                </table>
                <hr>
            ');

            $pdf->SetHTMLFooter('
				<hr>
                <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
                    <tr>
                        <td  colspan = "3" align="center" ><span style="font-weight: bold"> Av. Rodrigo Otávio, 6.200 - Campus Universitário Senador Arthur Virgílio Filho - CEP 69077-000 - Manaus, AM, Brasil </span></td>
                    </tr>
                    <tr>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  Tel. (092) 3305-1193/2808/2809</td>
                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  E-mail: secretaria@icomp.ufam.edu.br</td>

                        <td width="33%" align="center" style="font-weight: bold; font-style: italic;">  http://www.icomp.ufam.edu.br </td>
                    </tr>
                </table>
            ');

            return $pdf;
    }

    public function actionConvitepdf($idDefesa, $aluno_id){

        $model = $this->findModel($idDefesa, $aluno_id);

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();

        if($modelAluno->curso == 1){
            $curso = "Mestrado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Mestrado";
            }
            else{
                $tipoDefesa = "Dissertação de Mestrado";
            }
        }
        else{
            $curso = "Doutorado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else  if($model->tipoDefesa == "Q2"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else{
                $tipoDefesa = "Tese de Doutorado";
            }

        }


        $banca = Banca::find()
        ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
        ->where(["banca_id" => $model->banca_id])->all();

        $bancacompleta = "";

        foreach ($banca as $rows) {
            if($rows->funcao == "P"){
                $funcao = "(Presidente)";
            }
            else{
                $funcao = "";
            }
            $bancacompleta = $bancacompleta . $rows->membro_nome.' - '.$rows->membro_filiacao.' '.$funcao.'<br>';
        }

        $pdf = new mPDF('utf-8','A4','','','15','15','42','30');

        $pdf = $this->cabecalhoRodape($pdf);

             $pdf->WriteHTML('
                <div style="text-align:center"> <h3>  CONVITE À COMUNIDADE </h3> </div>
                <p style = "text-align: justify;">
                     A Coordenação do Programa de Pós-Graduação em Informática PPGI/UFAM tem o prazer de convidar toda a
                    comunidade para a sessão pública de apresentação de defesa de '.$tipoDefesa.':
                </p>
            ');

             $pdf->WriteHTML('
                <div style="text-align:center"> <h4>'.$model->titulo.'</h4> </div>
                <p style = "text-align: justify;">
                RESUMO: '.$model->resumo.'
                </p>
            ');

             $pdf->WriteHTML('

                    CANDIDATO: '.$model->nome.' <br><br>

                    BANCA EXAMINADORA: <br>
                    <div style="margin-left:15%"> '.$bancacompleta.' </div>

            ');

             $coordenadorppgi = new Defesa();
             $coordenadorppgi = $coordenadorppgi->getCoordenadorPPGI();


             $pdf->WriteHTML('
                <p> 
                    LOCAL: '.$model->local.'
                </p>
                <p> 
                    DATA: '.$model->data.'
                </p>
                <p> 
                    HORÁRIO: '.$model->horario.'
                </p>
				<br><br>
                <div style="text-align:center"> 
                    <p><font style="font-size:medium">'.$coordenadorppgi.'<br>
					<font style="font-size:small"> Coordenador(a) do Programa de Pós-Graduação em Informática PPGI/UFAM </p>

                </div>
            ');

    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);



    }

    public function actionAtadefesapdf($idDefesa, $aluno_id){

    $model = $this->findModel($idDefesa, $aluno_id);

        $arrayMes = array(
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro",
            );

        $modelAlunoLinha = LinhaPesquisa::find()->innerJoin("j17_aluno as a","a.id = ".$aluno_id)->one();

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();

        if($modelAluno->curso == 1){
                $curso = "Mestrado";
                $titulo = "mestre";

                $tipoDefesa = "Dissertação de Mestrado";
                $tipoDefesaUp = "DISSERTAÇÃO DE MESTRADO";

        }
        else{
                $curso = "Doutorado";
                $titulo = "doutor";
                $tipoDefesa = "Tese de Doutorado";
                $tipoDefesaUp = "TESE DE DOUTORADO";
        }

            $banca = Banca::find()
            ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
            ->where(["banca_id" => $model->banca_id])->orderBy(['funcao'=>SORT_DESC])->all();

            $bancacompleta = "";
            $outrosMembros = "";

            foreach ($banca as $rows) {
                if($rows->funcao == "P"){
                    $funcao = "(Presidente)";
                    $presidente = $rows->membro_nome;
                }
                else{
                    $funcao = "";
                    $outrosMembros = $outrosMembros .', '. $rows->membro_nome;
                }
                $bancacompleta = $bancacompleta . $rows->membro_nome.' - '.$rows->membro_filiacao.' '.$funcao.'<br>';
            }

            $pdf = new mPDF('utf-8','A4','','','15','15','42','30');

            $pdf = $this->cabecalhoRodape($pdf);


             $pdf->WriteHTML('
                <div style="text-align:center;"> <h4>  '.$model->numDefesa.'ª ATA DE DEFESA PÚBLICA DE '.$tipoDefesaUp.' </h4> </div>
            ');

             $dia = date('d', strtotime($model->data));
             $mes = date("m",strtotime($model->data));

             $pdf->WriteHTML('

                <p style = "text-align: justify; font-family: Times New Roman, Arial, serif; font-size: 100%;">
                    Aos '.$dia.' dias do mês de '.$arrayMes[$mes].' do ano de '.date("Y", strtotime($model->data)).', às '.$model->horario.'h, na '.$model->local.' da Universidade Federal do Amazonas, situada na Av. Rodrigo Otávio, 6.200, Campus Universitário, Setor Norte, Coroado, nesta Capital, ocorreu a sessão pública de defesa de '.$tipoDefesa.' intitulada "'.$model->titulo.'" apresentada pelo discente '.$model->nome.' que concluiu todos os pré-requisitos exigidos para a obtenção do título de '.$titulo.' em informática, conforme estabelece o artigo 52 do regimento interno do curso. Os trabalhos foram instalados pelo(a) '.$presidente.', orientador(a) e presidente da Banca Examinadora, que foi constituí­da, ainda, pelos membros convidados: '.$outrosMembros.'. A Banca Examinadora tendo decidido aceitar a dissertação, passou à arguição pública do candidato.
                </p>
            ');


             $pdf->WriteHTML('Encerrados os trabalhos, os examinadores expressaram o parecer abaixo. <br><br>

                A comissão considerou a '.$tipoDefesa.': <br>
                (  &#32;&#32;&#32;  ) Aprovada <br>
                (  &#32;&#32;&#32;  ) Suspensa <br>
                (  &#32;&#32;&#32;  ) Reprovada <br>
                <p style = "text-align: justify;"> 
                Proclamados os resultados, foram encerrados os trabalhos e, para constar, eu, Elienai Nogueira, Secretária do Programa de Pós-Graduação em Informática, lavrei a presente ata, que assino juntamente com os Membros da Banca Examinadora. 
                </p>
                <br>
                ');


            foreach ($banca as $rows) {

                if ($rows->funcao == "P"){
                    $funcao = "Presidente";
                }
                else if($rows->funcao == "E"){
                    $funcao = "Membro Externo";
                }
                else {
                    $funcao = "Membro Interno";
                }
                 $pdf->WriteHTML('

                    <div style="float: right;
					line-height: 2.2;
                                width: 50%;">
                                Assinatura: ............................................................
                    </div>

                    <div style="float: left;
                                width: 50%;
                                text-align:left;
								line-height: 2.2;
                                margin-bottom:3%;">
                            '.$rows->membro_nome.'
                    </div>
                ');
             }

                 $pdf->WriteHTML('

                    <div style="float: left;
                                width: 60%;
                                margin-top:3%;">
                                ____________________________________________________ <br>

                                Secretaria
                    </div>

                    <div style="text-align:right"> <h4>Manaus, '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).'</h4> </div>

                ');

    $pdf->addPage();

             $pdf->WriteHTML('
                <div style="text-align:center;"> <h4>  FOLHA DE SUSPENSÃO </h4> </div>
            ');

             $pdf->WriteHTML('
                <p style = "text-align: justify; font-family: Times New Roman, Arial, serif; font-size: 120%; margin-bottom:10%">

                 A Banca Examinadora, com base no Art. 13, da Resolução nº 033/2014 - CONSEPE, de 30 de setembro de 2014, decide suspender a sessão, pelo prazo de ______ dias, respeitando o período máximo de 60 dias estabelecido no § 1º do referido artigo:

                </p>
            ');

            foreach ($banca as $rows) {

                if ($rows->funcao == "P"){
                    $funcao = "Presidente";
                }
                else if($rows->funcao == "E"){
                    $funcao = "Membro Externo";
                }
                else {
                    $funcao = "Membro Interno";
                }
                 $pdf->WriteHTML('

                    <div style="float: right;
                                width: 60%;
                                text-align:right;
                                margin-bottom:5%;
								line-height: 4.0;
                                border-top:solid 1px">
                            '.$rows->membro_nome.' - '.$funcao.'
                    </div>

                ');

             }

             $pdf->WriteHTML('

                    <div style="text-align:center"> <h4>Manaus, '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).'</h4> </div>

                ');



    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);
}



    public function actionAtapdf($idDefesa, $aluno_id){

    $model = $this->findModel($idDefesa, $aluno_id);

        $modelAlunoLinha = LinhaPesquisa::find()->innerJoin("j17_aluno as a","a.id = ".$aluno_id)->one();

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();

        if($modelAluno->curso == 1){
            $curso = "Mestrado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Mestrado";
            }
            else{
                $tipoDefesa = "Dissertação de Mestrado";
            }
        }
        else{
            $curso = "Doutorado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else  if($model->tipoDefesa == "Q2"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else{
                $tipoDefesa = "Tese de Doutorado";
            }

        }

            $banca = Banca::find()
            ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
            ->where(["banca_id" => $model->banca_id])->orderBy(['funcao'=>SORT_DESC])->all();

            $bancacompleta = "";

            foreach ($banca as $rows) {
                if($rows->funcao == "P"){
                    $funcao = "(Presidente)";
                }
                else{
                    $funcao = "";
                }
                $bancacompleta = $bancacompleta . $rows->membro_nome.' - '.$rows->membro_filiacao.' '.$funcao.'<br>';
            }

            $pdf = new mPDF('utf-8','A4','','','15','15','42','30');

            $pdf = $this->cabecalhoRodape($pdf);

                 $pdf->WriteHTML('
                    <div style="text-align:center"> <h3>  Avaliação de Proposta de '.$tipoDefesa.' </h3> </div>
                    <p style = "font-weight: bold;">
                        DADOS DO(A) ALUNO(A): </p>
                        Nome: '.$model->nome.'  <br><br>
                        Área de Conceitação: Ciência da Computação  <br><br>
                        Linha de Pesquisa: '.$modelAlunoLinha->nome.'  <br><br>
                        Orientador: '.$modelAluno->nome.'  <br><br>
                        <hr>
                    </p>
                ');


                 $pdf->WriteHTML('
                    <p style = "font-weight: bold;">
                        DADOS DA DEFESA:
                    </p>
                    <table style =" margin-bottom:20px;">
                        <tr>
                            <td colspan="4"> Título: '.$model->titulo.' </td>
                        </tr>
                        <tr>
                        <td coslpan="4"> &nbsp;  </td>
                        </tr>
                        <tr>
                            <td> Data: '.date("d-m-Y",  strtotime($model->data)).' </td>
                            <td> Hora: '.$model->horario.' </td>
                            <td colspan="2"> Local: '.$model->local.' </td>

                        </tr>
                    </table>
                    <table style =" margin-bottom:60px; width:100%;">
                    <tr>
                        <td>
                            <h4> Avaliação da Banca Examinadora </h4>
                        </td>

                        <td align="right">
                            <h4> Conceito: _______________________ </h4>
                        </td>
                    </tr>
                    </table>



                ');


            foreach ($banca as $rows) {

                if ($rows->funcao == "P"){
                    $funcao = "Presidente";
                }
                else if($rows->funcao == "E"){
                    $funcao = "Membro Externo";
                }
                else {
                    $funcao = "Membro Interno";
                }
                 $pdf->WriteHTML('

                    <div style="float: right;
								height:40px;
                                width: 60%;
                                text-align:right;
                                margin-bottom:5%;
                                border-top:solid">
                            '.$rows->membro_nome.' - '.$funcao.'
					</div>
					
                ');

             }

    $pdf->addPage();

    $pdf->WriteHTML('
    <div style="text-align:center"> <h3>  Avaliação de Proposta de '.$tipoDefesa.' </h3> </div>
    <br>
        PARECER:
    ');

     $pdf->WriteHTML('

        <div style="width: 100%;
                    height:65%;
                    text-align:right;
                    margin-top:4%;
                    margin-bottom:8%;
                    border:double 1px">
        </div>

    ');

     $pdf->WriteHTML('

        <table style="width:100%; text-align:center">
            <tr>
                <td>
                _________________________________________
                </td>
                <td>
                _________________________________________  
                </td>   
            </tr>
            <tr>
                <td>
                Assinatura do(a) Orientador(a) 
                </td>
                <td>
                Assinatura do(a) Discente
                </td>   
            </tr>
            <tr>
            <td colspan="2"> <br><br> <b> Obs.: Anexar PROPOSTA a ser apresentada  </b> </td>
            </tr>
        </table>

    ');


    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);
}


    public function actionFolhapdf($idDefesa, $aluno_id){

        $arrayMes = array(
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro",
            );

        $model = $this->findModel($idDefesa, $aluno_id);

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();

        if($modelAluno->curso == 1){
            $curso = "Mestrado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Mestrado";
            }
            else{
                $tipoDefesa = "Dissertação de Mestrado";
            }
        }
        else{
            $curso = "Doutorado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else  if($model->tipoDefesa == "Q2"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else{
                $tipoDefesa = "Tese de Doutorado";
            }

        }

        $banca = Banca::find()
        ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
        ->where(["banca_id" => $model->banca_id])->orderBy(['funcao'=>SORT_DESC])->all();

        $bancacompleta = "";

        foreach ($banca as $rows) {
            if($rows->funcao == "P"){
                $funcao = "(Presidente)";
            }
            else{
                $funcao = "";
            }
            $bancacompleta = $bancacompleta . $rows->membro_nome.' - '.$rows->membro_filiacao.' '.$funcao.'<br><br><br><br>';
        }

        $pdf = new mPDF('utf-8','A4','','','15','15','42','30');

        $pdf = $this->cabecalhoRodape($pdf);

             $pdf->WriteHTML('
                <div style="text-align:center"> <h1>  FOLHA DE APROVAÇÃO </h1> </div>
            ');

             $pdf->WriteHTML('
                <div style="text-align:center"> <h3>'.$model->titulo.'</h3> </div>
                <div style="text-align:center"> <h3>'.$model->nome.'</h3> </div>
                <p style = "text-align: justify;">
                    '.$tipoDefesa.' defendida e aprovada pela banca examinadora constituída pelos Professores:
                </p>
            ');

             $mes = date("m",strtotime($model->data));


             $pdf->WriteHTML('
                    <br><br>
                    <div style="margin-left:5%"> '.$bancacompleta.' </div>
                    <div style="text-align:center"> <h3>Manaus, '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).'</h3> </div>
            ');


    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);
}

   public function actionAgradecimentopdf($idDefesa, $aluno_id, $membrosbanca_id){

        $arrayMes = array(
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro",
            );

        $model = $this->findModel($idDefesa, $aluno_id);

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();



        $banca = Banca::find()
        ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
        ->where(["membrosbanca_id" => $membrosbanca_id])->one();

        if ($banca->funcao == "P"){
                $participacao = "presidente/orientador(a)";
        }
        else if ($banca->funcao == "I"){
                $participacao = "membro interno";
        }
        else if ($banca->funcao == "E"){
                $participacao = "membro externo";
        }

        if($modelAluno->curso == 1){
            $curso = "Mestrado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Mestrado";
            }
            else{
                $tipoDefesa = "Dissertação de Mestrado";
            }
        }
        else{
            $curso = "Doutorado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else  if($model->tipoDefesa == "Q2"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else{
                $tipoDefesa = "Tese de Doutorado";
            }

        }

        $pdf = new mPDF('utf-8','A4','','','15','15','32','30');

        $pdf = $this->cabecalhoRodape($pdf);

             $mes = date("m",strtotime($model->data));

             $pdf->WriteHTML('
                <div style="text-align:center; padding:10% 10%;"> <h2>  AGRADECIMENTO </h2> </div>
            ');

             $pdf->WriteHTML('
                <p style = "text-align: justify; line-height: 3.0; font-family: Times New Roman, Arial, serif; font-size: 120%;">
                    AGRADECEMOS a participação do(a) <b>'.$banca->membro_nome.'</b> como
                    '.$participacao.'(a) da banca examinadora referente à apresentação da Defesa de '.$tipoDefesa.'
                    do(a) aluno(a), abaixo especificado(a), do curso de '.$curso.' em Informática do
                    Programa de Pós-Graduação em Informática da Universidade Federal do Amazonas - realizada no dia 
                    '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).' às '.$model->horario.'.
                </p>
            ');

             $mes = date("m",strtotime($model->data));


             $pdf->WriteHTML('
                    <br><br><br>
                <div style = "text-align: justify; font-family: Times New Roman, Arial, serif; font-size: 120%;"> Título: '.$model->titulo.'</div>
                <br>
                <div style = "text-align: justify; font-family: Times New Roman, Arial, serif; font-size: 120%;"> Aluno(a): '.$model->nome.'</div>
                    <br><br><br><br>
                    <div style="text-align:center"> <h3>Manaus, '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).'</h3> </div>
            ');


    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);
}

   public function actionDeclaracaopdf($idDefesa, $aluno_id, $membrosbanca_id){

        $arrayMes = array(
            "01" => "Janeiro",
            "02" => "Fevereiro",
            "03" => "Março",
            "04" => "Abril",
            "05" => "Maio",
            "06" => "Junho",
            "07" => "Julho",
            "08" => "Agosto",
            "09" => "Setembro",
            "10" => "Outubro",
            "11" => "Novembro",
            "12" => "Dezembro",
            );

        $model = $this->findModel($idDefesa, $aluno_id);

        $modelAluno = Aluno::find()->select("u.nome as nome, j17_aluno.curso as curso")->where(["j17_aluno.id" => $aluno_id])->innerJoin("j17_user as u","j17_aluno.orientador = u.id")->one();

        $banca = Banca::find()
        ->select("j17_banca_has_membrosbanca.* , j17_banca_has_membrosbanca.funcao ,mb.nome as membro_nome, mb.filiacao as membro_filiacao, mb.*")->leftJoin("j17_membrosbanca as mb","mb.id = j17_banca_has_membrosbanca.membrosbanca_id")
        ->where(["membrosbanca_id" => $membrosbanca_id])->one();

        if ($banca->funcao == "P"){
                $participacao = "presidente/orientador(a)";
        }
        else if ($banca->funcao == "I"){
                $participacao = "membro interno";
        }
        else if ($banca->funcao == "E"){
                $participacao = "membro externo";
        }

        if($modelAluno->curso == 1){
            $curso = "Mestrado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Mestrado";
            }
            else{
                $tipoDefesa = "Dissertação de Mestrado";
            }
        }
        else{
            $curso = "Doutorado";

            if($model->tipoDefesa == "Q1"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else  if($model->tipoDefesa == "Q2"){
                $tipoDefesa = "Exame de Qualificação de Doutorado";
            }
            else{
                $tipoDefesa = "Tese de Doutorado";
            }

        }


        $pdf = new mPDF('utf-8','A4','','','15','15','32','30');

        $pdf = $this->cabecalhoRodape($pdf);

             $pdf->WriteHTML('

                <div style="text-align:center; padding:10% 10%;"> <h2>  DECLARAÇÃO </h2> </div>
            ');

             $mes = date("m",strtotime($model->data));

             $pdf->WriteHTML('
                <p style = "text-align: justify; line-height: 3.0; font-family: Times New Roman, Arial, serif; font-size: 120%;">
                    DECLARAMOS para os devidos fins que o(a) <b> Prof(a) '.$banca->membro_nome.' </b> fez
                    parte, na qualidade de '.$participacao.', da comissão julgadora da defesa de '.$tipoDefesa.'
                    do(a) aluno(a) '.$model->nome.' , intitulada <b>"'.$model->titulo.'    "</b>, do curso de '.$curso.' em Informática do Programa de Pós-Graduação em Informática da Universidade Federal do Amazonas, realizada no dia 
                    '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).' às '.$model->horario.'.
                </p>
            ');


             $pdf->WriteHTML('
                    <br><br><br><br>
                    <div style="text-align:center"> <h3>Manaus, '.date("d", strtotime($model->data)).' de '.$arrayMes[$mes].' de '.date("Y", strtotime($model->data)).'</h3> </div>
            ');


    $pdfcode = $pdf->output();
    fwrite($arqPDF,$pdfcode);
    fclose($arqPDF);
}


    public function actionAprovar($idDefesa, $aluno_id)
    {
        $model = $this->findModel($idDefesa, $aluno_id);

        $model->conceito = "Aprovado";

        if ($model->save(false)) {

             $this->mensagens('success', 'Aluno', 'Aluno Aprovado com sucesso');

            return $this->redirect(['index']);
        } else {
            $this->mensagens('danger', 'Aluno', 'Não foi possível atribuir conceito para este aluno, tente mais tarde');
            return $this->redirect(['index']);
        }
    }


    public function actionReprovar($idDefesa, $aluno_id)
    {
        $model = $this->findModel($idDefesa, $aluno_id);

        $model->conceito = "Reprovado";

        if ($model->save(false)) {

             $this->mensagens('success', 'Aluno', 'Aluno Reprovado com sucesso');

            return $this->redirect(['index']);
        } else {
            $this->mensagens('danger', 'Aluno', 'Não foi possível atribuir conceito para este aluno, tente mais tarde');
            return $this->redirect(['index']);
        }
    }


    /**
     * Finds the Defesa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $idDefesa
     * @param integer $aluno_id
     * @return Defesa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($idDefesa, $aluno_id)
    {
        if (($model = Defesa::findOne(['idDefesa' => $idDefesa, 'aluno_id' => $aluno_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    function enviaNotificacaoPendenciaDefesa($model){
        
        if ($model->tipoDefesa == 'Q1'){
            $tipoexame = "Qualificação I";
        }
        else if ($model->tipoDefesa == 'Q2'){
            $tipoexame = "Qualificação II";
        }
        else if ($model->tipoDefesa == 'D'){
            $tipoexame = "Dissertação";
        }
        else {
            $tipoexame = "Tese";
        }

        $message = "";
                
        $nome_aluno = $model->modelAluno->nome;
        $emailOrientador = $model->modelAluno->orientador1->email;    
        $emailAluno = $model->modelAluno->email;
        $nomeOrientador = $model->modelAluno->orientador1->nome; 
        $emails[] = $emailOrientador;
        $emails[] = $emailAluno;
        //$emails[] = "secppgi@ufam.edu.br";
        //$emails[] = "coordenadorppgi@icomp.ufam.edu.br";
        
        
        // subject
        $subject  = "[IComp/UFAM] Pendência em relação à Defesa";
        
        // message
        $message .= "Informamos que há uma pendência de defesa do aluno abaixo relacionado: \r\n\n";
        $message .= "CANDIDATO: ".$nome_aluno."\r\n";
        $message .= "ORIENTADOR: ".$nomeOrientador."\r\n";
        $message .= "EXAME: ".$tipoexame."\r\n\n";
        $message .= "Atenciosamente,\r\n\n";
        $message .= "Secretaria - ICOMP\r\n"  ;

       try{
           Yii::$app->mailer->compose()
            ->setFrom("secretariappgi@icomp.ufam.edu.br")
            ->setTo($emails)
            ->setSubject($subject)
            ->setTextBody($message)
            ->send();
        }catch(Exception $e){
            $this->mensagens('warning', 'Erro ao enviar Email(s)', 'Ocorreu um Erro ao Enviar as Lembres de Pendência de Defesa.
                Tente novamente ou contate o adminstrador do sistema');
            return false;
        }
        
        return true;
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
