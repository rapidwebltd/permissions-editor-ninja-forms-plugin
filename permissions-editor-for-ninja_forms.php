<?php
/*
Plugin Name: Permissions Editor for Ninja Forms 
Description: This plugin enables you to select the Ninja Forms capabilities that you wish to enable different WordPress user roles to have access to.
Version: 1.2.1
Author: Rapid Web Ltd
Author URI: http://rapidweb.biz
Text Domain: permissions-editor-for-ninja-forms
Domain Path: /languages
*/

function penf_load_plugin_textdomain() {
    load_plugin_textdomain('permissions-editor-for-ninja-forms', false, basename(dirname(__FILE__)).'/languages/' );
}
add_action('plugins_loaded', 'penf_load_plugin_textdomain' );

function penf_get_caps() {
    $caps = ['penf_manage' => __('Manage', 'permissions-editor-for-ninja-forms'),
            'penf_submissions' => __('Submissions', 'permissions-editor-for-ninja-forms'),
            'penf_import' => __('Import / Export', 'permissions-editor-for-ninja-forms'),
            'penf_settings' => __('Settings', 'permissions-editor-for-ninja-forms'),
            'penf_view_menu' => __('View Menu', 'permissions-editor-for-ninja-forms')];

    if (is_plugin_active('ninja-forms-excel-export/ninja-forms-excel-export.php')) {
        $caps['penf_excelExportExtension'] = __('Excel Export Extension', 'permissions-editor-for-ninja-forms');
    }

    return $caps;
            
}

function penf_build_menu()
{
    add_options_page( __('Permissions Editor for Ninja Forms', 'permissions-editor-for-ninja-forms'), 
                      __('Permissions Editor for Ninja Forms', 'permissions-editor-for-ninja-forms'),
                      'manage_options', 
                      'penf_role_matrix', 'penf_role_matrix' );

}
add_action('admin_menu','penf_build_menu');

function penf_role_matrix() {
	if (!current_user_can('manage_options'))  {
		wp_die(__('You do not have sufficient permissions to access this page.', 'permissions-editor-for-ninja-forms'));
    }
    
	echo '<div class="wrap">';
	require("penf_role_matrix.php");
	echo '</div>';
}


function penf_activation() {

    if (!is_plugin_active('ninja-forms/ninja-forms.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            __('<p>The <strong>Permissions Editor for Ninja Forms</strong> plugin requires the Ninja Forms plugin. Please install and activate the Ninja Forms plugin and then try again.</p>', 'permissions-editor-for-ninja-forms'), 
            __('Ninja Forms is not installed!', 'permissions-editor-for-ninja-forms'), 
            ['response' => 200, 'back_link' => true]
        );
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
        <?php echo __('<p>The <strong>Permissions Editor for Ninja Forms</strong> plugin has been deactivated, because the Ninja Forms plugin is no longer active.</p>', 'permissions-editor-for-ninja-forms') ?>
    </div>
    <?php
}

function penf_update_capabilities() {

    global $wp_roles;

    if (!current_user_can('manage_options'))  {
        wp_die(__('You do not have sufficient permissions to perform this action.', 'permissions-editor-for-ninja-forms'));
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
        
        // Prevents fatal error if attempting to update capability on a role which has been deleted.
        if (!$role) {
            continue;
        }

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
add_filter( 'ninja_forms_admin_menu_capabilities', 'penf_viewSubmissions' );

// To give Editors access to the Import/Export Options
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


function penf_excelExportExtension( $capabilities ) {
    $capabilities = "penf_excelExportExtension";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_excel_export_capabilities', 'penf_excelExportExtension' );
add_filter( 'ninja_forms_admin_spreadsheet_capabilities', 'penf_excelExportExtension' );
