<?php


require_once "php-cnes-master/ws-security.php";


ini_set('display_errors', true);


$conCnes = new PDO("pgsql:host=10.17.0.209;port=5470;dbname=cnes", "mapas", "mapas");
$conCnes->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$conCnes) {
    echo 'não conectou';
}


$conMap = new PDO("pgsql:host=10.17.0.209;port=5470;dbname=mapas", "mapas", "mapas");
$conMap->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$conMap) {
    echo 'não conectou';
}


//$tipos = include_once '../../../src/protected/application/conf/space-types.php';

$schemaCnes = 'public';


$sql = "
    SELECT DISTINCT cns, nome   
    FROM public.profissionais WHERE cpf is null ORDER BY nome 
";
$query1 = $conCnes->query($sql);
while ($row = $query1->fetch(PDO::FETCH_OBJ)) {

    echo $row->cns . PHP_EOL;

    if ($row->cns != '') {
        $options = array('location' => 'https://servicoshm.saude.gov.br/cnes/ProfissionalSaudeService/v1r0',
            'encoding' => 'utf-8',
            'soap_version' => SOAP_1_2,
            'connection_timeout' => 18000000000000000000000,
            'trace' => 0,
            'exceptions' => 0);

        $client = new SoapClient('https://servicoshm.saude.gov.br/cnes/ProfissionalSaudeService/v1r0?wsdl', $options);
        $client->__setSoapHeaders(soapClientWSSecurityHeader('CNES.PUBLICO', 'cnes#2015public'));

        $function = 'consultarProfissionalSaude';

        $arguments= array( 'prof' => array(
            'FiltroPesquisaProfissionalSaude' => array(
                'CNS' => array(
                    'numeroCNS'  => $row->cns
                )
            )
        )
        );

        $result = $client->__soapCall($function, $arguments);


        if (@isset($result->ProfissionalSaude)) {
            $sqlInsert3 = "UPDATE public.profissionais
                       SET cpf='{$result->ProfissionalSaude->CPF->numeroCPF}'
                     WHERE cns = '{$row->cns}';";
            $conCnes->exec($sqlInsert3);
        }
    }
    //print("<pre>".print_r($result,true)."</pre>");

}

?>
