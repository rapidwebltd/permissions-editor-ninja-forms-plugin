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