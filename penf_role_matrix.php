<? 
global $wp_roles;

if ($_POST) {
    foreach($wp_roles->roles as $roleKey => $role) {
        $role = get_role($roleKey);
        foreach(penf_get_caps() as $penfCapabilityKey => $penfCapability) {
            $role->remove_cap($penfCapabilityKey);
        }
    }
    foreach($_POST as $roleKey => $penfCapabilityKeys) {
        $role = get_role($roleKey);
        foreach($penfCapabilityKeys as $penfCapabilityKey) {
            $role->add_cap($penfCapabilityKey);
        }
    }
}

?> 
<h1>Permissions Editor for Ninja Forms</h1>
<p>Simply use the matrix below to select the Ninja Forms capabilities that you wish to enable the different user roles to have access to.</p>
<p>Please note that 'View Menu' is required for any other capabilities and will be completed automatically upon selection of other capabilities.</p>
<h2>Overview of each capability:</h2>
<ul>
    <li><strong>Manage</strong> - enables the user to add new forms, edit/manage existing forms and access the Ninja Forms 'Add-ons'</li>
    <li><strong>Submissions</strong> - enables the user to view the submissions from form users</li>
    <li><strong>Import / Export</strong> - provides the user with access to the Import / Export options for Ninja Forms</li>
    <li><strong>Settings</strong> - provides user with access to the Ninja Forms settings</li>
</ul>
<form method="post">
<table class="form-table striped">
<tr>
<th>&nbsp;</th>
<?
foreach(penf_get_caps()  as $penfCapabilityKey => $penfCapability)
{
    echo "<th>".$penfCapability."</th>";
}
?>
</tr>

<?
foreach($wp_roles->roles as $roleKey => $role)
{?> 
<tr> 
<? 
 echo "<th>".$role['name']."</th>";
 foreach(penf_get_caps() as $penfCapabilityKey => $penfCapability)
 {?>
 <td><input type="checkbox" class="regular-text" name="<?= $roleKey ?>[]" value="<?= $penfCapabilityKey ?>" <?= (isset($role['capabilities'][$penfCapabilityKey]) && $role['capabilities'][$penfCapabilityKey] == true) ? 'checked' : '' ?>/> 
<?
 } ?>
 </tr> 
<? } ?>

</table>

<p class="submit">
    <input type="submit" value="Save Changes" class="button button-primary" />
</p>