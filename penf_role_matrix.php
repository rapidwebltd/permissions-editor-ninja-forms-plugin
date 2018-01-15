<?php
global $wp_roles;
?> 
<h1><?php echo __('Permissions Editor for Ninja Forms', 'permissions-editor-for-ninja-forms') ?></h1>
<p><?php echo __('Simply use the matrix below to select the Ninja Forms capabilities that you wish to enable the different user roles to have access to.', 'permissions-editor-for-ninja-forms') ?></p>
<p><?php echo __('Please note that \'View Menu\' is required for any other capabilities and will be completed automatically upon selection of other capabilities.', 'permissions-editor-for-ninja-forms') ?></p>
<h2><?php echo __('Overview of each capability:', 'permissions-editor-for-ninja-forms') ?></h2>
<ul>
    <li><strong><?php echo __('Manage', 'permissions-editor-for-ninja-forms') ?></strong> - <?php echo __('enables the user to add new forms, edit/manage existing forms and access the Ninja Forms \'Add-ons\'', 'permissions-editor-for-ninja-forms') ?></li>
    <li><strong><?php echo __('Submissions', 'permissions-editor-for-ninja-forms') ?></strong> - <?php echo __('enables the user to view the submissions from form users', 'permissions-editor-for-ninja-forms') ?></li>
    <li><strong><?php echo __('Import / Export', 'permissions-editor-for-ninja-forms') ?></strong> - <?php echo __('provides the user with access to the Import / Export options for Ninja Forms', 'permissions-editor-for-ninja-forms') ?></li>
    <li><strong><?php echo __('Settings', 'permissions-editor-for-ninja-forms') ?></strong> - <?php echo __('provides user with access to the Ninja Forms settings', 'permissions-editor-for-ninja-forms') ?></li>
    
    <?php if (is_plugin_active('ninja-forms-excel-export/ninja-forms-excel-export.php')) { ?>
		<li><strong><?php echo __('Excel Export Extension', 'permissions-editor-for-ninja-forms') ?></strong> - <?php echo __('provides user with access to the Excel Export Extension', 'permissions-editor-for-ninja-forms') ?></li>
    <?php } ?>
</ul>
<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
<input type="hidden" name="action" value="penf_update_capabilities"/>
<table class="form-table striped">
<tr>
<th>&nbsp;</th>
<?php
foreach(penf_get_caps()  as $penfCapabilityKey => $penfCapability)
{     if($penfCapabilityKey == "penf_view_menu"){continue;}  
    echo "<th>".$penfCapability."</th>";
}
?>
</tr>

<?php
foreach($wp_roles->roles as $roleKey => $role)
{ ?> 
<tr> 
<?php 
 echo "<th>".$role['name']."</th>";
 foreach(penf_get_caps() as $penfCapabilityKey => $penfCapability)
 {
    if($penfCapabilityKey == "penf_view_menu"){continue;}  
?>
 <td><input type="checkbox" name="<?php echo $roleKey ?>[]" value="<?php echo $penfCapabilityKey ?>" <?php echo (isset($role['capabilities'][$penfCapabilityKey]) && $role['capabilities'][$penfCapabilityKey] == true) ? 'checked' : '' ?>/> 
<?php
 } ?>
 </tr> 
<?php } ?>

</table>

<p class="submit">
    <input type="submit" value="Save Changes" class="button button-primary" />
</p>
