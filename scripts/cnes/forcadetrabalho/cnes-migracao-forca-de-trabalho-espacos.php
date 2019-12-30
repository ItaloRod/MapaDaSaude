<?php

ini_set('display_errors', true);


require_once "../../../src/protected/vendor/autoload.php";


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


$tipos = include_once '../../../src/protected/application/conf/space-types.php';

$schemaCnes = 'public';


// MIGRANDO tags de serviços das instituições
$sql = "SELECT DISTINCT B.descricao FROM public.estabelecimentos A 
INNER JOIN public.estabelecimentosservicos B ON A.cnes = B.cnes";
$query1 = $conCnes->query($sql);
while ($row = $query1->fetch(PDO::FETCH_OBJ)) {
    $sqlInsert5 = "INSERT INTO public.term (taxonomy, term, description) 
                    VALUES ('tag', '" . $row->descricao . "', '" . $row->descricao . "')";
    $conMap->exec($sqlInsert5);
}


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
        uf,
        tipo_unidade,
        cnes
    FROM public.estabelecimentos

";
$query1 = $conCnes->query($sql);
while ($row = $query1->fetch(PDO::FETCH_OBJ)) {


    //if ($row->longitude != null) {


        // buscando tipo de instituição
        foreach ($tipos['items']['Tipos']['items'] as $key => $value) {
            if ($value['name'] == $row->tipo_unidade) {
                $idTipo = $key;
            }
        }


        $location = '(' . $row->longitude . ', ' . $row->latitude . ')';

        if ($row->longitude != null) {
            $location = '(' . $row->longitude . ', ' . $row->latitude . ')';
        } else {
            $location = '(0, 0)';
        }


        $data = date('Y-m-d H:i:s');
        $idAgenteResponsavel = 492509; //mudar esse valor, pois é baseado no agente
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


        //Adicionando tipos de serviços a tags das instituições
        $sql11 = "SELECT DISTINCT B.descricao FROM public.estabelecimentos A 
                    INNER JOIN public.estabelecimentosservicos B ON A.cnes = B.cnes WHERE A.cnes = '{$row->cnes}' ";
        $query11 = $conCnes->query($sql11);
        while ($rowTerm = $query11->fetch(PDO::FETCH_OBJ)) {



            echo $rowTerm->descricao . PHP_EOL;


            $sqlTerm = "SELECT * FROM public.term WHERE term = '" . $rowTerm->descricao . "'";
            $resultTerm = $conMap->prepare($sqlTerm);
            $resultTerm->execute();
            $termo = $resultTerm->fetch(PDO::FETCH_OBJ);

            $sqlInsert6 = "INSERT INTO public.term_relation (term_id, object_type, object_id)  VALUES ('" . $termo->id . "', 'MapasCulturais\Entities\Space', '" . $idSpace . "')";
            $conMap->exec($sqlInsert6);
        }
    //}
}
