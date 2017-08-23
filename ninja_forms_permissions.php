<?php
/*
Plugin Name: Ninja Forms Permissions
Plugin URI: http://rapidweb.biz
Description: Give Editors access to the Ninja Forms menu in their dashboard area.
Version: 1.0.0
Author: Rapid Web Ltd
Author URI: http://rapidweb.biz

Based on dmajwool work here: https://wordpress.org/support/topic/works-but-there-is-capabilities-problems However, packaged it up with shorter function names
*/
// To give Editors access to the ALL Forms menu
function ninja_forms_editor_permissions( $capabilities ) {
    $capabilities = "edit_pages";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'ninja_forms_editor_permissions' );
add_filter( 'ninja_forms_admin_all_forms_capabilities', 'ninja_forms_editor_permissions' );
// To give Editors access to ADD New Forms
function my_custom_change_ninja_forms_add_new_capabilities_filter( $capabilities ) {
    $capabilities = "edit_pages";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'my_custom_change_ninja_forms_add_new_capabilities_filter' );
add_filter( 'ninja_forms_admin_add_new_capabilities', 'my_custom_change_ninja_forms_add_new_capabilities_filter' );

function nf_subs_capabilities( $cap ) {
    return 'edit_posts';
}
add_filter( 'ninja_forms_admin_submissions_capabilities', 'nf_subs_capabilities' );
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'nf_subs_capabilities' );
add_filter( 'ninja_forms_admin_menu_capabilities', 'nf_subs_capabilities' );

// To give Editors access to the Inport/Export Options
function custom_ninja_forms_import_export_capabilities_filter( $capabilities ) {
    $capabilities = "edit_pages";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'custom_ninja_forms_import_export_capabilities_filter' );
add_filter( 'ninja_forms_admin_import_export_capabilities', 'custom_ninja_forms_import_export_capabilities_filter' );

// To give Editors access to the the Settings page
function custom_ninja_forms_settings_capabilities_filter( $capabilities ) {
    $capabilities = "edit_pages";
    return $capabilities;
}
add_filter( 'ninja_forms_admin_parent_menu_capabilities', 'custom_ninja_forms_settings_capabilities_filter' );
add_filter( 'ninja_forms_admin_settings_capabilities', 'custom_ninja_forms_settings_capabilities_filter' );
