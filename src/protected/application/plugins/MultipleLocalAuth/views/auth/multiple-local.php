<?php

    function showStrategy($name, $config){
        if (!isset($config['strategies'])){
            return false;
        }

        if (!isset($config['strategies'][$name])){
            return false;
        }

        //Default Visible TRUE
        if (!isset($config['strategies'][$name]['visible'])){
            return true;
        } 

        return $config['strategies'][$name]['visible'] === true;
    }
?>
<br/>
<div style="padding-left: 5%;">
    <?php if($feedback_msg): ?>
    <div class="alert <?php echo $feedback_success ? 'success' : 'error'; ?>">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
        <?php echo htmlentities($feedback_msg); ?>
    </div>
    <?php endif; ?>

    <div class="box-registro col" style="width:30%;">
        <div id="multiple-login">
            <h5 class="textcenter"><?php \MapasCulturais\i::_e('Entrar', 'multipleLocal'); ?></h5>
            <form action="<?php echo $login_form_action; ?>" method="POST">
                <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
                <input type="text" name="email" value="<?php echo htmlentities($triedEmail); ?>" />
                <br/><br/>
                <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
                <input type="password" name="password" value="" />
                <br/><br/>
                <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Entrar', 'multipleLocal'); ?>" />
                <a id="multiple-login-recover" class="multiple-recover-link"><?php \MapasCulturais\i::_e('Esqueci minha senha', 'multipleLocal'); ?></a>
            </form>
        </div>
        
        <div id="multiple-recover" style="display:none;">
            <h5 class="textcenter"><?php \MapasCulturais\i::_e('Esqueci minha senha', 'multipleLocal'); ?></h5>
            <form action="<?php echo $recover_form_action; ?>" method="POST">
                <p><?php \MapasCulturais\i::_e('Para recuperar sua senha, informe o e-mail utilizado no cadastro.', 'multipleLocal'); ?></p>
                <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
                <input type="text" name="email" value="" />
                <br/><br/>
                <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Recuperar senha', 'multipleLocal'); ?>" />
                <a id="multiple-login-recover-cancel"  class="multiple-recover-link"><?php \MapasCulturais\i::_e('Cancelar', 'multipleLocal'); ?></a>
            </form>
        </div>
    </div>

    <div class="box-registro col" style="width:30%;">
        <h5 class="textcenter"><?php \MapasCulturais\i::_e('Registrar-se', 'multipleLocal'); ?></h5>
        <form action="<?php echo $register_form_action; ?>" method="<?php echo $register_form_method; ?>">
            <?php if (isset($config['register_form'])): ?>
            <?php 
                foreach($config['register_form']['fields'] as $fieldLabel=>$fieldName){
                    print \MapasCulturais\i::_e($fieldLabel, 'multipleLocal');
                    print "<input type='text' name='$fieldName' /> <br/><br/>";
                }
            ?>
            <?php else: ?>
                <?php \MapasCulturais\i::_e('Nome', 'multipleLocal'); ?>
                <input type="text" name="name" value="<?php echo htmlentities($triedName); ?>" />
                <br/><br/>
                <?php \MapasCulturais\i::_e('E-mail', 'multipleLocal'); ?>
                <input type="text" name="email" value="<?php echo htmlentities($triedEmail); ?>" />
                <br/><br/>
                <?php \MapasCulturais\i::_e('Senha', 'multipleLocal'); ?>
                <input type="password" name="password" value="" />
                <br/><br/>
                <?php \MapasCulturais\i::_e('Confirmar senha', 'multipleLocal'); ?>
                <input type="password" name="confirm_password" value="" />               
            <?php endif; ?>            
            <input type="submit" value="<?php \MapasCulturais\i::esc_attr_e('Registrar-se', 'multipleLocal'); ?>" />   
        </form>
    </div>
    
</div>
