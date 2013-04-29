<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Oxymoron290
 * @copyright Copyright (c) 2013, Timothy Sturm
 * @link http://www.phpvms.net
 * @contributor Wings On Air Virtual Airlines
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

echo '<h2>'.$title.'</h2><hr />';
echo '<div style="text-align: right; width: 100%">';
    if($title == 'Address Book'){
        echo '<a href="'.SITE_URL.'/index.php/contacts/blocked">View Blocked Contacts</a>';
    }else{
        echo '<a href="'.SITE_URL.'/index.php/contacts/index">View Address Book</a>';
    }
echo '</div>';

if($contacts == null){
    $this->set('message', 'There are no contacts in this list!');
    Template::show('core_error.tpl');
    return;
}
?>
<table id="tabledlist" class="tablesorter">
    <thead>
        <tr>
            <td>Pilot Name</td>
            <td>Options</td>
            <td>Relationship</td>
        </tr>
    </thead>
    <tbody>
<?php
foreach($contacts as $contact){
    $contactInfo = PilotData::getPilotData($contact->contact);
    echo '<tr>';
    echo '<td>'.
            '<img src="'.
            Countries::getCountryImage($contactInfo->location).'" alt="'.
            Countries::getCountryName($contactInfo->location).'" />'.
            
            '<a href="'.url('/profile/view/'.$contactInfo->pilotid).'">'.
            PilotData::GetPilotCode($contactInfo->code, $contactInfo->pilotid).
            '</a>'.
            
            $contactInfo->firstname.' '.$contactInfo->lastname.
         '</td>';
    
    echo '<td>';
    if($contact->blocked == 'N'){
        echo '<a href="">Edit</a> | '.
            '<a href="'.SITE_URL.'/index.php/Mail/newmail/'.$contact->contact.'">Message</a> | '.
            '<a href="'.SITE_URL.'/index.php/contacts/remove_contact/'.$contact->contact.'">Remove</a> | '.
            '<a href="'.SITE_URL.'/index.php/contacts/block_contact/'.$contact->contact.'">Block</a> | ';
    }else{
        echo '<a href="'.SITE_URL.'/index.php/contacts/unblock_contact/'.$contact->contact.'">Unblock</a> | ';
    }
    echo '</td>';
    echo '<td>'.$contact->relation.'</td>';
    echo '</tr>';
}
?>
    </tbody>
</table>
