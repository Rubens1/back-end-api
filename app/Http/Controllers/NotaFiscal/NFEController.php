<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NFEController extends Controller
{
    /**
     * alinhamento padrão do logo (C-Center)
     *
     * @var string
     */
    public $logoAlign='C';
    /**
     * Posição
     *
     * @var float
     */
    public $yDados=0;
    /**
     * Situação
     *
     * @var integer
     */
    public $situacaoExterna=0;
    /**
     * Numero DPEC
     *
     * @var string
     */
    public $numero_registro_dpec='';
    /**
     * quantidade de canhotos a serem montados, geralmente 1 ou 2
     *
     * @var integer
     */
    public $qCanhoto=1;

    // INÍCIO ATRIBUTOS DE PARÂMETROS DE EXIBIÇÃO
    /**
     * Parâmetro para exibir ou ocultar os valores do PIS/COFINS.
     *
     * @var boolean
     */
    public $exibirPIS=true;

    // INÍCIO ATRIBUTOS DE PARÂMETROS DE EXIBIÇÃO
    /**
     * Parâmetro para exibir ou ocultar os valores do ICMS Interestadual e Valor Total dos Impostos.
     *
     * @var boolean
     */
    public $exibirIcmsInterestadual=true;


    /**
     * Parâmetro para exibir ou ocultar o texto sobre valor aproximado dos tributos.
     *
     * @var boolean
     */
    public $exibirValorTributos=true;
    /**
     * Parâmetro para exibir ou ocultar o texto adicional sobre a forma de pagamento
     * e as informações de fatura/duplicata.
     *
     * @var boolean
     */
    public $exibirTextoFatura=false;
    /**
     * Parâmetro do controle se deve concatenar automaticamente informações complementares
     * na descrição do produto, como por exemplo, informações sobre impostos.
     *
     * @var boolean
     */
    public $descProdInfoComplemento=true;
    /**
     * Parâmetro do controle se deve gerar quebras de linha com "\n" a partir de ";" na descrição do produto.
     *
     * @var boolean
     */
    public $descProdQuebraLinha=true;
    // FIM ATRIBUTOS DE PARÂMETROS DE EXIBIÇÃO

    /**
     * objeto fpdf()
     *
     * @var object
     */
    protected $pdf;
    /**
     * XML NFe
     *
     * @var string
     */
    protected $xml;
    /**
     * path para logomarca em jpg
     *
     * @var string
     */
    protected $logomarca='';
    /**
     * mesagens de erro
     *
     * @var string
     */
    protected $errMsg='';
    /**
     * status de erro true um erro ocorreu false sem erros
     *
     * @var boolean
     */
    protected $errStatus=false;
    /**
     * orientação da DANFE
     * P-Retrato ou
     * L-Paisagem
     *
     * @var string
     */
    protected $orientacao='P';
    /**
     * formato do papel
     *
     * @var string
     */
    protected $papel='A4';
    /**
     * destino do arquivo pdf
     * I-borwser,
     * S-retorna o arquivo,
     * D-força download,
     * F-salva em arquivo local
     *
     * @var string
     */
    protected $destino = 'I';
    /**
     * diretorio para salvar o pdf com a opção de destino = F
     *
     * @var string
     */
    protected $pdfDir='';
    /**
     * Nome da Fonte para gerar o DANFE
     *
     * @var string
     */
    protected $fontePadrao='Times';
    /**
     * versão
     *
     * @var string
     */
    protected $version = '2.2.8';
    /**
     * Texto
     *
     * @var string
     */
    protected $textoAdic = '';
    /**
     * Largura
     *
     * @var float
     */
    protected $wAdic = 0;
    /**
     * largura imprimivel, em milímetros
     *
     * @var float
     */
    protected $wPrint;
    /**
     * Comprimento (altura) imprimivel, em milímetros
     *
     * @var float
     */
    protected $hPrint;
    /**
     * largura do canhoto (25mm) apenas para a formatação paisagem
     *
     * @var float
     */
    protected $wCanhoto=25;
    /**
     * Formato chave
     *
     * @var string
     */
    protected $formatoChave="#### #### #### #### #### #### #### #### #### #### ####";
    /**
     * quantidade de itens já processados na montagem do DANFE
     *
     * @var integer
     */
    protected $qtdeItensProc;

    /**
     * Document
     *
     * @var DOMDocument
     */
    protected $dom;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $infNFe;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $ide;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $entrega;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $retirada;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $emit;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $dest;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $enderEmit;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $enderDest;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $det;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $cobr;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $dup;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $ICMSTot;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $ISSQNtot;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $transp;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $transporta;
    /**
     * Node
     *
     * @var DOMNode
     */
    protected $veicTransp;
    /**
     * Node reboque
     *
     * @var DOMNode
     */
    protected $reboque;
    /**
     * Node infAdic
     *
     * @var DOMNode
     */
    protected $infAdic;
    /**
     * Tipo de emissão
     *
     * @var integer
     */
    protected $tpEmis;
    /**
     * Node infProt
     *
     * @var DOMNode
     */
    protected $infProt;
    /**
     * 1-Retrato/ 2-Paisagem
     *
     * @var integer
     */
    protected $tpImp;
    /**
     * Node compra
     *
     * @var DOMNode
     */
    protected $compra;
    /**
     * ativa ou desativa o modo de debug
     *
     * @var integer
     */
    protected $debugMode=2;
    
}
