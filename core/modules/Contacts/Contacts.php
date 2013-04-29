<?php
/**
 * phpVMS - Virtual Airline Administration Software
 * Copyright (c) 2008 Nabeel Shahzad
 * For more information, visit www.phpvms.net
 *	Forums: http://www.phpvms.net/forum
 *	Documentation: http://www.phpvms.net/docs
 *
 * phpVMS is licenced under the following license:
 *   Creative Commons Attribution Non-commercial Share Alike (by-nc-sa)
 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/3.0/
 *
 * @author Oxymoron290
 * @copyright Copyright (c) 2013, Timothy Sturm
 * @link http://www.phpvms.net
 * @contributor Wings On Air Virtual Airlines
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

class Contacts extends CodonModule
{
    private $error = false;
    
    public function __construct(){
        if(!class_exists('MailData')){
            $this->set('message', "You are missing the Airmail Module by SimPilot!");
            $this->render('core_error.tpl');
            $this->error = true;
            return;
        }
    }
    
    /**
     * Contacts::index()
     * 
     * @return
     **/
    public function index()
    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            $this->error = true;
        }
        if($this->error){return;}
        
        $this->set('title', 'Address Book');
        $this->set('contacts', ContactsData::viewAllContacts());
        
        $this->render('contacts/contacts_home.tpl');
    }
    
    /**
     * Contacts::index()
     * 
     * @return
     **/
    public function blocked()
    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            $this->error = true;
        }
        if($this->error){return;}
        
        $this->set('title', 'Blocked contacts');
        $this->set('contacts', ContactsData::viewBlockedContacts());
        
        $this->render('contacts/contacts_home.tpl');
    }
    
    /**
     * Contacts::view_contact($contact, $pilot)
     * This should be used when viewing a pilot in an address book
     * 
     * @contact int Id of the pilot to view in the address book
     * @pilot int (Optional)Id of the pilot who owns the address book.
     * 
     * @return bool true on success
     */
    public function view_contact($contact, $pilot = null)
    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            $this->error = true;
        }
        if($this->error){return;}
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : $pilot;
        $result = ContactsData::viewContact($contact, $pilot);
        
        if($result != null || !empty ($result)){
            $this->set('contact', $result);
            $this->render('contacts/view_contact.tpl');
            return true;
        }else{
            $this->set('message', 'There was getting the information for that contact.');
            $this->render('core_error.tpl');
            return false;
        }
    }
    
    public function add($contact, $pilot = null){
        return $this->add_contact($contact, $pilot);
    }
    
    public function remove($contact, $pilot = null){
        return $this->remove_contact($contact, $pilot);
    }
    
    public function block($contact, $pilot = null){
        return $this->block_contact($contact, $pilot);
    }
    
    public function unblock($contact, $pilot = null){
        return $this->unblock_contact($contact, $pilot);
    }
    
    /**
     * Contacts::add_contact($contact, $pilot)
     * This should be used in an action when adding a pilot to an address book
     * 
     * @contact int Id of the pilot to add to the address book
     * @pilot int (Optional)Id of the pilot to have the contact added to their book.
     * 
     * @return bool true on success
     */
    public function add_contact($contact, $pilot = null)
    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            $this->error = true;
        }
        if($this->error){return;}
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : $pilot;
        
        $result = ContactsData::addContact($contact, $pilot);
        
        if($result){
            $this->set('message', 'The pilot was added to your address book.');
            $this->render('core_success.tpl');
        }else{
            $this->set('message', 'There was an error while adding that contact.');
            $this->render('core_error.tpl');
        }
        return $this->index();
    }
    
    /**
     * Contacts::remove_contact($contact, $pilot)
     * This should be used in an action when removing a pilot from an address book
     * 
     * @contact int Id of the pilot to remove from an address book
     * @pilot int (Optional)Id of the pilot to have the contact removed from their book.
     * 
     * @return bool true on success
     */
    public function remove_contact($contact, $pilot = null)
    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            $this->error = true;
        }
        if($this->error){return;}
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : $pilot;
        
        $result = ContactsData::removeContact($contact, $pilot);
        
        if($result){
            $this->set('message', 'The pilot was removed from your address book.');
            $this->render('core_success.tpl');
        }else{
            $this->set('message', 'There was an error while removing that contact.');
            $this->render('core_error.tpl');
        }
        return $this->index();
    }
    
    /**
     * Contacts::block_contact($contact, $pilot)
     * This should be used in an action when blocking a pilot from an address book
     * 
     * @contact int Id of the pilot to blocked
     * @pilot int (Optional)Id of the pilot to have the contact blocked
     * 
     * @return bool true on success
     */
    public function block_contact($contact, $pilot = null)
    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            $this->error = true;
        }
        if($this->error){return;}
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : $pilot;
        
        $result = ContactsData::blockContact($contact, $pilot);
        
        if($result){
            $this->set('message', 'The pilot was blocked');
            $this->render('core_success.tpl');
        }else{
            $this->set('message', 'The pilot could not be blocked.');
            $this->render('core_error.tpl');
        }
        
        return $this->blocked();
    }
    
    /**
     * Contacts::unblock_contact($contact, $pilot)
     * This should be used in an action when unblocking a pilot from an address book
     * 
     * @contact int Id of the pilot to unblocked
     * @pilot int (Optional)Id of the pilot to have the contact unblocked
     * 
     * @return bool true on success
     */
    public function unblock_contact($contact, $pilot = null)
    {
        if(!Auth::LoggedIn()) {
            $this->set('message', 'You must be logged in to access this feature!');
            $this->render('core_error.tpl');
            $this->error = true;
        }
        if($this->error){return;}
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : $pilot;
        
        $result = ContactsData::unblockContact($contact, $pilot);
        
        if($result){
            $this->set('message', 'The pilot was unblocked');
            $this->render('core_success.tpl');
        }else{
            $this->set('message', 'The pilot could not be unblocked.');
            $this->render('core_error.tpl');
        }
        return $this->blocked();
    }
    
}
?>
