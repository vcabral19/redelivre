<?php

add_action('admin_menu', function() {
    add_menu_page('Suas campanhas', 'Suas campanhas', 'read', 'campaigns', function() {
        require(TEMPLATEPATH . '/includes/campaigns.php');
    });
    
    add_menu_page('Nova campanha', 'Nova campanha', 'read', 'campaigns_new', function() {
        require(TEMPLATEPATH . '/includes/campaigns_new.php');
    });
});
