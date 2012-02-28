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
        <table cellspacing="1" cellpadding="1" width="100%" border="1px">
        <?php
        if(!$mail) {
            echo '<tr><td colspan="5">You have no sent messages.</td></tr>';
        }
        else {
            ?>
            <tr>
                <td colspan="5" align="center"><b>Sent items for <?php echo Auth::$userinfo->firstname.' '.Auth::$userinfo->lastname.' '.$pilotcode; ?></th>
            </tr>
            <tr>
                <th>Status</th>
                <th>To</th>
                <th>Subject</th>
                <th>Date sent</th>
                <th>Remove</th>
            </tr>
    <?php
        foreach($mail as $test) {
            if ($test->read_state=='0') {
                $status = 'env_closed.gif ';
            }
            else {
                $status = 'env_open.gif';
            }
            $user = PilotData::GetPilotData($test->who_to); $pilot = PilotData::GetPilotCode($user->code, $test->who_to);
    ?>
            <tr>
                <td align="center"><img src="<?php echo SITE_URL?>/core/modules/Mail/mailimages/<?php echo $status;?>" border="0" /></td>
                <td align="center"><?php
                    if ($test->notam=='1') {
                        echo 'NOTAM (All Pilots)';
                    }
                    else {
                        echo $user->firstname.' '.$user->lastname; ?> <?php echo $pilot;
                    } ?>
                </td>
                <td align="center"><?php echo $test->subject; ?></td>
                <td align="center"><?php echo date(DATE_FORMAT.' h:ia', strtotime($test->date)); ?></td>
                <td align="center"><a href="<?php echo SITE_URL ?>/index.php/Mail/sent_delete/?mailid=<?php echo $test->id;?>"><img src="<?php echo SITE_URL?>/core/modules/Mail/mailimages\delete.gif" alt="Delete" border="0"/></a></td>

            </tr>
    <?php
    }
}
?>	
            <tr>
                <td colspan="4"><b><font size="1.5px">| AIRmail3 &copy 2011 | <a href="http://www.simpilotgroup.com">simpilotgroup.com</a> |</font></b></td>
                <td align="center"><a href="<?php echo url('/mail/delete_allsent'); ?>" onclick="return confirm('Delete All Sent Messages From View?')">Remove All</a></td>
            </tr>
        </table>
    </center>