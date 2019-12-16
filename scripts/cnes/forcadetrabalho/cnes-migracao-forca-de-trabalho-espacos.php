<?php

ini_set('display_errors', true);


$conCnes = new PDO("pgsql:host=10.17.0.209;port=5470;dbname=cnes", "mapas", "mapas");
$conCnes->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$conCnes) {
    echo 'n�o conectou';
}


$conMap = new PDO("pgsql:host=10.17.0.209;port=5470;dbname=mapas", "mapas", "mapas");
$conMap->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$conMap) {
    echo 'n�o conectou';
}


//$tipos = include_once '../../../src/protected/application/conf/space-types.php';

$schemaCnes = 'public';


$sql = "
    SELECT DISTINCT 
        nome_fantasia,
        longitude, 
        latitude, 
        logradouro, 
        numero, 
        bairro,  
        cep,
        municipio, 
        uf    
    FROM public.estabelecimentos

";
$query1 = $conCnes->query($sql);
while ($row = $query1->fetch(PDO::FETCH_OBJ)) {


    //if ($row->longitude != null) {

        /*
        foreach ($tipos['items']['Tipos']['items'] as $key => $value) {
            if ($value['name'] == $row->estabelecimento_tipounidade) {
                $idTipo = $key;
            }
        }*/

        $idTipo = 33;


        $location = '(' . $row->longitude . ', ' . $row->latitude . ')';

        if ($row->longitude != null) {
            $location = '(' . $row->longitude . ', ' . $row->latitude . ')';
        } else {
            $location = '(0, 0)';
        }


        $data = date('Y-m-d H:i:s');
        $idAgenteResponsavel = 238388; //mudar esse valor, pois � baseado no agente
        $sqlInsert = "INSERT INTO public.space (location, _geo_location, name, short_description, long_description, create_timestamp, status, type, is_verified, public, agent_id) 
                        VALUES ('" . $location . "', '0101000020E610000000000008A63E43C090B78B3B9BCF0DC0', '" . $row->nome_fantasia . "', '" . $row->nome_fantasia . "', '" . $row->nome_fantasia . "', '" . $data . "', 1, '" . $idTipo ."', 'FALSE', 'FALSE', '" . $idAgenteResponsavel ."')";
        $conMap->exec($sqlInsert);
        $idSpace = $conMap->lastInsertId();


        $sqlInsertMeta2 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_CEP', '" . $row->cep . "'  )";
        $conMap->exec($sqlInsertMeta2);

        $sqlInsertMeta3 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_Nome_Logradouro', '" . $row->logradouro . "'  )";
        $conMap->exec($sqlInsertMeta3);


        $sqlInsertMeta4 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_Num', '" . $row->numero . "' )";
        $conMap->exec($sqlInsertMeta4);

        $sqlInsertMeta5 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_Bairro', '" . $row->bairro . "' )";
        $conMap->exec($sqlInsertMeta5);

        $sqlInsertMeta6 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_Municipio', '" . $row->municipio . "' )";
        $conMap->exec($sqlInsertMeta6);

        $sqlInsertMeta7 = "INSERT INTO public.space_meta (object_id, key, value) VALUES ('" . $idSpace . "', 'En_Estado', '" . $row->uf . "' )";
        $conMap->exec($sqlInsertMeta7);

    //}
}
