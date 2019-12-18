<?php
$this->jsObject['spinner'] = $this->asset('img/spinner_192.gif', false);

$app = \MapasCulturais\App::i();
$em = $app->em;

?>
<style>

</style>
<section id="home-watermark">
<!-- <div>
    <h4 style="width:100px; position: absolute;"><img src="http://bids.org.bd/theme/bids/images/beta.png" alt=""></h4>
</div> -->
<div class="beta">
  	<p class="beta__content">beta</p>
  </div>
</section>

<?php $this->part('home-search'); ?>

<?php //$this->part('home-events'); ?>

<?php $this->part('home-agents'); ?>

<?php $this->part('home-spaces'); ?>

<?php //$this->part('home-projects'); ?>

<?php $this->part('home-opportunities'); ?>

<?php $this->part('home-developers'); ?>

<?php $this->part('home-nav'); ?>
