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
<form action="<?php echo url('/Mail');?>" method="post" enctype="multipart/form-data">
    <table width="100%" cellspacing="1" cellpadding="5" border="1">
        <tr>
            <td colspan="3" align="center"><b>Delete message folder for <?php echo (Auth::$userinfo->firstname.' '.Auth::$userinfo->lastname.' '.$pilotcode); ?></b></td>
        </tr>
        <?php $folders = MailData::checkforfolders(Auth::$userinfo->pilotid);
        if(!$folders) {echo '<tr><td align="center" colspan="3">There Are No Folders To Delete</td></tr>';}
        else {?>
        <tr>
            <td align="center" colspan="3"><b>Select Folder To Delete</b><br /><br />
                    <select name="folder_id">
                            <?php foreach ($folders as $folder) {echo '<option value="'.$folder->id.'">'.$folder->folder_title.'</option>';}?>
                    </select>
                    <br />
            </td>
        </tr>
        <tr bgcolor="#cccccc">
            <td align="center" colspan="3">All AIRMail contained in the folder being deleted will be moved to the default AIRMail Inbox.</td>
        </tr>
        <tr>
            <td align="center" colspan="3">
                <input type="hidden" name="action" value="confirm_delete_folder" />
                <input type="submit" value="Delete Folder">
            </td>
        </tr>

        <?php } ?>
        <tr>
            <td align="center" colspan="3"><b><font size="1.5px">| AIRmail3 &copy 2011 | <a href="http://www.simpilotgroup.com">simpilotgroup.com</a> |</font></b></td>
        </tr>
    </table>
</form>