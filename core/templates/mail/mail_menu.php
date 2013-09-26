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
<?php if(isset($message)) {echo $message.'<br />';} ?>
<table width="100%">
    <tr>
        <td align="left" width="20%"><img src="<?php echo SITE_URL?>/core/modules/Mail/mailimages/airmail_logo.png" border="0" alt="AIRMail"/></td>
        <td align="center">
            | <a href="<?php echo SITE_URL ?>/index.php/Mail">Inbox</a> |
            <a href="<?php echo SITE_URL ?>/index.php/Mail/newmail">Compose New message</a> |
            <a href="<?php echo SITE_URL ?>/index.php/Mail/newfolder">Create New Folder</a> |
            <a href="<?php echo SITE_URL ?>/index.php/Mail/deletefolder">Delete A Folder</a> |
            <a href="<?php echo SITE_URL ?>/index.php/Mail/settings">Settings</a> |
            <br />
            |<b> Folders </b>| 
                <?php
                if (isset($folders)) {foreach ($folders as $folder) {echo '<a href="'.SITE_URL.'/index.php/Mail/getfolder/'.$folder->id.'">'.$folder->folder_title.'</a> |';}
                }
                ?>
            <a href="<?php echo SITE_URL ?>/index.php/Mail/sent">Sent Messages</a> |
        </td>
        <td width="20%" align="right"><img src="<?php echo SITE_URL?>/core/modules/Mail/mailimages/mail_small.png" border="0" alt="AIRMail" /></td>
    </tr>
</table>