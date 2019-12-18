<?php

require_once "../src/protected/vendor/autoload.php";

ini_set('display_errors', true);
$conSagu = new PDO("pgsql:host=10.17.0.36;dbname=sagu", "postgres", "postgres");
if (!$conSagu) {
    echo 'não conectou';
}



$conMap = new PDO("pgsql:host=10.17.0.209;port=5470;dbname=mapas", "mapas", "mapas");
$conMap->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$conMap) {
    echo 'não conectou';
}


$tipos = include_once '../src/protected/application/conf/space-types.php';

$schemaCnes = 'cnes12_2019';


$sql = "
    SELECT DISTINCT 
    estabelecimento_nomefantasia, 
    longitude, 
    latitude, 
    estabelecimento_tipounidade, 
    estabelecimento_logradouro, 
    estabelecimento_numero, 
    estabelecimento_bairro,  
    estabelecimento_cep,
    a.estabelecimento_municipio, 
    a.estabelecimento_uf    
    FROM $schemaCnes.elastic_sagu_cnes_egressos A

";
$query1 = $conSagu->query($sql);
while ($row = $query1->fetch(PDO::FETCH_OBJ)) {


    if ($row->longitude != null) {

        foreach ($tipos['items']['Tipos']['items'] as $key => $value) {
            if ($value['name'] == $row->estabelecimento_tipounidade) {
                $idTipo = $key;
            }
        }


        $location = '(' . $row->longitude . ', ' . $row->latitude . ')';
        $data = date('Y-m-d H:i:s');
        $idAgenteResponsavel = 217895; //mudar esse valor, pois é baseado no agente
        $sqlInsert = "INSERT INTO public.space (location, _geo_location, name, short_description, long_description, create_timestamp, status, type, is_verified, public, agent_id) 
                        VALUES ('" . $location . "', '0101000020E610000000000008A63E43C090B78B3B9BCF0DC0', '" . $row->estabelecimento_nomefantasia . "', '" . $row->estabelecimento_nomefantasia . "', '" . $row->estabelecimento_nomefantasia . "', '" . $data . "', 1, '" . $idTipo ."', 'FALSE', 'FALSE', '" . $idAgenteResponsavel ."')";
        $conMap->exec($sqlInsert);
        $idSpace = $conMap->lastInsertId();


        $sqlInsertMeta2 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_CEP', '" . $row->estabelecimento_cep . "'  )";
        $conMap->exec($sqlInsertMeta2);

        $sqlInsertMeta3 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_Nome_Logradouro', '" . $row->estabelecimento_logradouro . "'  )";
        $conMap->exec($sqlInsertMeta3);


        $sqlInsertMeta4 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_Num', '" . $row->estabelecimento_numero . "' )";
        $conMap->exec($sqlInsertMeta4);

        $sqlInsertMeta5 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_Bairro', '" . $row->estabelecimento_bairro . "' )";
        $conMap->exec($sqlInsertMeta5);

        $sqlInsertMeta6 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_Municipio', '" . $row->estabelecimento_municipio . "' )";
        $conMap->exec($sqlInsertMeta6);

        $sqlInsertMeta7 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_Estado', '" . $row->estabelecimento_uf . "' )";
        $conMap->exec($sqlInsertMeta7);

    }
}
