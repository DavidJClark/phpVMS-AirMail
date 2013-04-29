<?php
//AIRMail3
//simpilotgroup addon module for phpVMS virtual airline system
//
//simpilotgroup addon modules are licenced under the following license:
//Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
//To view full icense text visit http://creativecommons.org/licenses/by-nc-sa/3.0/
//
//@author David Clark (simpilot)
//@copyright Copyright (c) 2009-2011, David Clark
//@license http://creativecommons.org/licenses/by-nc-sa/3.0/
?>
<center>
    <table cellspacing="1" cellpadding="5" width="100%" border="1px">
        <tr>
           <td colspan="3"><b>Message Thread</b></td>
        </tr>
        <?php
            foreach ($mail as $data) {
        ?>
                <tr bgcolor="#cccccc">
                    <td align="center">
                        <b>
                            <?php
                                if($data->who_to == Auth::$userinfo->pilotid){
                                    $user = PilotData::GetPilotData($data->who_from);
                                    $pilot = PilotData::GetPilotCode($user->code, $data->who_from);
                                    if($user){
                                        echo 'From: ';
                                    }
                                }
                                if($data->who_from == Auth::$userinfo->pilotid){
                                    $user = PilotData::GetPilotData($data->who_to);
                                    $pilot = PilotData::GetPilotCode($user->code, $data->who_to);
                                    if($user){
                                        echo 'To: ';
                                    }
                                }
                                
                                if($user){
                                    echo $user->firstname.' '. $user->lastname.' - '.$pilot;

                                    $contact = ContactsData::getContact($user->pilotid, Auth::$userinfo->pilotid);

                                    //TODO: Add onclick events for the following buttons
                                    // Maybe we want to call these in the background?

                                    if($contact->blocked == 'Y'){
                                        echo '<a href="'.SITE_URL.'/index.php/contacts/unblock/'.$user->pilotid.'">[<img src="'.SITE_URL.'/core/modules/Mail/mailimages/blocked.gif" alt="Unblock Pilot" />Unblock Pilot]</a>';
                                    }else{
                                        if($contact == null){
                                            echo '<a href="'.SITE_URL.'/index.php/contacts/add/'.$user->pilotid.'">[<img src="'.SITE_URL.'/core/modules/Mail/mailimages/addContact.png" alt="Add Pilot as Contact" />Add Pilot]</a>';
                                            echo '<a href="'.SITE_URL.'/index.php/contacts/block/'.$user->pilotid.'">[<img src="'.SITE_URL.'/core/modules/Mail/mailimages/blocked.gif" alt="Block Pilot" />Block Pilot]</a>';
                                        }else{
                                            // I'm not sure if we need this if here.
                                            if($contact->blocked == 'N'){
                                                echo '<a href="'.SITE_URL.'/index.php/contacts/remove/'.$user->pilotid.'">[<img src="'.SITE_URL.'/core/modules/Mail/mailimages/removeContact.png" alt="Remove Pilot as Contact" />Remove Pilot]</a>';
                                            }
                                        }
                                    }
                                }
                            ?>
                        
                        </b>
                    </td>
                    <td align="center"><b><?php echo date(DATE_FORMAT.' h:ia', strtotime($data->date)); ?></b></td>
                    <td align="center"><b>Subject: <?php echo $data->subject; ?></b></td>
                </tr>
                <tr bgcolor="#eeeeee">
                    <td colspan="4" align="left"><b>Message:</b><br /><br /><?php echo nl2br($data->message); ?><br /></td>
                </tr>

            <?php
            }
            ?>
        <tr bgcolor="#eeeeee">
            <td colspan="2" align="left"><b><a href="<?php echo SITE_URL ?>/index.php/Mail/reply/<?php echo $data->thread_id;?>">Reply</a></b></td>
            <td align="right"><b><a href="<?php echo SITE_URL ?>/index.php/Mail/move_message/<?php echo $data->id;?>">Move To Folder</a></b></td>
        </tr>
        <tr>
        <td colspan="3"><b><font size="1.5px">| AIRmail3 &copy 2011 | <a href="http://www.simpilotgroup.com">simpilotgroup.com</a> |</font></b></td>
        </tr>
    </table>
</center>