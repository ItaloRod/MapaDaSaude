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
    <script>
      var nameH = '<?php echo htmlentities($entity->name); ?>';
      <?php $this->enqueueScript('app', 'space', 'js/space.js'); ?>
    </script>

<link rel="stylesheet" type="text/css" href="https://daneden.github.io/animate.css/animate.min.css">
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
        <li><a href="#profsaude"><?php \MapasCulturais\i::_e("Profissionais de Saúde");?></a></li>
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
        <!-- #profissionais de saúde -->
        <div id="profsaude" class="aba-content">
            <?php $this->part('related-agents', ['entity' => $entity]); ?>
        </div>
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
            <li class="list-group-item"  title="Representa o tempo médio, em dias, que os pacientes permanecem internados.">
                <small><strong>Tempo médio de internamento: </strong>
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
                <small><strong>Atendimento Ambulatorial (anual): </strong>
                <label id="quantity_attendance_hospital_amb" 
                    class="badge_success"></label>
                </small>
            </li>
            <li class="list-group-item">
                <small><strong>Atendimento Emergência (anual):  </strong>
                <label id="quantity_attendance_hospital_eme" 
                    class="badge_success"></label>
                </small>
            </li>
        </ul>
        <div class="space"><br></div>
        <h3>Comparativo com outro mês e ano,</h3>
        <small class="text-danger" id="requiredMonthPermanence"><strong>Esses dois campos são obrigatórios</strong></small>      
        <select class="form-control" name="monthPermanence" id="monthPermanence">
            <option selected value='0'>-- Selecione o Mês --</option>
            <option value='1'>Janeiro</option>
            <option value='2'>Fevereiro</option>
            <option value='3'>Março</option>
            <option value='4'>Abril</option>
            <option value='5'>Maio</option>
            <option value='6'>Junho</option>
            <option value='7'>Julho</option>
            <option value='8'>Agosto</option>
            <option value='9'>Setembro</option>
            <option value='10'>Outubro</option>
            <option value='11'>Novembro</option>
            <option value='12'>Dezembro</option>
        </select>
        <small class="text-danger" id="requiredYearPermanence"><strong>Esse campo é obrigatório</strong></small>
        <select class="form-control" name="yearPermanence" id="yearPermanence">
            <option selected value='0'>-- Selecione o Ano --</option>
            <option value='2018'>2018</option>
            <option value='2019'>2019</option>
        </select>
        <button class="btn btn-success" id="btnComparativeIntegraSus">Consultar indicador</button>
        <div class="box-indicador animated bounceInUp" id="boxComparativeIntegrasus">
            <div class="box-body" id="bodyComparativeIntegrasus">
                <ul class="list-group">
                    <li class="list-group-item">
                        <small><strong>Permanencia Atualmente: </strong>
                        <label id="info_permanence_actual_select" class="badge_success"></label> dias 
                        </small>
                    </li>
                    <li class="list-group-item">
                        <small><strong>Taxa de ocupação dos leitos: </strong>
                        <label id="info_ocupation_select" class="badge_success"></label> 
                        </small>
                    </li>
                    <li class="list-group-item">
                        <small><strong>Taxa de mortalidade hospitalar: </strong>
                        <label id="info_hospital_mortality_select" class="badge_success"></label> 
                        </small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="box-indicador" id="iframeBoxIntegrasus">
    </div>
    <!-- Related Admin Agents BEGIN -->
    <?php $this->part('related-admin-agents.php', array('entity'=>$entity)); ?>
    <!-- Related Admin Agents END -->
    <!-- Related Agents BEGIN -->
    <?php //$this->part('related-agents', ['entity' => $entity]); ?>
    <!-- Related Agents END -->
    <?php $this->part('singles/space-children', ['entity' => $entity]); ?>
    <?php $this->part('downloads', ['entity' => $entity]); ?>
    <?php $this->part('link-list', ['entity' => $entity]); ?>
    <!-- History BEGIN -->
    <?php $this->part('history.php', array('entity' => $entity)); ?>
    <!-- History END -->

</div>