<?php
//include "../../php-cnes-master/ws-security.php";
include_once( dirname(dirname(dirname(__FILE__))).'/php-cnes-master/ws-security.php' );
$conMap = new PDO("pgsql:host=10.17.0.209;port=5470;dbname=cnes", "mapas", "mapas");
$conMap->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$conMap) {
    echo 'não conectou' . PHP_EOL;
} else {
    echo 'conectou mapas' . PHP_EOL;

$options = array( 'location' => 'https://servicoshm.saude.gov.br/cnes/EstabelecimentoSaudeService/v1r0', 
                  'encoding' => 'utf-8', 
                  'soap_version' => SOAP_1_2,
                  'connection_timeout' => 180,
                  'trace'        => 1, 
                  'exceptions'   => 1 );

$client = new SoapClient('https://servicoshm.saude.gov.br/cnes/EstabelecimentoSaudeService/v1r0?wsdl', $options);   
$client->__setSoapHeaders(soapClientWSSecurityHeader('CNES.PUBLICO', 'cnes#2015public'));

$function = 'consultarEstabelecimentoSaude';

//die();
//print("<pre>".print_r(getType($result,true))."</pre>");
$space = "SELECT * FROM estabelecimentos";
$query1 = $conMap->query($space);
$total = 0;

while ($row = $query1->fetch(PDO::FETCH_OBJ)) {
    //var_dump($row->cnes);
    $arguments = array( 'est' => array(
        'FiltroPesquisaEstabelecimentoSaude' => array(
            'CodigoCNES' => array(
                'codigo'      => $row->cnes
            )
        )
    )
    );
    
    $result = $client->__soapCall($function, $arguments);
    //var_dump($result->DadosGeraisEstabelecimentoSaude->CodigoCNES->codigo .' - '.$result->DadosGeraisEstabelecimentoSaude->tipoUnidade->descricao);
    $sqlUp = "UPDATE public.estabelecimentos SET tipo_unidade = '".$result->DadosGeraisEstabelecimentoSaude->tipoUnidade->descricao . "' WHERE cnes = '" .$row->cnes. "'";
    //printf($sqlUp);
    $conMap->exec($sqlUp);
    $total++;
}
// foreach ($result as $key => $value) {
//     var_dump($value->CodigoCNES->codigo);
// }
 echo $total;
?>