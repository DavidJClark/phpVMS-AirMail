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
    <table width="100%" cellspacing="1" cellpadding="1" border="1">
        <tr>
            <?php if(isset($folder)) {
                echo '<td colspan="6" align="center"><b>'.$folder->folder_title.' folder for '.Auth::$userinfo->firstname.' '.Auth::$userinfo->lastname.' '.$pilotcode.'</b>';
                echo ' - <a href="'.SITE_URL.'/index.php/Mail/editfolder/'.$folder->id.'">Edit Folder Name</a></td>';
                }
                else {echo '<td colspan="6" align="center"><b>Inbox for '.Auth::$userinfo->firstname.' '.Auth::$userinfo->lastname.' '.$pilotcode.'</b></td>';}
            ?>
        </tr>
        <tr>
            <th>Status</th>
            <th>From</th>
            <th>Subject</th>
            <th>Date</th>
            <th>Delete</th>
        </tr>
<?php if(!$mail) {
        echo '<tr><td colspan="5">You have no messages.</td></tr>';
        echo '<tr>
            <td colspan="5"><b><font size="1.5px">| AIRmail3 &copy 2011 | <a href="http://www.simpilotgroup.com">simpilotgroup.com</a> |</font></b></td>
        </tr>';
        }
        else {
            foreach($mail as $data) {
                if ($data->read_state=='0') {
                    $status = 'env_closed.gif' ;
                }
                else {
                    $status = 'env_open.gif';
                }
                ?>
                <?php $user = PilotData::GetPilotData($data->who_from); $pilot = PilotData::GetPilotCode($user->code, $data->who_from); ?>
        <tr bgcolor="#eeeeee">
            <td align="center"><img src="<?php echo SITE_URL?>/core/modules/Mail/mailimages/<?php echo $status;?>" border="0" alt="Status" /></td>
            <td align="center"><?php echo "$user->firstname $user->lastname $pilot"; ?></td>
            <td align="center"><a href="<?php echo SITE_URL ?>/index.php/Mail/item/<?php echo $data->thread_id;?>"><?php echo $data->subject; ?></a></td>
            <td align="center"><?php echo date(DATE_FORMAT.' h:ia', strtotime($data->date)); ?></td>
            <td align="center"><a href="<?php echo SITE_URL ?>/index.php/Mail/delete/<?php echo $data->id;?>">
                <img src="<?php echo SITE_URL?>/core/modules/Mail/mailimages/delete.gif" alt="Delete" border="0"/></a></td>
        </tr>
<?php
}
?>
        <tr>
            <td colspan="4"><b><font size="1.5px">| AIRmail3 &copy 2011 | <a href="http://www.simpilotgroup.com">simpilotgroup.com</a> |</font></b></td>
            <td align="center"><a href="<?php echo url('/mail/delete_all/').$data->reciever_folder; ?>" onclick="return confirm('Delete All Inbox Messages?')">Delete All</a></td>
        </tr>
        <?php
        }
        ?>

    </table>
</center>