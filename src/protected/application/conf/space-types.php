<?php
/**
 * See https://github.com/Respect/Validation to know how to write validations
 */
return array(
    'metadata' => array(
        'emailPublico' => array(
            'label' => \MapasCulturais\i::__('Email Público'),
            'validations' => array(
                'v::email()' => \MapasCulturais\i::__('O email público não é um email válido.')
            )
        ),

        'emailPrivado' => array(
            'label' => \MapasCulturais\i::__('Email Privado'),
            'validations' => array(
                'v::email()' => \MapasCulturais\i::__('O email privado não é um email válido.')
            ),
        	'private' => true
        ),

        'telefonePublico' => array(
            'label' => \MapasCulturais\i::__('Telefone Público'),
            'type' => 'string',
            'validations' => array(
                'v::allOf(v::regex("#^\(\d{2}\)[ ]?\d{4,5}-\d{4}$#"), v::brPhone())' => \MapasCulturais\i::__('Por favor, informe o telefone público no formato (xx) xxxx-xxxx.')
            )
        ),

        'telefone1' => array(
            'label' => \MapasCulturais\i::__('Telefone 1'),
            'type' => 'string',
            'validations' => array(
                'v::allOf(v::regex("#^\(\d{2}\)[ ]?\d{4,5}-\d{4}$#"), v::brPhone())' => \MapasCulturais\i::__('Por favor, informe o telefone 1 no formato (xx) xxxx-xxxx.')
            ),
        	'private' => true
        ),


        'telefone2' => array(
            'label' => \MapasCulturais\i::__('Telefone 2'),
            'type' => 'string',
            'validations' => array(
                'v::allOf(v::regex("#^\(\d{2}\)[ ]?\d{4,5}-\d{4}$#"), v::brPhone())' => \MapasCulturais\i::__('Por favor, informe o telefone 2 no formato (xx) xxxx-xxxx.')
            ),
        	'private' => true
        ),

        /*
        'virtual_fisico' => array(
            'label' => \MapasCulturais\i::__('Virtual ou físico'),
            'type' => 'select',
            'options' => array(
                '' => \MapasCulturais\i::__('Físico'),
                'virtual' => \MapasCulturais\i::__('Virtual')
            )
        ),
        */
        'acessibilidade' => array(
            'label' => \MapasCulturais\i::__('Acessibilidade'),
            'type' => 'select',
            'options' => array(
                '' => \MapasCulturais\i::__('Não Informado'),
                'Sim' => \MapasCulturais\i::__('Sim'),
                'Não' => \MapasCulturais\i::__('Não')
            )
        ),
        'acessibilidade_fisica' => array(
            'label' => \MapasCulturais\i::__('Acessibilidade física'),
            'type' => 'multiselect',
            'allowOther' => true,
            'allowOtherText' => \MapasCulturais\i::__('Outros'),
            'options' => array(
                \MapasCulturais\i::__('Banheiros adaptados'),
                \MapasCulturais\i::__('Rampa de acesso'),
                \MapasCulturais\i::__('Elevador'),
                \MapasCulturais\i::__('Sinalização tátil'),

                // vindos do sistema de museus.cultura.gov.br
                \MapasCulturais\i::__('Bebedouro adaptado'),
                \MapasCulturais\i::__('Cadeira de rodas para uso do visitante'),
                \MapasCulturais\i::__('Circuito de visitação adaptado'),
                \MapasCulturais\i::__('Corrimão nas escadas e rampas'),
                \MapasCulturais\i::__('Elevador adaptado'),
                \MapasCulturais\i::__('Rampa de acesso'),
                \MapasCulturais\i::__('Sanitário adaptado'),
                \MapasCulturais\i::__('Telefone público adaptado'),
                \MapasCulturais\i::__('Vaga de estacionamento exclusiva para deficientes'),
                \MapasCulturais\i::__('Vaga de estacionamento exclusiva para idosos')
            )
        ),
        'capacidade' => array(
            'label' => \MapasCulturais\i::__('Capacidade'),
            'validations' => array(
                "v::intVal()->positive()" => \MapasCulturais\i::__("A capacidade deve ser um número positivo.")
            )
        ),

        'endereco' => array(
            'label' => \MapasCulturais\i::__('Endereço'),
            'type' => 'text'
        ),


        'En_CEP' => [
            'label' => \MapasCulturais\i::__('CEP'),
        ],
        'En_Nome_Logradouro' => [
            'label' => \MapasCulturais\i::__('Logradouro'),
        ],
        'En_Num' => [
            'label' => \MapasCulturais\i::__('Número'),
        ],
        'En_Complemento' => [
            'label' => \MapasCulturais\i::__('Complemento'),
        ],
        'En_Bairro' => [
            'label' => \MapasCulturais\i::__('Bairro'),
        ],
        'En_Municipio' => [
            'label' => \MapasCulturais\i::__('Município'),
        ],
        'En_Estado' => [
            'label' => \MapasCulturais\i::__('Estado'),
            'type' => 'select',
            'options' => array(
                'AC'=>'Acre',
                'AL'=>'Alagoas',
                'AP'=>'Amapá',
                'AM'=>'Amazonas',
                'BA'=>'Bahia',
                'CE'=>'Ceará',
                'DF'=>'Distrito Federal',
                'ES'=>'Espírito Santo',
                'GO'=>'Goiás',
                'MA'=>'Maranhão',
                'MT'=>'Mato Grosso',
                'MS'=>'Mato Grosso do Sul',
                'MG'=>'Minas Gerais',
                'PA'=>'Pará',
                'PB'=>'Paraíba',
                'PR'=>'Paraná',
                'PE'=>'Pernambuco',
                'PI'=>'Piauí',
                'RJ'=>'Rio de Janeiro',
                'RN'=>'Rio Grande do Norte',
                'RS'=>'Rio Grande do Sul',
                'RO'=>'Rondônia',
                'RR'=>'Roraima',
                'SC'=>'Santa Catarina',
                'SP'=>'São Paulo',
                'SE'=>'Sergipe',
                'TO'=>'Tocantins',
            )
        ],

        'horario' => array(
            'label' => \MapasCulturais\i::__('Horário de funcionamento'),
            'type' => 'text'
        ),

        'criterios' => array(
            'label' => \MapasCulturais\i::__('Critérios de uso do espaço'),
            'type' => 'text'
        ),

        'site' => array(
            'label' => \MapasCulturais\i::__('Site'),
            'validations' => array(
                "v::url()" => \MapasCulturais\i::__("A url informada é inválida.")
            )
        ),
        'facebook' => array(
            'label' => \MapasCulturais\i::__('Facebook'),
            'validations' => array(
                "v::url('facebook.com')" => \MapasCulturais\i::__("A url informada é inválida.")
            )
        ),
        'twitter' => array(
            'label' => \MapasCulturais\i::__('Twitter'),
            'validations' => array(
                "v::url('twitter.com')" => \MapasCulturais\i::__("A url informada é inválida.")
            )
        ),
        'googleplus' => array(
            'label' => \MapasCulturais\i::__('Google+'),
            'validations' => array(
                "v::url('plus.google.com')" => \MapasCulturais\i::__("A url informada é inválida.")
            )
        ),
        'instagram' => array(
            'label' => \MapasCulturais\i::__('Instagram'),
            'validations' => array(
                "v::startsWith('@')" => \MapasCulturais\i::__("O usuário informado é inválido. Informe no formato @usuario e tente novamente")
            )
        )
    ),

    'items' => array(
        \MapasCulturais\i::__('Tipos') => array(
            'range' => array(1,2000),
            'items' => array(
                1 => array( 'name' => \MapasCulturais\i::__("CENTRAL DE ABASTECIMENTO" )),
                2 => array( 'name' => \MapasCulturais\i::__("CENTRAL DE GESTAO EM SAUDE" )),
                3 => array( 'name' => \MapasCulturais\i::__("CENTRAL DE NOTIFICACAO,CAPTACAO E DISTRIB DE ORGAOS ESTADUAL" )),
                4 => array( 'name' => \MapasCulturais\i::__("CENTRAL DE REGULACAO DO ACESSO" )),
                5 => array( 'name' => \MapasCulturais\i::__("CENTRAL DE REGULACAO MEDICA DAS URGENCIAS" )),
                6 => array( 'name' => \MapasCulturais\i::__("CENTRO DE APOIO A SAUDE DA FAMILIA" )),
                7 => array( 'name' => \MapasCulturais\i::__("CENTRO DE ATENCAO HEMOTERAPIA E OU HEMATOLOGICA" )),
                8 => array( 'name' => \MapasCulturais\i::__("CENTRO DE ATENCAO PSICOSSOCIAL" )),
                9 => array( 'name' => \MapasCulturais\i::__("CENTRO DE IMUNIZACAO" )),
                10 => array( 'name' => \MapasCulturais\i::__("CENTRO DE PARTO NORMAL - ISOLADO" )),
                11 => array( 'name' => \MapasCulturais\i::__("CENTRO DE SAUDE/UNIDADE BASICA" )),
                12 => array( 'name' => \MapasCulturais\i::__("CLINICA/CENTRO DE ESPECIALIDADE" )),
                13 => array( 'name' => \MapasCulturais\i::__("CONSULTORIO ISOLADO" )),
                14 => array( 'name' => \MapasCulturais\i::__("FARMACIA" )),
                15 => array( 'name' => \MapasCulturais\i::__("HOSPITAL/DIA - ISOLADO" )),
                16 => array( 'name' => \MapasCulturais\i::__("HOSPITAL ESPECIALIZADO" )),
                17 => array( 'name' => \MapasCulturais\i::__("HOSPITAL GERAL" )),
                18 => array( 'name' => \MapasCulturais\i::__("LABORATORIO CENTRAL DE SAUDE PUBLICA LACEN" )),
                19 => array( 'name' => \MapasCulturais\i::__("LABORATORIO DE SAUDE PUBLICA" )),
                20 => array( 'name' => \MapasCulturais\i::__("POLICLINICA" )),
                21 => array( 'name' => \MapasCulturais\i::__("POLO ACADEMIA DA SAUDE" )),
                22 => array( 'name' => \MapasCulturais\i::__("POSTO DE SAUDE" )),
                23 => array( 'name' => \MapasCulturais\i::__("PRONTO ATENDIMENTO" )),
                24 => array( 'name' => \MapasCulturais\i::__("PRONTO SOCORRO ESPECIALIZADO" )),
                25 => array( 'name' => \MapasCulturais\i::__("PRONTO SOCORRO GERAL" )),
                26 => array( 'name' => \MapasCulturais\i::__("SERVICO DE ATENCAO DOMICILIAR ISOLADO(HOME CARE)" )),
                27 => array( 'name' => \MapasCulturais\i::__("UNIDADE DE APOIO DIAGNOSE E TERAPIA (SADT ISOLADO)" )),
                28 => array( 'name' => \MapasCulturais\i::__("UNIDADE DE ATENCAO A SAUDE INDIGENA" )),
                29 => array( 'name' => \MapasCulturais\i::__("UNIDADE DE VIGILANCIA EM SAUDE" )),
                30 => array( 'name' => \MapasCulturais\i::__("UNIDADE MISTA" )),
                31 => array( 'name' => \MapasCulturais\i::__("UNIDADE MOVEL DE NIVEL PRE-HOSPITALAR NA AREA DE URGENCIA" )),
                32 => array( 'name' => \MapasCulturais\i::__("UNIDADE MOVEL TERRESTRE" )),
                33 => array( 'name' => \MapasCulturais\i::__("OUTROS" )),
            )
        )
    )
);
