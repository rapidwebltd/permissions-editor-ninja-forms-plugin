<?php
/*
Plugin Name: Permissions Editor for Ninja Forms 
Plugin URI: http://rapidweb.biz
Description: Description goes here
Version: 1.0.0
Author: Rapid Web Ltd
Author URI: http://rapidweb.biz
*/

function penf_get_caps() {
    return ['penf_dashboard' => 'dashboard',
            'penf_add' => 'add',
            'penf_submissions' => 'submissions',
            'penf_import' => 'import',
            'penf_settings' => 'settings',
            'penf_add_ons' => 'add ons',
            'penf_view_menu' => 'view menu'];
}

function ninja_forms_editor_build_menu()
{
    add_options_page( 'Permisions editor for ninja forms', 
                     'Permisions editor for ninja forms',
                      'manage_options', 
                      'penf_role_matrix', 'penf_role_matrix' );

}
add_action('admin_menu','ninja_forms_editor_build_menu');

function penf_role_matrix() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	require("penf_role_matrix.php");
	echo '</div>';
}


function penf_activation() {
    $role = get_role('administrator');
    foreach(penf_get_caps() as $penfCapabilityKey => $penfCapability) {
        $role->add_cap($penfCapabilityKey);
    }
}

register_activation_hook(__FILE__, 'penf_activation');






// To give Editors access to the ALL Forms menu
function ninja_forms_editor_permissions( $capabilities ) {
    $capabilities = "read";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'ninja_forms_editor_permissions' );
add_filter( 'ninja_forms_admin_all_forms_capabilities', 'ninja_forms_editor_permissions' );
// To give Editors access to ADD New Forms
function my_custom_change_ninja_forms_add_new_capabilities_filter( $capabilities ) {
    $capabilities = "read";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'my_custom_change_ninja_forms_add_new_capabilities_filter' );
add_filter( 'ninja_forms_admin_add_new_capabilities', 'my_custom_change_ninja_forms_add_new_capabilities_filter' );

function nf_subs_capabilities( $cap ) {
    return 'read';
}
add_filter( 'ninja_forms_admin_submissions_capabilities', 'nf_subs_capabilities' );
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'nf_subs_capabilities' );
add_filter( 'ninja_forms_admin_menu_capabilities', 'nf_subs_capabilities' );

// To give Editors access to the Inport/Export Options
function custom_ninja_forms_import_export_capabilities_filter( $capabilities ) {
    $capabilities = "read";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'custom_ninja_forms_import_export_capabilities_filter' );
add_filter( 'ninja_forms_admin_import_export_capabilities', 'custom_ninja_forms_import_export_capabilities_filter' );

// To give Editors access to the the Settings page
function custom_ninja_forms_settings_capabilities_filter( $capabilities ) {
    $capabilities = "read";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'custom_ninja_forms_settings_capabilities_filter' );
add_filter( 'ninja_forms_admin_settings_capabilities', 'custom_ninja_forms_settings_capabilities_filter' );
