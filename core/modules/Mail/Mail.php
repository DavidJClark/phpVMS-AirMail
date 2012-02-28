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

class Mail extends CodonModule {

    public function index() {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        elseif($this->post->action == 'send') {
            $this->send();
        }
        elseif($this->post->action == 'savefolder') {
            $this->savefolder();
        }
        elseif($this->post->action == 'move') {
            $this->move();
        }
        elseif($this->post->action == 'confirm_delete_folder') {
            $this->confirm_delete_folder();
        }
        elseif($this->post->action == 'confirm_edit_folder') {
            $this->confirm_edit_folder();
        }
        elseif($this->post->action == 'save_settings') {
            $this->save_settings();
        }
        else {
            $this->inbox();
        }
    }
    
    //message screen
    public function message()   {
        $this->show('mail/mail_message');
    }
    
    //main inbox
    public function inbox() {
        $pid = Auth::$userinfo->pilotid;
        $this->set('mail', MailData::getallmail($pid));
        $this->set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
        $this->menu();
        $this->show('mail/mail_inbox.tpl');
    }
    
    //internal function to show top menu for airmail
    public function menu()  {
        $this->set('folders', MailData::checkforfolders(Auth::$userinfo->pilotid));
        $this->show('mail/mail_menu.tpl');
    }

    public function item($thread_id) {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        else {
            $this->set('mail', MailData::getmailcontent($thread_id));
            $this->menu();
            $this->show('mail/mail_open.tpl');
        }
    }

    //create new message
    public function newmail() {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        else {
            $this->set('allpilots', $pilots=(PilotData::findPilots(array('p.retired' => '0'))));
            $this->menu();
            $this->show('mail/mail_new.tpl');
        }
    }

    //send new message
    protected function send() {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $who_to = DB::escape($this->post->who_to);
        $who_from = DB::escape($this->post->who_from);
        $subject = DB::escape($this->post->subject);
        $message = DB::escape($this->post->message);
        $oldmessage = DB::escape($this->post->oldmessage);
        
        if($oldmessage)
        {
            $thread_id = $oldmessage; 
        }
        else
        {
            $thread_id = time();
        }

        if(!$who_to) {
            $nosender = 'You must choose a reciepent';
            echo '<center><br /><b>'.$nosender.'</b><br /></center>';
            $this->inbox();
            return;
        }
        
        if (!$subject) {
            $subject = '(No Subject)';
        }

        if($who_to == 'all')
            {
            $notam = 0;
            $pilots=(PilotData::findPilots(array('p.retired' => '0')));
                foreach ($pilots as $pilot) {
                    $notam = $notam+1;
                    MailData::send_new_mail($pilot->pilotid, $who_from, $subject, $message, $notam, $thread_id);
                    if(MailData::send_email($pilot->pilotid) === TRUE)
                    {
                        $email = $pilot->email;
                        $sub = 'You have reveived a new message at '.SITE_NAME;
                        $message = 'You received a new message with the subject ('.$subject.') in your pilot\'s message inbox on '.date('m/d/Y', time());
                        Util::SendEmail($email, $sub, $message);
                    }
                }
            }
            else
            {
                MailData::send_new_mail($who_to, $who_from, $subject, $message, '0', $thread_id);
                if(MailData::send_email($who_to) === TRUE)
                    {
                        $pilot = PilotData::getPilotData($who_to);
                        $email = $pilot->email;
                        $sub = 'You have reveived a new message at '.SITE_NAME;
                        $message = 'You received a new message with the subject ('.$subject.') in your pilot\'s message inbox on '.date('m/d/Y', time());
                        Util::SendEmail($email, $sub, $message);
                    }
            }

        $this->set('message', '<div id="success">AIRMail Message Sent!</div>');
        $this->message();
    }

    //get sent messages
    public function sent() {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $pid = Auth::$userinfo->pilotid;
        $this->set('mail', MailData::getsentmail($pid));
        $this->set('pilotcode', PilotData::GetPilotCode(Auth::$userinfo->code, Auth::$userinfo->pilotid));
        $this->menu();
        $this->show('mail/mail_sentitems.tpl');
    }
    
    //settings for pilots
    public function settings()  {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $this->menu();
        $this->show('mail/mail_settings.tpl');
    }
    
    //save new settings for pilot
    protected function save_settings()  {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        if($this->post->email == '0')
        {MailData::remove_email_setting(Auth::$userinfo->pilotid);}
        else
        {
            if(MailData::send_email(Auth::$userinfo->pilotid) != TRUE)
            {MailData::set_email_setting(Auth::$userinfo->pilotid);}
        }
        $this->set('message', '<div id="success">Settings Saved</div>');
        $this->message();
    }

    //delete a single message from view
    public function delete($mail_id) {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        MailData::deletemailitem($mail_id);
        header('Location: '.url('/Mail'));
    }
    
    //delete all messages in particular folder view for pilot
    public function delete_all($folder)    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $pid = Auth::$userinfo->pilotid;
        MailData::delete_inbox($pid, $folderid);
        $this->set('message', '<div id="success">All Inbox Messages Deleted</div>');
        $this->message();
    }

    //delete single sent item from senders view
    public function sent_delete() {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $mailid = $_GET['mailid'];
        MailData::deletesentmailitem($mailid);
        $this->sent();
    }
    
    //delete all sent items from view
    public function delete_allsent()    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $pid = Auth::$userinfo->pilotid;
        MailData::delete_sentbox($pid);
        $this->set('message', '<div id="success">All Sent Messages Deleted From View</div>');
        $this->message();
    }

    public function reply($thread_id) {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $this->set('mail', MailData::getmailcontent($thread_id));
        $this->menu();
        $this->show('mail/mail_reply.tpl');
    }

    public function checkmail() {
        if (!Auth::LoggedIn()) {
        }
        else {
            $item = MailData::checkformail();
            $items = $item->total;
            $this->set('items', $items);
            $this->show('mail/mail_check.tpl');
        }
    }

    public function checkforfolders($pid) {
        $query = "SELECT *
                    FROM airmail_folders
                    WHERE pilot_id='$pid'";

        return DB::query($query);
    }

    //name new folder
    public function newfolder() {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $this->menu();
        $this->show('mail/mail_newfolder.tpl');
    }

    //save new folder
    protected function savefolder() {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $folder_title = DB::escape($this->post->folder_title);
        MailData::savenewfolder($folder_title);
        header('Location: '.url('/Mail'));
    }

    public function getfolder($id) {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $this->set('folder', MailData::getfoldercontents($id));
        $this->set('mail', MailData::getfoldermail($id));
        $this->menu();
        $this->show('mail/mail_inbox.tpl');
    }

    public function move_message($id)  {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $this->set('mail_id', $id);
        $this->set('folders', MailData::checkforfolders(Auth::$userinfo->pilotid));
        $this->menu();
        $this->show('mail/move_message');
    }
    
    protected function move() {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $folder = DB::escape($this->post->folder);
        $mail_id = DB::escape($this->post->mail_id);
        MailData::movemail($mail_id, $folder);
        header('Location: '.url('/Mail/getfolder/'.$folder));
    }

    public function editfolder($id)    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $this->set('folder', MailData::getfoldercontents($id));
        $this->menu();
        $this->show('mail/mail_editfolder.tpl');
    }

    public function deletefolder()  {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            return;
        }
        $this->menu();
        $this->show('mail/mail_deletefolder.tpl');
    }

    protected function confirm_delete_folder()   {
        $folder_id = DB::escape($this->post->folder_id);

        MailData::deletefolder($folder_id);
        header('Location: '.url('/Mail'));
    }

     protected function confirm_edit_folder()   {
        $folder_id = DB::escape($this->post->folder_id);
        $folder_title = DB::escape($this->post->folder_title);
        MailData::editfolder($folder_id, $folder_title);
        header('Location: '.url('/Mail'));
    }
}