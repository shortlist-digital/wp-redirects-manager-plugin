<?php
/**
* @wordpress-plugin
* Plugin Name: WP Redirects Manager Plugin
* Plugin URI: http://github.com/shortlist-digital/wp-redirects-manager-plugin
* Description: A plugin to add the role to enable editors to add redirect rules
* Version: 1.0.0
* Author: Shortlist Studio
* Author URI: http://shortlist.studio
* License: MIT
*/

require_once __DIR__ . '/../../../../vendor/autoload.php';

class WpRedirectsManagerPlugin
{
    public function __construct()
    {
        add_filter( 'register_post_type_args', array($this, 'custom_post_type_args'), 10, 2 );
        add_action('admin_init', array($this, 'redirects_manager_role'));
    }

    function custom_post_type_args( $args, $post_type ) {
        if ( $post_type == "redirect_rule" ) {
          $args['capabilities'] = array(
              'publish_posts' => 'publish_redirect_rules',
              'edit_posts' => 'edit_redirect_rules',
              'edit_others_posts' => 'edit_others_redirect_rules',
              'delete_posts' => 'delete_redirect_rules',
              'delete_private_posts' => 'delete_private_lsponsored_ongforms',
              'delete_others_posts' => 'delete_others_redirect_rules',
              'read_private_posts' => 'read_private_redirect_rules',
              'edit_post' => 'edit_redirect_rule',
              'delete_post' => 'delete_redirect_rule',
              'read_post' => 'read_redirect_rule'
          );
        }
        return $args;
      }

      function redirects_manager_role() {
        remove_role('redirects_manager');
        if (!get_role('redirects_manager')) {
          add_role('redirects_manager',
            'Redirects Manager',
            array(
              'read' => true,
              'edit_posts' => true,
              'delete_posts' => true,
              'publish_posts' => true,
              'upload_files' => true,
            )
          );
        }
        // Add the roles you'd like to administer the custom post types
        $roles = array('redirects_manager');
        // Loop through each role and assign capabilities
        foreach($roles as $the_role) {
          $role = get_role($the_role);
          $role->add_cap('read_redirect_rule');
          $role->add_cap('read_private_redirect_rules');
          $role->add_cap('edit_redirect_rule');
          $role->add_cap('edit_redirect_rules');
          $role->add_cap('edit_others_redirect_rules');
          $role->add_cap('edit_published_redirect_rules');
          $role->add_cap('publish_redirect_rules');
          $role->add_cap('delete_redirect_rule');
          $role->add_cap('delete_others_redirect_rules');
          $role->add_cap('delete_private_redirect_rules');
          $role->add_cap('delete_published_redirect_rules');
        }
        get_role($roles[0])->remove_cap('edit_posts');
      }
}
new WpRedirectsManagerPlugin();
