<?php

$conMap = new PDO("pgsql:host=10.17.0.209;port=5470;dbname=mapas", "mapas", "mapas");
$conMap->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!$conMap) {
    echo 'não conectou' . PHP_EOL;
} else {
    echo 'conectou mapas' . PHP_EOL;
}

$agent = "SELECT id FROM agent";
$query1 = $conMap->query($agent);
$total = 0;
while ($row = $query1->fetch(PDO::FETCH_OBJ)) {
    $dataHora = date('Y-m-d H:i:s');
    $sqlInsert = "INSERT INTO public.seal_relation 
                    (seal_id, object_id, create_timestamp, status, object_type, agent_id, owner_id, validate_date, renovation_request) 
                    VALUES ('4', '" . $row->id . "', '" . $dataHora . "' , '1' , 'MapasCulturais\Entities\Agent' , '1' ,'" . $row->id . "' ,
                    '2029-12-08 00:00:00' , true)";
    $conMap->exec($sqlInsert);
    $total++;
}

// $result = $query1->fetch(PDO::FETCH_ASSOC);
// echo "total: " . count($query1);
// foreach($result as $key => $value) {
//     echo $key . "-" . $value;
// }
echo "Total : " . $total++;
die();

?>