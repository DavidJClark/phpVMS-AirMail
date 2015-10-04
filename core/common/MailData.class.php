<?php
/**
 * AIRMail3
 * simpilotgroup addon module for phpVMS virtual airline system
 *
 * simpilotgroup addon modules are licenced under the following license:
 * Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 * To view full icense text visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author David Clark (simpilot)
 * @copyright Copyright (c) 2009-2011, David Clark
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @contributors oxymoron;
 */

class MailData extends CodonData {

    public static function getallmail($pid) {
        if(Auth::$userinfo->pilotid != $pid){
            throw new Exception("This mailbox does not belong to you. If you are accessing this via an Administration module, this feature is still in development");
        }
        $query = "SELECT *
                FROM   `".TABLE_PREFIX."airmail`
                WHERE `who_to`='$pid'
                AND `deleted_state`='0'
                AND `receiver_folder`='0'
                ORDER BY `date` ASC";

        $results = DB::get_results($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return $results;
    }

    public static function get_unopen_count($pid) {
        if(Auth::$userinfo->pilotid != $pid){
            throw new Exception("This mailbox does not belong to you. If you are accessing this via an Administration module, this feature is still in development");
        }
        $query = "SELECT COUNT(*) AS total
                FROM   `".TABLE_PREFIX."airmail`
                WHERE `who_to`='$pid'
                AND `deleted_state`='0'
                AND `read_state`='0'";

        $count = DB::get_row($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return $count->total;
    }

    public static function getsentmail($pid) {
        if(Auth::$userinfo->pilotid != $pid){
            throw new Exception("This mailbox does not belong to you. If you are accessing this via an Administration module, this feature is still in development");
        }
        $query = "SELECT *
                FROM   `".TABLE_PREFIX."airmail`
                WHERE `who_from`='$pid'
                AND `sent_state`='0'
                AND `notam`<'2'
                ORDER BY `date` ASC";

        $results = DB::get_results($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return $results;
    }

    public static function send_new_mail($who_to, $who_from, $subject, $newmessage, $notam, $thread_id) {
        $sql="INSERT INTO ".TABLE_PREFIX."airmail (`who_to`, `who_from`, `date`, `subject`, `message`, `notam`, `thread_id`)
			VALUES ('$who_to', '$who_from', NOW(), '$subject', '$newmessage', '$notam', '$thread_id')";

        DB::query($sql);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return;
    }

    //check to see if pilot wants email sent when new message arrives
    public static function send_email($pid)    {
        $query = "SELECT email FROM `".TABLE_PREFIX."airmail_email` WHERE `pilot_id`='$pid'";

        $result = DB::get_row($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        if($result->email == 1)
        {return TRUE;}
        else
        {return FALSE;}
    }

    //remove email setting
    public static function remove_email_setting($pid)  {
        if(Auth::$userinfo->pilotid != $pid){
            throw new Exception("This mailbox does not belong to you. If you are accessing this via an Administration module, this feature is still in development");
        }
        $query = "DELETE FROM `".TABLE_PREFIX."airmail_email` WHERE `pilot_id`='$pid'";

        DB::query($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return;
    }

    //set email pilot setting
    public static function set_email_setting($pid) {
        if(Auth::$userinfo->pilotid != $pid){
            throw new Exception("This mailbox does not belong to you. If you are accessing this via an Administration module, this feature is still in development");
        }
        $query = "INSERT INTO `".TABLE_PREFIX."airmail_email` (`pilot_id`, `email`) VALUES ('$pid', '1')";

        DB::query($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return;
    }

    public static function deletemailitem($mailid, $pid = null) {
        $pid = ($pid == null) ? Auth::$userinfo->pilotid : (int)$pid;
        if($pid != Auth::$userinfo->pilotid){
            throw new Exception("This mailbox does not belong to you. ".
                        "If you are accessing this via an Administration module, ".
                        "this feature is still in development"
                    );
        }

        $mailid = (int)$mailid;

        $sql = "SELECT *
                FROM `".TABLE_PREFIX."airmail`
                WHERE `id`='$mailid'
                AND `who_to`='$pid'";

        $upd = "UPDATE `".TABLE_PREFIX."airmail` ".
                "SET ".
                //"`read_state`=1, ".
                "`deleted_state`=1 ".
                "WHERE `id`='$mailid'";

        DB::query($upd);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        $results = DB::get_results($sql);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return $results;
    }

    //delete a pilots messages from inbox and foldersviews
    public static function delete_inbox($pid, $folderid) {
        if(Auth::$userinfo->pilotid != $pid){
            throw new Exception("This mailbox does not belong to you. If you are accessing this via an Administration module, this feature is still in development");
        }
        $query = "UPDATE `".TABLE_PREFIX."airmail` ".
                "SET ".
                //"`read_state`=1, ".
                "`deleted_state`=1 ".
                "WHERE `who_to`='$pid' AND `receiver_folder`='$folderid'";
        DB::query($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return;
    }

    //delete a pilots sent messages from view
    public static function delete_sentbox($pid) {
        if(Auth::$userinfo->pilotid != $pid){
            throw new Exception("This mailbox does not belong to you. If you are accessing this via an Administration module, this feature is still in development");
        }
        //throw new Exception("We can find a better way of doing this... Function disabled for now.");
        $query = "UPDATE `".TABLE_PREFIX."airmail` SET `sent_state`=1 WHERE `who_from`='$pid'";
        DB::query($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return;
    }

    //delete all sent items from pilots view
    public static function deletesentmailitem($mailid, $pid = null) {
        $pid = ($pid == null) ? Auth::$userinfo->pilotid : (int)$pid;
        if($pid != Auth::$userinfo->pilotid){
            throw new Exception("This mailbox does not belong to you. ".
                        "If you are accessing this via an Administration module, ".
                        "this feature is still in development"
                    );
        }

        $mailid = (int)$mailid;

        $sql = "SELECT *
                FROM `".TABLE_PREFIX."airmail`
                WHERE `id`='$mailid'
                AND `who_from`='$pid'
               ";

        $upd = "UPDATE `".TABLE_PREFIX."airmail` SET `sent_state`=1 WHERE `id`='$mailid'";

        DB::query($upd);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        $results = DB::get_results($sql);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return $results;
    }

    public static function getmailcontent($thread_id, $pid = null) {
        $pid = ($pid == null) ? Auth::$userinfo->pilotid : (int)$pid;
        $thread_id = (int)$thread_id; // Cast as an integer

        $sql = "SELECT *
                FROM `".TABLE_PREFIX."airmail`
                WHERE `thread_id`='$thread_id'
                AND `who_to`='$pid'
                ORDER BY `id` ASC
            ";

        $results = DB::get_results($sql);
        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        if($pid != Auth::$userinfo->pilotid){
            $allow = false;
            foreach($results as $item){
                $allow = ($item->who_from == Auth::$userinfo->pilotid)?true:$allow;
            }
            if(!$allow){
                throw new Exception("This message does not belong to you. ".
                            "If you are accessing this via an Administration module, ".
                            "this feature is still in development"
                        );
            }
        }else{
            $upd = "UPDATE `".TABLE_PREFIX."airmail` SET `read_state`=1 WHERE `thread_id`='$thread_id' AND `who_to`='$pid'";
            DB::query($upd);
            $code = DB::errno();
            if ($code != 0){
                $message = DB::error();
                throw new Exception($message, $code);
            }
        }

        return $results;
    }

    public static function checkformail($pid = null) {
        $pid = ($pid == null) ? Auth::$userinfo->pilotid : (int)$pid;
        if(Auth::$userinfo->pilotid != $pid){
            throw new Exception("This mailbox does not belong to you. If you are accessing this via an Administration module, this feature is still in development");
        }
        $query = "SELECT COUNT(*) AS total
                FROM `".TABLE_PREFIX."airmail`
                WHERE `read_state`=0
                AND `who_to`='$pid'
                AND `deleted_state` = 0";

        $results = DB::get_row($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return $results;
    }

    public static function savenewfolder($folder_title) {
        $pilot_id = Auth::$userinfo->pilotid;
        $query ="INSERT INTO `".TABLE_PREFIX."airmail_folders` (`pilot_id`, `folder_title`)
                    VALUES ('$pilot_id', '$folder_title')";
        DB::query($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return;
    }

    public static function checkforfolders($pid)   {
        if(Auth::$userinfo->pilotid != $pid){
            throw new Exception("This mailbox does not belong to you. If you are accessing this via an Administration module, this feature is still in development");
        }
        $query = "SELECT *
                    FROM `".TABLE_PREFIX."airmail_folders`
                    WHERE `pilot_id`='$pid'
                    ORDER BY `folder_title` ASC";

        $results = DB::get_results($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return $results;
    }

    public static function getfoldercontents($id)  {
        $query = "SELECT *
                    FROM `".TABLE_PREFIX."airmail_folders`
                    WHERE `id`='$id'";

        $results = DB::get_row($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }
        //throw new Exception("TODO: Check if the folder belongs to the requesting party.");
        return $results;
    }

    public static function getfoldermail($id)  {
        $pid = Auth::$userinfo->pilotid;
        $query = "SELECT *
                FROM   `".TABLE_PREFIX."airmail`
                WHERE `who_to`='$pid'
                AND `deleted_state`='0'
                AND `receiver_folder`='$id'
                ORDER BY `date` ASC";

        $results = DB::get_results($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }
        //throw new Exception("TODO: Check if the folder belongs to the requesting party.");
        return $results;
    }

    public static function movemail($mail_id, $folder)  {
        $upd = "UPDATE `".TABLE_PREFIX."airmail` SET `receiver_folder`='$folder' WHERE `id`='$mail_id'";

        DB::query($upd);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return;
    }

    public static function deletefolder($folder_id)    {
        //throw new Exception("TODO: Check if the item belongs to the appropriate pilot.");
        $upd = "UPDATE `".TABLE_PREFIX."airmail` SET `receiver_folder`='0' WHERE `receiver_folder`='$folder_id'";

        DB::query($upd);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        $query2 = "DELETE FROM `".TABLE_PREFIX."airmail_folders`
                WHERE `id`='$folder_id'";

        DB::query($query2);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return;
    }

    public static function editfolder($folder_id, $folder_title)   {
        //throw new Exception("TODO: Check if the item belongs to the appropriate pilot.");
        $upd = "UPDATE `".TABLE_PREFIX."airmail_folders` SET `folder_title`='$folder_title' WHERE `id`='$folder_id'";

        DB::query($upd);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }
        return;
    }

    public static function getprofilemail($pilotid)    {
        $query = "SELECT * FROM `".TABLE_PREFIX."airmail` WHERE `who_to`='$pilotid' ORDER BY `date` DESC LIMIT 2";

        $results = DB::get_results($query);

        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }

        return $results;
    }
}