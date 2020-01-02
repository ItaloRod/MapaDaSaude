<?php
return array(
    1 => array(
       // 'slug' => \MapasCulturais\i::__('tag'),
    'slug' => 'tag',
        'entities' => array(
            'MapasCulturais\Entities\Space',
            'MapasCulturais\Entities\Agent',
            'MapasCulturais\Entities\Event',
            'MapasCulturais\Entities\Project',
            'MapasCulturais\Entities\Opportunity',
        )
    ),

    2 => array(
        //'slug' => \MapasCulturais\i::__('area'),
        'slug' => 'area',
        //'required' => \MapasCulturais\i::__("Você deve informar ao menos uma área de atuação"),
        'entities' => array(
            'MapasCulturais\Entities\Space',
            'MapasCulturais\Entities\Agent'
        ),
        'restricted_terms' => array(
            \MapasCulturais\i::__("AUTONOMO"),
            \MapasCulturais\i::__("BOLSISTA"),
            \MapasCulturais\i::__("CARGO COMISSIONADO"),
            \MapasCulturais\i::__("CELETISTA"),
            \MapasCulturais\i::__("CONTRATADO TEMPORARIO OU POR PRAZO/TEMPO DETERMINADO"),
            \MapasCulturais\i::__("CONTRATADO VERBALMENTE"),
            \MapasCulturais\i::__("CONTRATO POR PRAZO DETERMINADO"),
            \MapasCulturais\i::__("COOPERADO"),
            \MapasCulturais\i::__("EMPREGADO PUBLICO CELETISTA"),
            \MapasCulturais\i::__("EMPREGO PUBLICO"),
            \MapasCulturais\i::__("ESTAGIARIO"),
            \MapasCulturais\i::__("ESTATUTARIO"),
            \MapasCulturais\i::__("INTERMEDIADO POR EMPRESA PRIVADA"),
            \MapasCulturais\i::__("PESSOA FISICA"),
            \MapasCulturais\i::__("PESSOA JURIDICA"),
            \MapasCulturais\i::__("PROPRIETARIO"),
            \MapasCulturais\i::__("RESIDENTE"),
            \MapasCulturais\i::__("SEM INTERMEDIACAO(RPA)"),
            \MapasCulturais\i::__("SEM TIPO"),
            \MapasCulturais\i::__("SERVIDOR CEDIDO"),
            \MapasCulturais\i::__("VINCULO EMPREGATICIO"),
            \MapasCulturais\i::__("VOLUNTARIADO")
        )
    ),

    3 => array(
        'slug' => 'linguagem',
        'required' => \MapasCulturais\i::__("Você deve informar ao menos uma linguagem"),
        'entities' => array(
            'MapasCulturais\Entities\Event'
        ),

        'restricted_terms' => array(
        )
    )
);
