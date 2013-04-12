<?php
/*
Plugin Name: Widget Facebook Like
Description: Adciona uma caixa de Likes do Facebook
Version: 1.0
Author: HackLab
*/

class WidgetFacebookLikeBox extends WP_Widget {
    function WidgetFacebookLikeBox() {
        $widget_ops = array('classname' => 'FacebookLikeBox', 'description' => 'Adciona uma caixa de likes de sua página no Facebook' );
        parent::WP_Widget('facebookLikeBox', 'Facebook LikeBox', $widget_ops);

    }
    
    function widget($args, $instance) {
        extract($args);
        $options = get_option('campanha_social_networks');
        if(!isset($options['facebook-page']) || !$options['facebook-page'])
            return;
        
        echo $before_widget;
        ?>
        <iframe src="//www.facebook.com/plugins/likebox.php?href=<?php echo urlencode($options['facebook-page']) ?>&amp;width=292&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;border_color=white&amp;stream=false&amp;header=false&amp;appId=" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:290px;" allowTransparency="true"></iframe>
        <?php
        echo $after_widget;
    }
 
    function update($new_instance, $old_instance) {
        return $old_instance;
    }
    
    function form($instance) {
        ?>
        Este Widget utiliza a configuração de Página do Facebook do menu <a href="<?php bloginfo('url') ?>/wp-admin/admin.php?page=campaign_social_networks">Redes Sociais</a> e para funcionar corretamente, você deve ter uma página no Facebook.
        <strong>Opções do widget</strong>
        <p>
        	<label for="<?php $this->get_field_id('fb-show-faces'); ?>">Exibir fotos</label>
        	<select name="<?php echo $this->get_field_name('fb-show-faces'); ?>" id="<?php echo $this->get_field_id('fb-show-faces'); ?>">
        		<option value="1" <?php echo ($instance['fb-show-faces'] == 1) ? "selected=1" : ""; ?>>Sim</option>
        		<option value="0" <?php echo ($instance['fb-show-faces'] == 1) ? "selected=1" : ""; ?>>Não</option>
        	</select>
        </p>
        <?php 
    }
 
}

function registerWidgetFacebookLikeBox() {
    register_widget("WidgetFacebookLikeBox");
}

add_action('widgets_init', 'registerWidgetFacebookLikeBox');
    
?>
