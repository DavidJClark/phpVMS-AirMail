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
        <?php foreach($mail as $data) ?>
        <form action="<?php echo url('/Mail');?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="who_from" value="<?php echo Auth::$userinfo->pilotid ?>" />
        <input type="hidden" name="who_to" value="<?php echo $data->who_from; ?>" />
        <input type="hidden" name="oldmessage" value="<?php echo ' '.$data->thread_id.'<br /><br />'; ?>" />
        <table cellspacing="1" cellpadding="5" border="1" width="100%">
            <?php $user = PilotData::GetPilotData($data->who_from); $pilot = PilotData::GetPilotCode($user->code, $data->who_from); ?>
            <tr bgcolor="#cccccc">
                <td><b>To Pilot ID: <?php echo $pilot; ?></b></td>
                <td><b>Subject:<input type="text" name="subject" value="RE: <?php echo $data->subject;?>"</b></td>
            </tr>
            <tr bgcolor="#eeeeee">
                <td colspan="2" align="left">
                    <b>Last Message</b> - <?php echo $data->message; ?><br /><br />
                    <center>
                        <textarea name="message" rows="10" cols="90" wrap="soft"></textarea>
                </td>
            </tr>
            <tr bgcolor="#cccccc"><td colspan="2">
                    <input type="hidden" name="action" value="send" />
                    <input type="submit" value="Send AIRmail"></td>
            </tr>
            <tr>
                <td colspan="2"><b><font size="1.5px">| AIRmail3 &copy 2011 | <a href="http://www.simpilotgroup.com">simpilotgroup.com</a> |</font></b></td>
            </tr>
        </table>
        </form>
    </center>