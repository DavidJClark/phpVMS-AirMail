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

class MailData extends CodonData {

    public static function getallmail($pid) {
        $query = "SELECT *
                FROM   airmail
                WHERE who_to='$pid'
                AND deleted_state='0'
                AND receiver_folder='0'
                ORDER BY date ASC";

        return DB::get_results($query);
    }

    public static function get_unopen_count($pid) {
        $query = "SELECT COUNT(*) AS total
                FROM   airmail
                WHERE who_to='$pid'
                AND deleted_state='0'
                AND read_state='0'";

        $count = DB::get_row($query);
        return $count->total;
    }

    public static function getsentmail($pid) {
        $query = "SELECT *
                FROM   airmail
                WHERE who_from='$pid'
                AND sent_state='0'
                AND notam<'2'
                ORDER BY date ASC";

        return DB::get_results($query);
    }
    
    public function send_new_mail($who_to, $who_from, $subject, $newmessage, $notam, $thread_id) {
        $sql="INSERT INTO airmail (who_to, who_from, date, subject, message, notam, thread_id)
			VALUES ('$who_to', '$who_from', NOW(), '$subject', '$newmessage', '$notam', '$thread_id')";
                
        DB::query($sql);
    }
    
    //check to see if pilot wants email sent when new message arrives
    public function send_email($pid)    {
        $query = "SELECT email FROM airmail_email WHERE pilot_id='$pid'";
        
        $result = DB::get_row($query);
        if($result->email == 1)
        {return TRUE;}
        else
        {return FALSE;}
    }
    
    //remove email setting
    public function remove_email_setting($pid)  {
        $query = "DELETE FROM airmail_email WHERE pilot_id='$pid'";
        
        DB::query($query);
    }
    
    //set email pilot setting
    public function set_email_setting($pid) {
        $query = "INSERT INTO airmail_email (pilot_id, email) VALUES ('$pid', '1')";
        
        DB::query($query);
    }

    public static function deletemailitem($mailid) {
        $pid = Auth::$userinfo->pilotid;
        $sql = "SELECT *
                FROM   airmail
                WHERE id='$mailid'
                AND who_to='$pid'";

        $upd = "UPDATE airmail SET deleted_state=1, read_state=1 WHERE id='$mailid'";

        DB::query($upd);

        return DB::get_results($sql);
    }
    
    //delete a pilots messages from inbox and foldersviews
    function delete_inbox($pid, $folderid) {
        $query = "UPDATE airmail SET read_state=1, deleted_state=1 WHERE who_to='$pid' AND receiver_folder='$folderid'";
        DB::query($query);
    }
    
    //delete a pilots sent messages from view
    function delete_sentbox($pid) {
        $query = "UPDATE airmail SET sent_state=1 WHERE who_from='$pid'";
        DB::query($query);
    }

    //delete all sent items from pilots view
    public static function deletesentmailitem($mailid) {
        $pid = Auth::$userinfo->pilotid;
        $sql = "SELECT *
                FROM   airmail
                WHERE id='$mailid'
                AND who_from='$pid'
            ";

        $upd = "UPDATE airmail SET sent_state=1 WHERE id='$mailid'";

        DB::query($upd);

        return DB::get_results($sql);
    }

    public static function getmailcontent($thread_id) {
        $pid = Auth::$userinfo->pilotid;
        $sql = "SELECT *
                FROM airmail
                WHERE thread_id='$thread_id'
                AND who_to = '$pid'
                ORDER BY id ASC
            ";
        $upd = "UPDATE airmail SET read_state=1 WHERE thread_id='$thread_id' AND who_to='$pid'";

        DB::query($upd);
        
        return DB::get_results($sql);
    }
    public static function checkformail() {
        $pid = Auth::$userinfo->pilotid;
        $query = "SELECT COUNT(*) AS total
                FROM airmail
                WHERE read_state=0
                AND who_to='$pid'";

        return DB::get_row($query);
    }

    public function savenewfolder($folder_title) {
        $pilot_id = Auth::$userinfo->pilotid;
        $query ="INSERT INTO airmail_folders (pilot_id, folder_title)
                    VALUES ('$pilot_id', '$folder_title')";
        DB::query($query);
    }

    public function checkforfolders($pid)   {
        $query = "SELECT *
                    FROM airmail_folders
                    WHERE pilot_id='$pid'
                    ORDER BY folder_title ASC";

        return DB::get_results($query);
    }

    public function getfoldercontents($id)  {
        $query = "SELECT *
                    FROM airmail_folders
                    WHERE id='$id'";

        return DB::get_row($query);
    }

    public function getfoldermail($id)  {
        $pid = Auth::$userinfo->pilotid;
        $query = "SELECT *
                FROM   airmail
                WHERE who_to='$pid'
                AND deleted_state='0'
                AND receiver_folder='$id'
                ORDER BY date ASC";

        return DB::get_results($query);
    }

    public function movemail($mail_id, $folder)  {
        $upd = "UPDATE airmail SET receiver_folder='$folder' WHERE id='$mail_id'";

        DB::query($upd);
    }

    public function deletefolder($folder_id)    {
        $upd = "UPDATE airmail SET receiver_folder='0' WHERE reciever_folder='$folder_id'";

        DB::query($upd);

        $query2 = "DELETE FROM airmail_folders
                WHERE id='$folder_id'";

        DB::query($query2);
    }

    public function editfolder($folder_id, $folder_title)   {
        $upd = "UPDATE airmail_folders SET folder_title='$folder_title' WHERE id='$folder_id'";

        DB::query($upd);
    }
    
    public function getprofilemail($pilotid)    {
        $query = "SELECT * FROM airmail WHERE who_to='$pilotid' ORDER BY date DESC LIMIT 2";
        
        return DB::get_results($query);
    }
}