<?php
$action = preg_replace("#^(\w+/)#", "", $this->template);
$this->bodyProperties['ng-app'] = "entity.app";
$this->bodyProperties['ng-controller'] = "EntityController";

$this->addEntityToJs($entity);

if($this->isEditable()){
    $this->addEntityTypesToJs($entity);
    $this->addTaxonoyTermsToJs('area');

    $this->addTaxonoyTermsToJs('tag');
}

$this->includeMapAssets();

$this->includeAngularEntityAssets($entity);

$child_entity_request = isset($child_entity_request) ? $child_entity_request : null;

$this->entity = $entity;

?>
<style>
.form-control{
    display: block;
    width: 100%;
    height: calc(1.5em + .75rem + 2px);
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.badge_success {
		background: #79d279;
		border-radius: 3px;
		color: #fff;
		font-weight: 600;
		padding: 2px 6px;
		box-shadow: 0 2px 3px rgba(0,0,0,.2), inset 0 2px 5px rgba(225,225,225,.3);
		font-size: 13px;
		margin-left: 5px;
		position: relative;
		display: inline-block;
		top: -1px;
    }
.list-group{
    padding-left:0;
    margin-bottom:20px;
    margin: 0 !important;
}
.list-group-item{
    position:relative;
    display:block;
    padding:10px 15px;
    margin-bottom:-1px;
    background-color:#fff;
    border:1px solid #ddd
}
.box{position:relative;border-radius:3px;background:#ffffff;border-top:3px solid #d2d6de;margin-bottom:20px;width:100%;box-shadow:0 1px 1px rgba(0,0,0,0.1)}
</style>
<?php $this->applyTemplateHook('breadcrumb','begin'); ?>

<?php $this->part('singles/breadcrumb', ['entity' => $entity,'entity_panel' => 'spaces','home_title' => 'entities: My Spaces']); ?>

<?php $this->applyTemplateHook('breadcrumb','end'); ?>

<?php $this->part('editable-entity', ['entity' => $entity, 'action' => $action]);  ?>

<article class="main-content space">
    <?php $this->applyTemplateHook('main-content','begin'); ?>
    <header class="main-content-header">
        <?php $this->part('singles/header-image', ['entity' => $entity]); ?>

        <?php $this->part('singles/entity-status', ['entity' => $entity]); ?>

        <!--.header-image-->
        <div class="header-content">
            <?php $this->applyTemplateHook('header-content','begin'); ?>

            <?php $this->part('singles/avatar', ['entity' => $entity, 'default_image' => 'img/avatar--space.png']); ?>

            <?php $this->part('singles/type', ['entity' => $entity]) ?>

            <?php $this->part('entity-parent', ['entity' => $entity, 'child_entity_request' => $child_entity_request]) ?>

            <?php $this->part('singles/name', ['entity' => $entity]) ?>

            <?php $this->applyTemplateHook('header-content','end'); ?>
        </div>
        <!--.header-content-->
        <?php $this->applyTemplateHook('header-content','after'); ?>
    </header>
    <!--.main-content-header-->
    <?php $this->applyTemplateHook('header','after'); ?>

    <?php $this->applyTemplateHook('tabs','before'); ?>
    <ul class="abas clearfix clear">
        <?php $this->applyTemplateHook('tabs','begin'); ?>
        <li class="active"><a href="#sobre"><?php \MapasCulturais\i::_e("Sobre");?></a></li>
        <?php if(!($this->controller->action === 'create')):?>
        <li><a href="#permissao"><?php \MapasCulturais\i::_e("Responsáveis");?></a></li>
        <li><a href="#funcionarios"><?php \MapasCulturais\i::_e("Prof. Saúde");?></a></li>
        <?php endif;?>
        <?php $this->applyTemplateHook('tabs','end'); ?>
    </ul>
    <?php $this->applyTemplateHook('tabs','after'); ?>

    <div class="tabs-content">
        <?php $this->applyTemplateHook('tabs-content','begin'); ?>
        <div id="sobre" class="aba-content">
            <?php $this->applyTemplateHook('tab-about','begin'); ?>
            <div class="ficha-spcultura">
                <?php if($this->isEditable() && $entity->shortDescription && strlen($entity->shortDescription) > 400): ?>
                    <div class="alert warning"><?php \MapasCulturais\i::_e("O limite de caracteres da descrição curta foi diminuido para 400, mas seu texto atual possui");?> <?php echo strlen($entity->shortDescription) ?> <?php \MapasCulturais\i::_e("caracteres. Você deve alterar seu texto ou este será cortado ao salvar.");?></div>
                <?php endif; ?>

                <p>
                    <span class="js-editable required" data-edit="shortDescription" data-original-title="<?php \MapasCulturais\i::esc_attr_e("Descrição Curta");?>" data-emptytext="<?php \MapasCulturais\i::esc_attr_e("Insira uma descrição curta");?>" data-tpl='<textarea maxlength="400"></textarea>'><?php echo $this->isEditable() ? $entity->shortDescription : nl2br($entity->shortDescription); ?></span>
                </p>
                <?php $this->applyTemplateHook('tab-about-service','before'); ?>
                <?php $this->part('singles/space-servico', ['entity' => $entity]); ?>
                <?php $this->applyTemplateHook('tab-about-service','after'); ?>

                <?php $this->part('singles/location', ['entity' => $entity, 'has_private_location' => false]); ?>
            </div>

            <?php $this->applyTemplateHook('tab-about-extra-info','before'); ?>
            <?php $this->part('singles/space-extra-info', ['entity' => $entity]) ?>
            <?php $this->applyTemplateHook('tab-about-extra-info','after'); ?>

            <?php $this->part('video-gallery.php', ['entity' => $entity]); ?>

            <?php $this->part('gallery.php', ['entity' => $entity]); ?>

            <?php $this->applyTemplateHook('tab-about','end'); ?>
        </div>
        <!-- #sobre -->
        <!-- #permissao -->
        <?php $this->part('singles/permissions') ?>
        <!-- #permissao -->
 
        <?php $this->applyTemplateHook('tabs-content','end'); ?>
    </div>
    <!-- .tabs-content -->
    <?php $this->applyTemplateHook('tabs-content','after'); ?>

    <?php $this->part('owner', ['entity' => $entity, 'owner' => $entity->owner]) ?>

    <?php $this->applyTemplateHook('main-content','end'); ?>
</article>
<div class="sidebar-left sidebar space">
    <?php $this->part('related-seals.php', array('entity'=>$entity)); ?>

    <?php $this->part('singles/space-public', ['entity' => $entity]) ?>

    <?php $this->part('widget-areas', ['entity' => $entity]); ?>

    <?php $this->part('widget-tags', ['entity' => $entity]); ?>

    <?php $this->part('redes-sociais', ['entity' => $entity]); ?>
</div>
<div class="sidebar space sidebar-right">
    <?php if($this->controller->action == 'create'): ?>
        <div class="widget">
            <p class="alert info"><?php \MapasCulturais\i::_e("Para adicionar arquivos para download ou links, primeiro é preciso salvar");?> <?php $this->dict('entities: the space') ?>.<span class="close"></span></p>
        </div>
    <?php endif; ?>
    <div class="widget" id="infoIntegrasus">
        <h3>Informações <a href="#" style="color:#79d279"> <strong> Integrasus</strong> </a> </h3>
        <ul class="list-group">
            <li class="list-group-item">
                <small><strong>Permanencia Atualmente: </strong>
                    <label id="info_permanence_actual" class="badge_success"></label> dias 
                </small>
            </li>
            <li class="list-group-item">
                <small><strong>Taxa de ocupação dos leitos: </strong>
                    <label id="info_ocupation" class="badge_success"></label> 
                </small>
            </li>
            <li class="list-group-item">
                <small><strong>Taxa de mortalidade hospitalar: </strong>
                    <label id="info_hospital_mortality" class="badge_success"></label> 
                </small>
            </li>
            <li class="list-group-item">
                <small><strong>Atendimento Ambulatorial: </strong>
                    <label id="quantity_attendance_hospital_amb" 
                    class="badge_success"></label> (2019)
                </small>
            </li>
            <li class="list-group-item">
                <small><strong>Atendimento Emergência:  </strong>
                    <label id="quantity_attendance_hospital_eme" 
                    class="badge_success"></label> (2019) 
                </small>
            </li>
        </ul>
    </div>
    <!-- <select class="form-control" name="monthPermanence" id="monthPermanence">
        <option selected value='0'>-- Selecione o Mês --</option>
        <option value='1'>Janaury</option>
        <option value='2'>February</option>
        <option value='3'>March</option>
        <option value='4'>April</option>
        <option value='5'>May</option>
        <option value='6'>June</option>
        <option value='7'>July</option>
        <option value='8'>August</option>
        <option value='9'>September</option>
        <option value='10'>October</option>
        <option value='11'>November</option>
        <option value='12'>December</option>
    </select>
    <select class="form-control" name="monthPermanence" id="monthPermanence">
        <option selected value='0'>-- Selecione o Ano --</option>
        <option value='1'>Janaury</option>
        <option value='2'>February</option>
        <option value='3'>March</option>
        <option value='4'>April</option>
        <option value='5'>May</option>
        <option value='6'>June</option>
        <option value='7'>July</option>
        <option value='8'>August</option>
        <option value='9'>September</option>
        <option value='10'>October</option>
        <option value='11'>November</option>
        <option value='12'>December</option>
    </select>
    <button class="btn btn-success">Consultar</button> -->
    <div class="box">
       <h6 style="text-align: center;">Pacientes que tiveram tempo de permanência superior a 24h na emergência</h6>
    </div>
    <!-- Related Admin Agents BEGIN -->
        <?php $this->part('related-admin-agents.php', array('entity'=>$entity)); ?>
    <!-- Related Admin Agents END -->

    <!-- Related Agents BEGIN -->
    <?php $this->part('related-agents', ['entity' => $entity]); ?>
    <!-- Related Agents END -->

    <?php $this->part('singles/space-children', ['entity' => $entity]); ?>

    <?php $this->part('downloads', ['entity' => $entity]); ?>

    <?php $this->part('link-list', ['entity' => $entity]); ?>

    <!-- History BEGIN -->
        <?php $this->part('history.php', array('entity' => $entity)); ?>
    <!-- History END -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/pt-br.js"></script>
    <script>
$(document).ready(function () {
    //POR PADRÃO INICIA OCULTANDO A DIV DAS INFORMAÇÕES
    $("#infoIntegrasus").hide();
    //NOME DO HOSPITAL VINDO DO PHP
    var nameH = '<?php echo htmlentities($entity->name); ?>';
    //ARRAY COM HOSPITAIS VALIDOS
    var hospitalValidation = [
        'HGF HOSPITAL GERAL DE FORTALEZA',
        'HGCC HOSPITAL GERAL DR CESAR CALS',
        'HOSPITAL GERAL DR WALDEMAR ALCANTARA',
        'HOSPITAL REGIONAL DO SERTAO CENTRAL',
        'HOSPITAL SAO JOSE DE DOENCAS INFECCIOSAS',
        'HOSPITAL DE SAUDE MENTAL DE MESSEJANA',
        'HM HOSPITAL DE MESSEJANA DR CARLOS ALBERTO STUDART GOMES',
        'HOSPITAL REGIONAL NORTE'
    ]
    //VERIFICANDO QUAL A SIGLA PARA CONSULTA    
    var sigla = '';
    switch (nameH) {
        case 'HGF HOSPITAL GERAL DE FORTALEZA':
            sigla = 'HGF';

            break;
        case 'HGCC HOSPITAL GERAL DR CESAR CALS':
            sigla = 'HGCC';
            break;
        case 'HOSPITAL GERAL DR WALDEMAR ALCANTARA':
            sigla = 'HGWA';
            break;
        case 'HOSPITAL REGIONAL DO SERTAO CENTRAL':
            sigla = 'HRSC';
            break;
        case 'HOSPITAL SAO JOSE DE DOENCAS INFECCIOSAS':
            sigla = 'HSJ';
            break;
        case 'HOSPITAL DE SAUDE MENTAL DE MESSEJANA':
            sigla = 'HM';
            break;
        case 'HM HOSPITAL DE MESSEJANA DR CARLOS ALBERTO STUDART GOMES':
            sigla = 'HM';
            // expected output: "Mangoes and papayas are $2.79 a pound."
            break;
        case 'HOSPITAL REGIONAL NORTE':
            sigla = 'HRN';
            // expected output: "Mangoes and papayas are $2.79 a pound."
            break;
    }
    //SE NO ARRAY DE HOSPITAIS EXISTIR O MESMO NOME QUE VEM DO PHP, CHAMA AS FUNCOES
    if (hospitalValidation.indexOf(nameH) == 0 || hospitalValidation.indexOf(nameH) > 0) {
        $("#infoIntegrasus").show();
        permanenceActual(sigla)
        txoccupationbed(sigla)
        txHospitalMortality(sigla)
        quantity_attendance_hospital(sigla)
    }

});
//PERMANENCIA
function permanenceActual(sigla) {
    var dt2 = new Date();
    var current_month = (dt2.getMonth() + 1);
    var current_year = (dt2.getFullYear());
    $.ajax({
        type: "get",
        url: "https://indicadores.integrasus.saude.ce.gov.br/api/media-permanencia-geral",

        dataType: "json",
        success: function (response) {
            // console.log('permanencia');
            // console.log(response.content.hospital);
            //console.log(response.content[0]);
            var permanence = response.content;
            // $.getJSON("url", data,
            //     function (data, textStatus, jqXHR) {

            //     }
            // );
            $.each(permanence, function (indexInArray, permanence) {
                //console.log(permanence.hospital)
                if (permanence.hospital == sigla && permanence.mes == current_month && permanence.ano == current_year) {
                    console.log('Tempo de permanencia : ' + permanence.mediaPermanenciaGeral)
                    $("#info_permanence_actual").html(permanence.mediaPermanenciaGeral)
                    $("#info_permanence_actual").attr('title', current_month + '/' + current_year)
                }
            });
        }
    });
}
//TAXA DE OCUPAÇÃO DE LEITOS
function txoccupationbed(sigla) {
    var dt2 = new Date();
    var current_month = (dt2.getMonth() + 1);
    var current_year = (dt2.getFullYear());
    $.ajax({
        type: "get",
        url: "https://indicadores.integrasus.saude.ce.gov.br/api/taxa-ocupacao-leitos",

        dataType: "json",
        success: function (response) {
            console.log('Leitos');
            // console.log(response.content.hospital);
            //console.log(response.content[0]);
            var permanence = response.content;
            // $.getJSON("url", data,
            //     function (data, textStatus, jqXHR) {

            //     }
            // );
            // console.log(permanence[0].taxaOcupacaoLeitosMes)
            $.each(permanence, function (indexInArray, permanence) {
                //console.log(permanence.hospital)
                var mesAnterior = current_month - 1
                if (permanence.hospital == sigla && permanence.mes == mesAnterior && permanence.ano == current_year) {
                    console.log('Taxa de Ocupação dos Leitos: ' + permanence.taxaOcupacaoLeitosMes)
                    $("#info_ocupation").html(permanence.taxaOcupacaoLeitosMes.toFixed(2) + '%')
                    $("#info_ocupation").attr('title', mesAnterior + '/' + current_year)
                }
            });
        }
    });
}
//QUANTIDADE DE ATENDIMENTO
function quantity_attendance_hospital(sigla) {
    var dt2 = new Date();
    var current_month = (dt2.getMonth() + 1);
    var current_year = (dt2.getFullYear());
    $.ajax({
        type: "get",
        url: "https://indicadores.integrasus.saude.ce.gov.br/api/qtd-atendimento-hospital",
        dataType: "json",
        success: function (response) {
            //console.log('atendimentohospital');
            // console.log(response.content.hospital);
            //console.log(response.content[0]);
            var permanence = response.content;
            // $.getJSON("url", data,
            //     function (data, textStatus, jqXHR) {

            //     }
            // );
            console.log(permanence[0].taxaOcupacaoLeitosMes)
            $.each(permanence, function (indexInArray, permanence) {
                //console.log(permanence.hospital)
                if (permanence.hospital == sigla && permanence.tipoAtendimento == 'AMBULATORIO') {
                    //console.log('Taxa de mortalidadeHospitalar: ' + permanence.mortalidadeHospitalar)
                    $("#quantity_attendance_hospital_amb").html(permanence.qtd)
                    $("#quantity_attendance_hospital_amb").attr('title', "Tipo: " + permanence.tipoAtendimento)
                }
                if (permanence.hospital == sigla && permanence.tipoAtendimento == 'EMERGÊNCIA') {
                    //console.log('Taxa de mortalidadeHospitalar: ' + permanence.mortalidadeHospitalar)
                    $("#quantity_attendance_hospital_eme").html(permanence.qtd)
                    $("#quantity_attendance_hospital_eme").attr('title', "Tipo: " + permanence.tipoAtendimento)
                }
            });
        }
    });
}
//TAXA DE MORTALIDADE HOSPITALAR
function txHospitalMortality(sigla) {
    var dt2 = new Date();
    var current_month = (dt2.getMonth() + 1);
    var current_year = (dt2.getFullYear());
    $.ajax({
        type: "get",
        url: "https://indicadores.integrasus.saude.ce.gov.br/api/taxa-mortalidade",
        dataType: "json",
        success: function (response) {
            //            console.log('mortalidade');
            // console.log(response.content.hospital);
            //console.log(response.content[0]);
            var permanence = response.content;
            // $.getJSON("url", data,
            //     function (data, textStatus, jqXHR) {

            //     }
            // );
            // console.log(permanence[0].taxaOcupacaoLeitosMes)
            $.each(permanence, function (indexInArray, permanence) {
                //console.log(permanence.hospital)
                if (permanence.hospital == sigla && permanence.mes == current_month && permanence.ano == current_year) {
                    //console.log('Taxa de mortalidadeHospitalar: ' + permanence.mortalidadeHospitalar)
                    $("#info_hospital_mortality").html(permanence.mortalidadeHospitalar.toFixed(2) + '%')
                    $("#info_hospital_mortality").attr('title', current_month + '/' + current_year)
                }
            });
        }
    });
}

function SelectPermanence(current_month, current_year) {
    var dt2 = new Date();
    var current_month = (dt2.getMonth() + 1);
    var current_year = (dt2.getFullYear());
    $.ajax({
        type: "get",
        url: "https://indicadores.integrasus.saude.ce.gov.br/api/media-permanencia-geral",

        dataType: "json",
        success: function (response) {
            //console.log('hgcc');
            // console.log(response.content.hospital);
            //console.log(response.content[0]);
            var permanence = response.content;
            // $.getJSON("url", data,
            //     function (data, textStatus, jqXHR) {

            //     }
            // );
            $.each(permanence, function (indexInArray, permanence) {
                //console.log(permanence.hospital)
                if (permanence.hospital == "HGF" && permanence.mes == current_month && permanence.ano == current_year) {
                    console.log('Tempo de permanencia selecionado: ' + permanence.mediaPermanenciaGeral);
                }
            });
        }
    });
}
</script>
</div>
