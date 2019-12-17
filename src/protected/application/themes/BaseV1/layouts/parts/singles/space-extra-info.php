<?php if ( $this->isEditable() || $entity->longDescription ): ?>
    <h3><?php \MapasCulturais\i::_e("Descrição");?></h3>
    <span class="descricao js-editable" data-edit="longDescription" data-original-title="<?php $this->dict('entities: Description of the space') ?>" data-emptytext="<?php $this->dict('entities: Description of the space') ?>" ><?php echo $this->isEditable() ? $entity->longDescription : nl2br($entity->longDescription); ?></span>
<?php endif; ?>

<?php if ( $this->isEditable() || $entity->criterios ): ?>
    <h3><?php $this->dict('entities: Usage criteria of the space') ?></h3>
    <div class="descricao js-editable" data-edit="criterios" data-original-title="<?php $this->dict('entities: Usage criteria of the space') ?>" data-emptytext="<?php $this->dict('entities: Usage criteria of the space') ?>" data-placeholder="<?php $this->dict('entities: Usage criteria of the space') ?>" data-showButtons="bottom" data-placement="bottom"><?php echo $entity->criterios; ?></div>
<?php endif; 
$handle = curl_init();
 
$url = "https://indicadores.integrasus.saude.ce.gov.br/api/media-permanencia-geral";
curl_setopt($handle, CURLOPT_URL, $url);
// Set the result output to be a string.
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
 
$output = curl_exec($handle);
 
curl_close($handle);
 
var_dump($output);
$arr = json_decode($output, true);
foreach ($arr['content'] as $element) {
    //var_dump($element);
    if($element['hospital'] == 'HGF' && $element['mes'] == '12' && $element['ano'] == '2019')
    {
        var_dump($element);
    }
}
?>
<script>
$(document).ready(function () {
    // $.ajax({
    //     type: "get",
    //     url: "http://indicadores.integrasus.saude.ce.gov.br/api/media-permanencia-geral/HGCC12018",
    //     headers: {
    //         // 'Authorization':'Basic xxxxxxxxxxxxx',
    //         // 'X-CSRF-TOKEN':'xxxxxxxxxxxxxxxxxxxx',
    //         'Content-Type':'application/json'
    //     },
    //     dataType: "json",
    //     success: function (response) {
    //         console.log('hgcc');
    //         console.log(response);
    //     }
    // });
    // $.getJSON("http://indicadores.integrasus.saude.ce.gov.br/api/media-permanencia-geral/HGCC12018",
    //     function (textStatus, jqXHR) {
    //         console.log(textStatus);
    //     }
    // );
});
</script>