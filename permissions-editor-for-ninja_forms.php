<?php
/*
Plugin Name: Permissions Editor for Ninja Forms 
Description: This plugin enables you to select the Ninja Forms capabilities that you wish to enable different WordPress user roles to have access to.
Version: 1.0.0
Author: Rapid Web Ltd
Author URI: http://rapidweb.biz
*/

function penf_get_caps() {
    return ['penf_manage' => 'Manage',
            'penf_submissions' => 'Submissions',
            'penf_import' => 'Import / Export',
            'penf_settings' => 'Settings',
            'penf_view_menu' => 'View Menu'];
}

function ninja_forms_editor_build_menu()
{
    add_options_page( 'Permissions Editor for Ninja Forms', 
                     'Permissions Editor for Ninja Forms',
                      'manage_options', 
                      'penf_role_matrix', 'penf_role_matrix' );

}
add_action('admin_menu','ninja_forms_editor_build_menu');

function penf_role_matrix() {
	if (!current_user_can('manage_options'))  {
		wp_die('You do not have sufficient permissions to access this page.');
    }
    
	echo '<div class="wrap">';
	require("penf_role_matrix.php");
	echo '</div>';
}


function penf_activation() {

    if (!is_plugin_active('ninja-forms/ninja-forms.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('<p>The <strong>Permissions Editor for Ninja Forms</strong> plugin requires the Ninja Forms plugin. Please install and activate the Ninja Forms plugin and then try again.</p>', 'Ninja Forms is not installed!', ['response' => 200, 'back_link' => true]);
    }

    $role = get_role('administrator');
    foreach(penf_get_caps() as $penfCapabilityKey => $penfCapability) {
        $role->add_cap($penfCapabilityKey);
    }
}

register_activation_hook(__FILE__, 'penf_activation');

function penf_deactivated_admin_notice() {
    ?>
    <div class="notice notice-info is-dismissible">
        <p>The <strong>Permissions Editor for Ninja Forms</strong> plugin has been deactivated, because the Ninja Forms plugin is no longer active.</p>
    </div>
    <?php
}

function penf_capabilities_updated_admin_notice() {
    ?>
    <div class="notice notice-info is-dismissible">
        <p>Permissions have been updated.</p>
    </div>
    <?php
}

function penf_update_capabilities() {

    global $wp_roles;

    if (!current_user_can('manage_options'))  {
        wp_die('You do not have sufficient permissions to perform this action.');
    }

    foreach($wp_roles->roles as $roleKey => $role) {
        $role = get_role($roleKey);

        foreach(penf_get_caps() as $penfCapabilityKey => $penfCapability) {
            $role->remove_cap($penfCapabilityKey);
        }
    }

    foreach($_POST as $roleKey => $penfCapabilityKeys) {

        if ($roleKey=='action') {
            continue;
        }

        $role = get_role($roleKey);
        if(count($penfCapabilityKeys) > 0 )
        {
            $role->add_cap("penf_view_menu");
        }
        foreach($penfCapabilityKeys as $penfCapabilityKey) {
            $role->add_cap($penfCapabilityKey);
        } 
    }

    wp_redirect(admin_url('options-general.php?page=penf_role_matrix&updated'));
}

add_action('admin_post_penf_update_capabilities', 'penf_update_capabilities');

function penf_admin_init() {

    if (!is_plugin_active('ninja-forms/ninja-forms.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
        add_action('admin_notices', 'penf_deactivated_admin_notice');
        return;
    }

    if (!current_user_can('penf_manage')) {
        remove_submenu_page('ninja-forms', 'ninja-forms');
    }
}

add_action('admin_init', 'penf_admin_init');



// To give Editors access to the ALL Forms menu
function ninja_forms_editor_permissions( $capabilities ) {
    $capabilities = "read";
    return $capabilities;
}
function penf_viewMenu($capabilities) {
    $capabilities = "penf_view_menu";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'penf_viewMenu' );

function  penf_manage($capabilities){
    $capabilities = "penf_manage";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_all_forms_capabilities', 'penf_manage' );
add_filter( 'ninja_forms_admin_extend_capabilities', 'penf_manage' );
add_filter( 'ninja_forms_admin_add_new_capabilities', 'penf_manage' );


function penf_viewSubmissions( $cap ) {
    return 'penf_submissions';
}
add_filter( 'ninja_forms_admin_submissions_capabilities', 'penf_viewSubmissions' );
//add_filter( 'ninja_forms_admin_menu_capabilities', 'nf_subs_capabilities' );

// To give Editors access to the Inport/Export Options
function penf_importExport( $capabilities ) {
    $capabilities = "penf_import";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_import_export_capabilities', 'penf_importExport' );

// To give Editors access to the the Settings page

function penf_editSettings( $capabilities ) {
    $capabilities = "penf_settings";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_settings_capabilities', 'penf_editSettings' );





