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
        if(count($penfCapabilityKeys) > 0 )
        {
         $role->add_cap("penf_view_menu");
        }
        foreach($penfCapabilityKeys as $penfCapabilityKey) {
            $role->add_cap($penfCapabilityKey);
        } 
    }
}

?> 
<form method="post">
<table>
<tr>
<th>&nbsp;</th>
<?
foreach(penf_get_caps()  as $penfCapabilityKey => $penfCapability)
{     if($penfCapabilityKey == "penf_view_menu"){continue;}  
    echo "<th>".$penfCapability."</th>";
}
?>
</tr>

<?
foreach($wp_roles->roles as $roleKey => $role)
{ ?> 
<tr> 
<? 
 echo "<td>".$role['name']."</td>";
 foreach(penf_get_caps() as $penfCapabilityKey => $penfCapability)
 {
    if($penfCapabilityKey == "penf_view_menu"){continue;}  
?>
 <td><input type="checkbox" name="<?= $roleKey ?>[]" value="<?= $penfCapabilityKey ?>" <?= (isset($role['capabilities'][$penfCapabilityKey]) && $role['capabilities'][$penfCapabilityKey] == true) ? 'checked' : '' ?>/> 
<?
 } ?>
 </tr> 
<? } ?>

</table>

<input type="submit" value="Save" />