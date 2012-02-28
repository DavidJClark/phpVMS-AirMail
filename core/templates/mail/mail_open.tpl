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
                $user = PilotData::GetPilotData($data->who_from); $pilot = PilotData::GetPilotCode($user->code, $data->who_from);
                ?>
                <tr bgcolor="#cccccc">
                    <td align="center"><b>From: <?php echo $user->firstname.' '. $user->lastname.' '.$pilot; ?></b></td>
                    <td align="center"><b><?php echo date(DATE_FORMAT.' h:ia', strtotime($data->date)); ?></b></td>
                    <td align="center"><b>Subject: <?php echo $data->subject; ?></b></td>
                </tr>
                <tr bgcolor="#eeeeee">
                    <td colspan="4" align="left"><b>Message:</b><br /><br /><?php echo $data->message; ?><br /></td>
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