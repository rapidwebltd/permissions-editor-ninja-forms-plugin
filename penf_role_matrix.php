<?php
global $wp_roles;
?> 
<h1><? echo __('Permissions Editor for Ninja Forms', 'permissions-editor-for-ninja-forms') ?></h1>
<p><? echo __('Simply use the matrix below to select the Ninja Forms capabilities that you wish to enable the different user roles to have access to.', 'permissions-editor-for-ninja-forms') ?></p>
<p><? echo __('Please note that \'View Menu\' is required for any other capabilities and will be completed automatically upon selection of other capabilities.', 'permissions-editor-for-ninja-forms') ?></p>
<h2><? echo __('Overview of each capability:', 'permissions-editor-for-ninja-forms') ?></h2>
<ul>
    <li><strong><? echo __('Manage', 'permissions-editor-for-ninja-forms') ?></strong> - <? echo __('enables the user to add new forms, edit/manage existing forms and access the Ninja Forms \'Add-ons\'', 'permissions-editor-for-ninja-forms') ?></li>
    <li><strong><? echo __('Submissions', 'permissions-editor-for-ninja-forms') ?></strong> - <? echo __('enables the user to view the submissions from form users', 'permissions-editor-for-ninja-forms') ?></li>
    <li><strong><? echo __('Import / Export', 'permissions-editor-for-ninja-forms') ?></strong> - <? echo __('provides the user with access to the Import / Export options for Ninja Forms', 'permissions-editor-for-ninja-forms') ?></li>
    <li><strong><? echo __('Settings', 'permissions-editor-for-ninja-forms') ?></strong> - <? echo __('provides user with access to the Ninja Forms settings', 'permissions-editor-for-ninja-forms') ?></li>
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
 <td><input type="checkbox" name="<? echo $roleKey ?>[]" value="<? echo $penfCapabilityKey ?>" <? echo (isset($role['capabilities'][$penfCapabilityKey]) && $role['capabilities'][$penfCapabilityKey] == true) ? 'checked' : '' ?>/> 
<?php
 } ?>
 </tr> 
<?php } ?>

</table>

<p class="submit">
    <input type="submit" value="Save Changes" class="button button-primary" />
</p>