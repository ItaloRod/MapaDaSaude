<?php

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


$sql = "SELECT DISTINCT cbodescricao FROM public.profissionais A WHERE cbodescricao <> ''";
$query1 = $conCnes->query($sql);
while ($row = $query1->fetch(PDO::FETCH_OBJ)) {
    $sqlInsert5 = "INSERT INTO public.term (taxonomy, term, description) 
                    VALUES ('tag', '" . $row->cbodescricao . "', '" . $row->cbodescricao . "')";
    $conMap->exec($sqlInsert5);
}




$idAgentTmp = null;
$sql = "SELECT DISTINCT cns FROM public.profissionais A";
$query1 = $conCnes->query($sql);
while ($rowProfissional = $query1->fetch(PDO::FETCH_OBJ)) {

    $email = $rowProfissional->cns.'@esp.ce.gov.br';
    $cns = $rowProfissional->cns;
    $sqlInsert = "INSERT INTO public.usr (auth_provider, auth_uid, email, status, last_login_timestamp, create_timestamp) 
                        VALUES (0, 'fake-5dd2cecc55dc5', '" . $email . "', '1', '2019-11-18 17:03:08', '2019-11-18 17:03:08')";
    $conMap->exec($sqlInsert);
    $idUsr = $conMap->lastInsertId();


    $dataHora = date('Y-m-d H:i:s');
    $sqlInsert4 = "INSERT INTO public.entity_revision (id, user_id, object_id, object_type, create_timestamp, action, message) 
                    VALUES ('" . $idUsr . "', '" . $idUsr . "', '" . 5200 . "', 'MapasCulturais\Entities\Agent', '" . $dataHora . "', 'created', 'Registro criado.')";
    $conMap->exec($sqlInsert4);

    $indiceAgente = 0;
    $sql = "SELECT * FROM public.profissionais A INNER JOIN public.estabelecimentos B ON A.cnes = B.cnes WHERE A.cns = '{$cns}';";

    $query2 = $conCnes->query($sql);
    while ($row = $query2->fetch(PDO::FETCH_OBJ)) {

        $nomePessoa = str_replace("'", "", strtoupper($row->nome));
        $descricao = utf8_encode('Profissional de saúde');

        if ($row->longitude != null) {
            $location = '(' . $row->longitude . ', ' . $row->latitude . ')';
        } else {
            $location = '(0, 0)';
        }

        if ($indiceAgente == 0) {

            $sqlInsert2 = "INSERT INTO public.agent (user_id, type, name, location, _geo_location, create_timestamp, status, is_verified, public_location, update_timestamp, short_description) 
                VALUES ('" . $idUsr . "', 1, '" . $nomePessoa . "', '" . $location . "', '0101000020E6100000FCFFFFAB214C43C0547B8C58D9800EC0', '2019-11-18 17:03:08', '1', 'FALSE', 'TRUE', '2019-11-18 17:47:06', '" . $descricao . "')";

            $conMap->exec($sqlInsert2);
            $idAgent = $conMap->lastInsertId();
            $idAgentTmp = $idAgent;

            $sqlInsert3 = "UPDATE public.usr SET profile_id =  " . $idAgent . " WHERE id = " . $idUsr;
            $conMap->exec($sqlInsert3);

        } else {

            $sqlInsert2 = "INSERT INTO public.agent (user_id, type, name, location, _geo_location, create_timestamp, status, is_verified, public_location, update_timestamp, short_description, parent_id) 
                VALUES ('" . $idUsr . "', 1, '" . $nomePessoa . "', '" . $location . "', '0101000020E6100000FCFFFFAB214C43C0547B8C58D9800EC0', '2019-11-18 17:03:08', '1', 'FALSE', 'TRUE', '2019-11-18 17:47:06', '" . $descricao . "', '" . $idAgentTmp . "')";
            $conMap->exec($sqlInsert2);
            $idAgent = $conMap->lastInsertId();
        }

        $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
        $stmt->execute();
        $row1 = $stmt->fetch();
        $sqlInsertMeta2 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_CEP', '" . $row->cep . "'  )";
        $conMap->exec($sqlInsertMeta2);


        $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
        $stmt->execute();
        $row1 = $stmt->fetch();
        $sqlInsertMeta3 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_Nome_Logradouro', '" . $row->logradouro . "'  )";
        $conMap->exec($sqlInsertMeta3);


        $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
        $stmt->execute();
        $row1 = $stmt->fetch();
        $sqlInsertMeta4 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_Num', '" . $row->numero . "' )";
        $conMap->exec($sqlInsertMeta4);


        $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
        $stmt->execute();
        $row1 = $stmt->fetch();
        $sqlInsertMeta5 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_Bairro', '" . $row->bairro . "' )";
        $conMap->exec($sqlInsertMeta5);


        $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
        $stmt->execute();
        $row1 = $stmt->fetch();
        $sqlInsertMeta6 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_Municipio', '" . $row->municipio . "' )";
        $conMap->exec($sqlInsertMeta6);

        $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
        $stmt->execute();
        $row1 = $stmt->fetch();
        $sqlInsertMeta7 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_Estado', '" . $row->uf . "' )";
        $conMap->exec($sqlInsertMeta7);


        $sql11 = "SELECT DISTINCT cbodescricao FROM public.profissionais A WHERE cbodescricao <> '' AND A.cns = '" . $row->cns . "' AND A.cbodescricao = '" . $row->cbodescricao . "'";
        $query11 = $conCnes->query($sql11);
        while ($rowTerm = $query11->fetch(PDO::FETCH_OBJ)) {
            $sqlTerm = "SELECT * FROM public.term WHERE term = '" . $rowTerm->cbodescricao . "'";
            $resultTerm = $conMap->prepare($sqlTerm);
            $resultTerm->execute();
            $termo = $resultTerm->fetch(PDO::FETCH_OBJ);

            $sqlInsert6 = "INSERT INTO public.term_relation (term_id, object_type, object_id)  VALUES ('" . $termo->id . "', 'MapasCulturais\Entities\Agent', '" . $idAgent . "')";
            $conMap->exec($sqlInsert6);
        }

        $sql12 = "SELECT id FROM public.space WHERE name = '" . $row->estabelecimento . "'";
        $query12 = $conMap->query($sql12);
        while ($rowTerm = $query12->fetch(PDO::FETCH_OBJ)) {
            $sqlInsert6 = "INSERT INTO public.agent_relation (agent_id, object_type, object_id, type, has_control, create_timestamp, status)  
                            VALUES ('" . $idAgent . "', 'MapasCulturais\Entities\Space', '" . $rowTerm->id . "', '" . strtoupper($row->cbodescricao) . "', 'FALSE', '" . $dataHora . "', '1')";
            $conMap->exec($sqlInsert6);
        }


        $indiceAgente++;

    }




}



