<?php
namespace Saude;

use MapasCulturais\Themes\BaseV1;
use MapasCulturais\App;

class Theme extends BaseV1\Theme{

    protected static function _getTexts(){
        $self = App::i()->view;
        $url_search_agents = $self->searchAgentsUrl;
        $url_search_spaces = $self->searchSpacesUrl;
        $url_search_events = $self->searchEventsUrl;
        $url_search_projects = $self->searchProjectsUrl;

        return array(
            'site: of the region' => 'do estado de Ceará',
            'site: by the site owner' => 'pela Escola de saúde pública do Ceará',

            'home: welcome' => "O Mapa da Saúde é um software livre, gratuito e colaborativo implementado pela Escola de Saúde Pública (ESP-CE). No qual, abrange informações   de diferentes sistemas, serviços e recursos para melhor informar e integrar a sociedade e os governos sobre o quadro de saúde do Estado do Ceará, trazendo dados sobre os profissionais, os serviços e os sistema relacionados a escola. Para conferir mais e colaborar na gestão estadual, basta criar seu perfil.",
            /*'home: events' => "Você pode pesquisar eventos culturais da cidade nos campos de busca combinada. Como usuário cadastrado, você pode incluir seus eventos na plataforma e divulgá-los gratuitamente.",*/
            'home: agents' => "Você pode colaborar na gestão da saúde da cidade com suas próprias informações, preenchendo seu perfil de ator de mudança. Neste espaço, estão registrados profissionais, gestores e instituições; uma rede de atores envolvidos no cenário de saúde. Você pode cadastrar um ou mais informes, além de associar ao seu perfil eventos e espaços de com divulgação gratuita.",
            'home: spaces' => "Procure por espaços culturais incluídos na plataforma, acessando os campos de busca combinada que ajudam na precisão de sua pesquisa. Cadastre também os espaços onde desenvolve suas atividades artísticas e culturais na cidade.",
            'home: projects' => "Reúne os projetos ou agrupa os eventos de todos os tipos executados pela escola. Neste espaço, você encontra leis de fomento, mostras, convocatórias e editais criado pela Escola de Saúde Pública-CE, além de diversas iniciativas cadastradas pelos usuários da plataforma. Cadastre-se e divulgue seus projetos.",
            'home: opportunities' => "Faça a sua inscrição ou acesse o resultado de diversas convocatórias como editais, oficinas, prêmios e concursos. Você também pode criar o seu próprio formulário e divulgar uma oportunidade para outros atores de mudança.",
            'home: colabore' => "Colabore com o Mapa da Saúde",

            'home: abbreviation' => "ESP-CE",
            'home: home_devs' => 'Existem algumas maneiras de desenvolvedores interagirem com o Mapa de Saúde. A primeira é através da nossa  <a href="https://github.com/hacklabr/mapasculturais/blob/master/documentation/docs/mc_config_api.md" target="_blank">API</a>. 
            Com ela você pode acessar os dados públicos no nosso banco de dados e utilizá-los para desenvolver aplicações externas. Além disso, o Mapa da Saúde é construído a partir do software livre, recebendo contribuição para o seu desenvolvimento através do <a href="https://github.com/hacklabr/mapasculturais/" target="_blank">GitHub</a>.',

            'search: verified results' => 'Resultados da ESP-CE',
            'search: verified' => "ESP-CE"
        );
    }

    static function getThemeFolder() {
        return __DIR__;
    }

    function _init() {
        parent::_init();
        $app = App::i();

        $app->hook('view.render(<<*>>):before', function() use($app) {
            $this->jsObject['assets']['logo-prefeitura'] = $this->asset('img/logo-prefeitura.png', false);
        });

        $theme = $this;

        $app->hook('GET(project.virada-cultural-2015.docx)', function() use($app, $theme) {
            $this->requireAuthentication();

            $project_id = 632;

            $entity = $app->repo('Project')->find($project_id);

            $entity->checkPermission('@control');

            $ids = $entity->getChildrenIds();

            $ids[] = $entity->id;


            $in = implode(',', array_map(function ($e){ return '@Project:' . $e; }, $ids));

            $events = $app->controller('Event')->apiQuery([
                '@select' => 'id,name,classificacaoEtaria,singleUrl,occurrences,terms,status,owner.id,owner.name,owner.singleUrl,project.id,project.name',
                'project' => 'IN(' . $in . ')',
                '@permissions' => 'view',
                '@files' => '(avatar.avatarSmall):url'
            ]);

            $parceiros = [
                'Centro Acadêmico XI de Agosto' => [],
                'Cultura Inglesa Festival' => [],
                'Secretaria da Cultura do Estado de São Paulo' => [],
                'Coletivo Teatral Commune' => [],
                'Sesc São Paulo' => [],
                'Família 100 Conflito' => [],
                'Movimento Cultural Penha' => [],
                'RenattodSousa Foto Galeria' => [],
                'Kaapora Cultural' => [],
                'Garageclub' => [],
                'Associação Viva e Deixe Viver Contadores de História' => [],
                'Garrafas Bar' => [],
                'Cia. Teatral Bola de Neve' => [],
                'Memorial da Inclusão: Os Caminhos da Pessoa com Deficiência' => [],
                'Caixa Cultural São Paulo' => [],
                'Dia da Música' => [],
                'Serviço Funerário do Município de São Paulo' => [],
                'Espaçoarte eventos' => [],
                'UNAS' => [],
                'Centro de Tradições Nordestinas' => [],
                'Virada Coral' => [],
                'Consulado Geral de Portugal em São Paulo' => [],
                'Auditório Ibirapuera' => [],
                'MASP' => []
            ];

            $eventos_smc = [];


            foreach($events as $e){
                $e['owner']['name'] = trim(str_replace('  ', ' ', $e['owner']['name']));
                if(isset($parceiros[$e['owner']['name']])){
                    $parceiros[$e['owner']['name']][] = $e;
                }else{
                    $eventos_smc[] = $e;
                }
            }

            $enderecos = [];

            $group_events = function($events) use ($enderecos){
                $spaces = [];
                foreach($events as $event){
                    foreach($event['occurrences'] as $occ){
                        if(!isset($spaces[$occ->space->name])){
                            $spaces[$occ->space->name] = [$occ->space->endereco];

                        }
                        if($occ->rule->duration != 1440){
                            $data = $occ->startsOn->format('Y-m-d') . ' ' . $occ->startsAt->format('H:i');
                            $spaces[$occ->space->name][$data] = $event['name'];
                        }else{
                            if(!isset($spaces[$occ->space->name]['24h'])){
                                $spaces[$occ->space->name]['24h'] = [];
                            }

                            $spaces[$occ->space->name]['24h'][] = $event['name'];
                        }
                    }
                }

                foreach($spaces as $name => $evts){
                    ksort($spaces[$name]);
                }

                return $spaces;
            };

            $print_parceiro = function($eventos, $parceiro = false) use ($project_id, $group_events){
                $projetos = [];
                $eventos_parceiro = [];
                foreach($eventos as $evento){
                    if($evento['project']['id'] != $project_id){
                        if(!isset($projetos[$evento['project']['name']])){
                            $projetos[$evento['project']['name']] = [];
                        }

                        $projetos[$evento['project']['name']][] = $evento;
                    }else{
                        $eventos_parceiro[] = $evento;
                    }
                }

                $eventos_smc = $group_events($eventos_parceiro);
                $eventos_projeto = [];
                foreach($projetos as $name => $evts){
                    $eventos_projeto[$name] = $group_events($evts);
                }

                $print = function($eventos, $h = 'h1'){
                    ksort($eventos);
                    foreach($eventos as $palco => $evts){
                        echo "<{$h}>$palco</{$h}>";
                        $endereco = $evts[0];
                        unset($evts[0]);
                        if($endereco != $palco){
                            echo "<em>$endereco</em><br><br>";
                        }

                        if(isset($evts['24h'])){
                        }else{
                            $format = 'H:i';
                        }
                            $format = '\d\i\a d \à\s H:i';

                        foreach($evts as $hora => $nome){
                            if($hora == '24h'){
                                foreach($nome as $n){
                                    echo "  <b>$hora</b>   -   $n<br>";
                                }
                            }else{
                                $dt = new \DateTime($hora);
                                $hora = $dt->format($format);
                                echo "  <b>$hora</b>   -   $nome<br>";
                            }
                        }
                    }
                };


                if($parceiro){
                    $h1 = 'h2';
                    $h2 = 'h3';
                    $h3 = 'h4';
                }else{
                    $h1 = 'h1';
                    $h2 = 'h2';
                    $h3 = 'h3';
                }

                if($parceiro){
                    echo "<h1>{$parceiro}</h1>";
                }

                $print($eventos_smc, $h2);

                foreach($eventos_projeto as $projeto => $evts){
                    echo "<{$h1}>{$projeto}</{$h1}>";
                    $print($evts,$h3);
                }


            };


            $response = $app->response();
            $response->header("Content-Type", "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
            $response->header('Content-Disposition', 'attachment; filename=virada-cultural-2015.docx');

            ?>
<html>
    <body>
        <?php $print_parceiro($eventos_smc); ?>

        <?php foreach($parceiros as $parceiro => $eventos): if(!$eventos) continue; ?>
            <?php $print_parceiro($eventos, $parceiro); ?>
        <?php endforeach; ?>
    </body>
</html>
            <?php
        });
    }

}
