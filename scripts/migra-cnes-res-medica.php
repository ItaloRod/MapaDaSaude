<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
$conSagu = new PDO("pgsql:host=10.17.0.36;dbname=sagu", "postgres", "postgres");
if (!$conSagu) {
    echo 'não conectou';
}



$conMap = new PDO("pgsql:host=10.17.0.209;port=5470;dbname=mapas", "mapas", "mapas");
$conMap->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$conMap) {
    echo 'não conectou' . PHP_EOL;
} else {
    echo 'conectou mapas' . PHP_EOL;
}


$schemaCnes = 'cnes12_2019';

$dataHora = date('Y-m-d H:i:s');

$idAgentTmp = null;
$sql = "SELECT DISTINCT A.personid, B.email FROM $schemaCnes.elastic_sagu_cnes_egressos_residenciamedica A
        INNER JOIN public.basperson B ON A.personid = B.personid";
$query1 = $conSagu->query($sql);
while ($rowProfissional = $query1->fetch(PDO::FETCH_OBJ)) {

    $email = strtolower($rowProfissional->email);

    // verificando se o residente já está cadastrado
    $sqlUsr = "SELECT * FROM public.usr WHERE email = '" . $email . "' LIMIT 1";
    $queryUsr = $conMap->query($sqlUsr);
    $resultUsr = $queryUsr->fetch();

    if ( isset($resultUsr['email']) ) {
        $idUsr = $resultUsr['id'];
    } else {
        $email = strtolower($rowProfissional->email);
        $sqlInsert = "INSERT INTO public.usr (auth_provider, auth_uid, email, status, last_login_timestamp, create_timestamp) 
                        VALUES (0, 'fake-5dd2cecc55dc5', '" . $email . "', '1', '2019-11-18 17:03:08', '2019-11-18 17:03:08')";
        $conMap->exec($sqlInsert);
        $idUsr = $conMap->lastInsertId();

        $sqlInsert4 = "INSERT INTO public.entity_revision (id, user_id, object_id, object_type, create_timestamp, action, message) 
                    VALUES ('" . $idUsr . "', '" . $idUsr . "', '" . 5200 . "', 'MapasCulturais\Entities\Agent', '" . $dataHora . "', 'created', 'Registro criado.')";
        $conMap->exec($sqlInsert4);
    }


    $indiceAgente = 0;
    $sql = "
        SELECT DISTINCT pessoa,
            B.email, 
            a.personid, 
            a.estabelecimento_codigocnes, 
            a.estabelecimento_nomefantasia, 
            a.estabelecimento_municipio, 
            a.estabelecimento_uf, 
            a.estabelecimento_geo, 
            a.estabelecimento_pertencesus, 
            a.estabelecimento_tipounidade, 
            a.vinculo_descricao, 
            a.vinculo_tipo, 
            a.vinculo_subtipo, 
            a.cbo_cod, 
            a.cbo_descricao, 
            split_part(a.estabelecimento_geo::text, ','::text, 1) AS latitude, 
            split_part(a.estabelecimento_geo::text, ','::text, 2) AS longitude, 
            a.estabelecimento_logradouro, 
            a.estabelecimento_numero, 
            a.estabelecimento_bairro, 
            a.estabelecimento_complemento, 
            a.estabelecimento_cep, 
            a.estabelecimento_diretor_cpf, 
            a.estabelecimento_diretor_nome, 
            a.estabelecimento_telefone
           FROM cnes12_2019.elastic_sagu_cnes_egressos_residenciamedica a
           INNER JOIN public.basperson B ON A.personid = B.personid  WHERE A.personid = $rowProfissional->personid
    ";
    $query2 = $conSagu->query($sql);
    while ($row = $query2->fetch(PDO::FETCH_OBJ)) {

        if ($row->longitude != null) {


            $nomePessoa = str_replace("'", "", strtoupper($row->pessoa));
            $descricao = 'Egresso Residência Médica - Escola de Saúde Pública do Ceará | ';
            $descricao .= 'Instituição de trabalho: ' . $row->estabelecimento_nomefantasia;
            $location = '(' . $row->longitude . ', ' . $row->latitude . ')';



            // verificando se existe o agente e é o agente "pai"
            $sqlAgentPai = "SELECT * FROM public.agent WHERE user_id = '" . $idUsr . "' AND location  ~= point " . $location . " LIMIT 1";
            $queryAgentPai = $conMap->query($sqlAgentPai);
            $resultAgentPai = $queryAgentPai->fetch();
            $agentPai = $resultAgentPai['id'];

            if (isset($agentPai)) {

                /*$sqlInsert2 = "INSERT INTO public.agent (user_id, type, name, location, _geo_location, create_timestamp, status, is_verified, public_location, update_timestamp, short_description, parent_id)
                    VALUES ('" . $idUsr . "', 1, '" . $nomePessoa . "', '" . $location . "', '0101000020E6100000FCFFFFAB214C43C0547B8C58D9800EC0', '2019-11-18 17:03:08', '1', 'FALSE', 'TRUE', '2019-11-18 17:47:06', '" . $descricao . "', '" . $agentPai . "')";
                $conMap->exec($sqlInsert2);
                $idAgent = $conMap->lastInsertId();
                */
                $idAgent = $agentPai;

            } else {

                $sqlInsert2 = "INSERT INTO public.agent (user_id, type, name, location, _geo_location, create_timestamp, status, is_verified, public_location, update_timestamp, short_description) 
                    VALUES ('" . $idUsr . "', 1, '" . $nomePessoa . "', '" . $location . "', '0101000020E6100000FCFFFFAB214C43C0547B8C58D9800EC0', '2019-11-18 17:03:08', '1', 'FALSE', 'TRUE', '2019-11-18 17:47:06', '" . $descricao . "')";
                $conMap->exec($sqlInsert2);
                $idAgent = $conMap->lastInsertId();
                $idAgentTmp = $idAgent;

                $sqlInsert3 = "UPDATE public.usr SET profile_id =  " . $idAgent . " WHERE id = " . $idUsr;
                $conMap->exec($sqlInsert3);

                $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
                $stmt->execute();
                $row1 = $stmt->fetch();
                $sqlInsertMeta2 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_CEP', '" . $row->estabelecimento_cep . "'  )";
                $conMap->exec($sqlInsertMeta2);


                $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
                $stmt->execute();
                $row1 = $stmt->fetch();
                $sqlInsertMeta3 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_Nome_Logradouro', '" . $row->estabelecimento_logradouro . "'  )";
                $conMap->exec($sqlInsertMeta3);


                $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
                $stmt->execute();
                $row1 = $stmt->fetch();
                $sqlInsertMeta4 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_Num', '" . $row->estabelecimento_numero . "' )";
                $conMap->exec($sqlInsertMeta4);


                $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
                $stmt->execute();
                $row1 = $stmt->fetch();
                $sqlInsertMeta5 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_Bairro', '" . $row->estabelecimento_bairro . "' )";
                $conMap->exec($sqlInsertMeta5);


                $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
                $stmt->execute();
                $row1 = $stmt->fetch();
                $sqlInsertMeta6 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_Municipio', '" . $row->estabelecimento_municipio . "' )";
                $conMap->exec($sqlInsertMeta6);

                $stmt = $conMap->prepare("SELECT A.id FROM public.agent_meta A ORDER BY A.id DESC LIMIT 1");
                $stmt->execute();
                $row1 = $stmt->fetch();
                $sqlInsertMeta7 = "INSERT INTO public.agent_meta (object_id, key, value) VALUES ('" . $idAgent . "', 'En_Estado', '" . $row->estabelecimento_uf . "' )";
                $conMap->exec($sqlInsertMeta7);


                $sql12 = "SELECT id  FROM public.space WHERE name = '" . $row->estabelecimento_nomefantasia . "'";
                $query12 = $conMap->query($sql12);
                while ($rowTerm = $query12->fetch(PDO::FETCH_OBJ)) {
                    $sqlInsert6 = "INSERT INTO public.agent_relation (agent_id, object_type, object_id, type, has_control, create_timestamp, status)
                                VALUES ('" . $idAgent . "', 'MapasCulturais\Entities\Space', '" . $rowTerm->id . "', '" . strtoupper($row->cbo_descricao) . "', 'FALSE', '" . $dataHora . "', '1')";
                    $conMap->exec($sqlInsert6);
                }

                // migra selo egressos
                $sqlInsertSeal = "INSERT INTO public.seal_relation
                    (seal_id, object_id, create_timestamp, status, object_type, agent_id, owner_id, validate_date, renovation_request)
                    VALUES ('2', '" . $idAgent . "', '" . $dataHora . "' , '1' , 'MapasCulturais\Entities\Agent' , '1' ,'" . $idAgent . "' ,
                    '2029-12-08 00:00:00' , true)";
                $conMap->exec($sqlInsertSeal);

            }

            /*

            $sql11 = "SELECT DISTINCT cbo_descricao FROM $schemaCnes.elastic_sagu_cnes_egressos_residenciamedica A WHERE cbo_descricao <> '' AND A.personid = " . $row->personid . " AND A.cbo_descricao = '" . $row->cbo_descricao . "'";
            $query11 = $conSagu->query($sql11);
            while ($rowTerm = $query11->fetch(PDO::FETCH_OBJ)) {
                $sqlTerm = "SELECT * FROM public.term WHERE term = '" . $rowTerm->cbo_descricao . "'";
                $resultTerm = $conMap->prepare($sqlTerm);
                $resultTerm->execute();
                $termo = $resultTerm->fetch(PDO::FETCH_OBJ);

                if (isset($termo)) {
                    $sqlInsert6 = "INSERT INTO public.term_relation (term_id, object_type, object_id)  VALUES ('" . $termo->id . "', 'MapasCulturais\Entities\Agent', '" . $idAgent . "')";
                    $conMap->exec($sqlInsert6);
                }

            }*/

            $indiceAgente++;
        }
    }

}















