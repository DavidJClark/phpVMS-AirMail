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
 
class ContactsData extends CodonData {
    public static $error;
    
    public function __construct(){
        // check that airmail is installed.
        
    }
    
    /**
     * ContactsData::isAble($whoTo, $whoFrom)
     * Shorthand to ContactsData::checkCommunicationBetween($whoTo, $whoFrom)
     * 
     * @param type $whoTo
     * @param type $whoFrom
     * @return boolean
     */
    public static function isAble($whoTo, $whoFrom){
        return self::checkCommunicationBetween($whoTo, $whoFrom);
    }
    
    /**
     * ContactsData::checkCommunicationBetween($whoTo, $whoFrom)
     * Checks weather communication between two pilots is allowed by the pilots
     * themselves. Calling ContactsData::isAble() is a shorthand to this function.
     * 
     * @param int $whoTo
     * @param int $whoFrom
     * @return boolean
     */
    public static function checkCommunicationBetween($whoTo, $whoFrom){
        // Verify both pilots exists
        $contact = PilotData::getPilotData((int)$whoTo);
        $pilot = PilotData::getPilotData((int)$whoFrom);
        if($contact == null || empty($contact) || $pilot == null || empty($pilot)){
            self::$error = 'Pilot Doesnt exist!';
            return false;
        }else{
            // verify the users aren't blocked in either pilots address book.
            $blockCheck1 = self::getContact($whoTo, $whoFrom);
            if($blockCheck1 != null){
                if($blockCheck1->blocked == 'Y'){   // Verify that the contact 
                                                    // isn't blocked by pilot.
                    self::$error = 'The contact is blocked by the pilot.';
                    return false;
                }
            }
            
            $blockCheck2 = self::getContact($whoFrom, $whoTo);
            if($blockCheck2 != null){
                if($blockCheck2->blocked == 'Y'){   // Verify that the pilot 
                                                    // isn't blocked by contact.
                    self::$error = 'The pilot is blocked by the contact.';
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * ContactsData::viewAllContacts($pilot)
     * 
     * @param (Optional)int $pilot Pilot ID of the address book owner.
     * @return object
     * @throws Exception On database Failure.
     */
    public static function viewAllContacts($pilot = null){
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : $pilot;
        if($pilot != Auth::$userinfo->pilotid){
            self::$error = "Administration of this module has not been developed.";
            return false;
        }
        // Get all contacts in address book
        
        $query = "SELECT `contact`, `relation`, `blocked` ".
                "FROM `".TABLE_PREFIX."contacts` ".
                "WHERE `pilot`='$pilot' ".
                "AND `blocked`='N' ".
                "ORDER BY `contact` ASC";

        $results = DB::get_results($query);
        
        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }
        
        return $results;
    }
    
    /**
     * ContactsData::viewBlockedContacts($pilot)
     * 
     * @param (Optional)int $pilot Pilot ID of the address book owner.
     * @return object
     * @throws Exception On database Failure.
     */
    public static function viewBlockedContacts($pilot = null){
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : $pilot;
        if($pilot != Auth::$userinfo->pilotid){
            self::$error = "Administration of this module has not been developed.";
            return false;
        }
        // Get all contacts in black list
        
        $query = "SELECT `contact`, `relation`, `blocked` ".
                "FROM `".TABLE_PREFIX."contacts` ".
                "WHERE `pilot`='$pilot' ".
                "AND `blocked`='Y' ".
                "ORDER BY `contact` ASC";

        $results = DB::get_results($query);
        
        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }
        
        return $results;
    }
    
    /**
     * ContactsData::viewContact($contact, $pilot)
     * View the $contact in $pilot's address book.
     * 
     * @param int $contact Pilot ID of the contact in the address book
     * @param (Optional)int $pilot Pilot ID of the address book owner.
     * @return object
     */
    public static function viewContact($contact, $pilot = null){
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : (int)$pilot;
        $contact = (int)$contact;
        if(self::checkContact($contact, $pilot)){
            // Verify the contact is part of that address book.
            $result = self::getContact($contact, $pilot);
            
            if($result != null){
                // Get the contacts PilotData.
                $contactData = PilotData::getPilotData($contact);
            }
            
            // Add our relationship status to the pilot's data, and return.
            $contactData->relation = $result->relation;
            return $contactData;
        }
    }
    
    /**
     * ContactsData::addContact($contact, $pilot)
     * Add a contact to a pilot's address book.
     * 
     * @param int $contact Pilot ID of the contact to be added to the address book.
     * @param (Optional)int $pilot Pilot ID of the address book owner.
     * @return boolean True on success.
     * @throws Exception On database failure.
     */
    public static function addContact($contact, $pilot = null){
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : $pilot;
        if(self::checkContact($contact, $pilot)){
            $result = self::getContact($contact, $pilot);
            if($result == null){ // Verify that contact isn't already part of that address book.
                $blockCheck = self::getContact($pilot, $contact);
                if($blockCheck != null){ // Verify that the contact isn't blocked.
                    if($blockCheck->blocked == 'Y'){
                        self::$error = "That user has you blocked.";
                        return false; // The user is blocked.
                    }
                }
                
                $sql="INSERT INTO `".TABLE_PREFIX."contacts` (`pilot`, `contact`) VALUES ('$pilot', '$contact')";
                DB::query($sql);
                
                $code = DB::errno();
                if ($code != 0){
                    $message = DB::error();
                    throw new Exception($message, $code);
                    return false;
                }
                return true;
            }else{
                self::$error = "Remove the block on that contact first.";
            }
        }
        return false;
    }
    
    /**
     * ContactsData::removeContact($contact, $pilot)
     * Remove a contact from a pilot's address book.
     * 
     * @param int $contact Pilot ID of the contact to be added to the address book.
     * @param (Optional)int $pilot PilotID of the address book owner.
     * @return boolean True on success.
     * @throws Exception On database failure.
     */
    public static function removeContact($contact, $pilot = null, $unblocking = false){
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : $pilot;
        if(self::checkContact($contact, $pilot, $unblocking)){
            $result = self::getContact($contact, $pilot);
            if($result != null){ // Verify the contact is in that address book
                $sql = "DELETE FROM `".TABLE_PREFIX."contacts` WHERE `pilot`='$pilot' AND `contact`='$contact'";
                DB::query($sql);
                
                $code = DB::errno();
                if ($code != 0){
                    $message = DB::error();
                    throw new Exception($message, $code);
                    return false;
                }
                return true;
            }
        }
        return false;
    }
    
    /**
     * ContactsData::blockContact($contact, $pilot)
     * Blocks a user from contacting the $pilot.
     * 
     * @param int $contact
     * @param (Optional)int $pilot
     * @return boolean True on success.
     * @throws Exception On database failure.
     */
    public static function blockContact($contact, $pilot = null){
        $pilot = ($pilot == null) ? Auth::$userinfo->pilotid : $pilot;
        if(self::checkContact($contact, $pilot)){
            
            // check if the user has them as a contact, if so, remove it
            $check = self::getContact($pilot, $contact);
            if($check != null){
                self::removeContact($pilot, $contact, true);
            }
            
            $result = self::getContact($contact, $pilot);
            if($result == null){ // Verify that contact isn't already part of that address book.
                $sql="INSERT INTO `".TABLE_PREFIX."contacts` (`pilot`, `contact`, `blocked`) VALUES ('$pilot', '$contact', 'Y')";
                DB::query($sql);
                
                $code = DB::errno();
                if ($code != 0){
                    $message = DB::error();
                    throw new Exception($message, $code);
                    return false;
                }
                return true;
            }
            
            if($result->blocked == 'N'){
                $sql="UPDATE `".TABLE_PREFIX."contacts` SET `blocked`='Y' WHERE `pilot`='$pilot' AND `contact`='$contact'";
                
                DB::query($sql);
                
                $code = DB::errno();
                if ($code != 0){
                    $message = DB::error();
                    throw new Exception($message, $code);
                    return false;
                }
                return true;
            }
        }
        return false;
    }
    
    /**
     * ContactsData::unblockContact($contact, $pilot)
     * Removes a blocked contact.
     * 
     * @param int $contact
     * @param (Optional)int $pilot
     * @return boolean
     */
    public static function unblockContact($contact, $pilot = null){
        return self::removeContact($contact, $pilot, true);
    }
    
    public static function reportContacts(){
        throw new Exception("Planned before release of v1.0");
    }
    
    /**
     * self::checkContact()
     * Private function, verifies the requesting user is able to submit the action
     * and all parameters are valid.
     * 
     * @param int $contact
     * @param int $pilot
     * @return boolean
     */
    private static function checkContact($contact, $pilot, $omitAdmin = false){
        if($contact == $pilot){
            self::$error = "You cannot have yourself as a contact.";
            return false;
        }
        
        if(!$omitAdmin){
            if($pilot != Auth::$userinfo->pilotid){
                self::$error = "Administration of this module has not been developed.";
                return false;
            }
        }
        
        // Verify both pilots exists
        $contact = PilotData::getPilotData((int)$contact);
        $pilot = PilotData::getPilotData((int)$pilot);
        
        if($contact == null || empty($contact)){
            self::$error = 'Pilot Doesnt exist!';
            return false;
        }
        if($pilot == null || empty($pilot)){
            self::$error = 'Pilot Doesnt exist!';
            return false;
        }
        return true;
    }
    
    /**
     * self::getContact($contact, $pilot)
     * Retrieves the database entry where $contact is in $pilot's address book.
     * 
     * @param int $contact
     * @param int $pilot
     * @return object on success
     * @throws Exception On database failure.
     */
    public static function getContact($contact, $pilot){
        $query = "SELECT * ".
                "FROM `".TABLE_PREFIX."contacts` ".
                "WHERE `pilot`='$pilot' ".
                "AND `contact`='$contact'".
                //"AND `blocked`='N' ".
                "ORDER BY `contact` ASC";
        
        $result = DB::get_row($query);
        
        $code = DB::errno();
        if ($code != 0){
            $message = DB::error();
            throw new Exception($message, $code);
        }
        
        return $result;
    }
}
?>
