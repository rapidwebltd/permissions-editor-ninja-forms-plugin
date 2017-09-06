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
            'penf_submissions' => 'submissions',
            'penf_import' => 'import',
            'penf_settings' => 'settings',
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
function penf_viewMenu($capabilities) {
    $capabilities = "penf_view_menu";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'penf_viewMenu' );

function  penf_viewDashboard($capabilities){
    $capabilities = "penf_dashboard";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_all_forms_capabilities', 'penf_viewDashboard' );
add_filter( 'ninja_forms_admin_extend_capabilities', 'penf_viewDashboard' );
add_filter( 'ninja_forms_admin_add_new_capabilities', 'penf_viewDashboard' );


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
