<?php
/*
Plugin Name: Permissions Editor for Ninja Forms 
Plugin URI: http://rapidweb.biz
Description: Plugin that enables diffent user permsions for ninja forms.
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
