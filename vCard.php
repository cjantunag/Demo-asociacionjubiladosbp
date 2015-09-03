<?php
var $name = 'no_name';
function setTitle($title) { $this->setProperty("TITLE",$title ); }
function setORG($org, $dep){ $this->setProperty("ORG","$org;$dep" ); }
function setAddress($address, $city, $state,$postal, $country, $type) { $this->setProperty("ADR;$type",";$address;$city;$state;$postal;$country" ); }
function setName($first_name, $last_name, $prefix){
	$this->name = strtr($first_name.'_'.$last_name, ' ' , '_');
	$this->setProperty('N',$last_name.';'.$first_name.';'.$prefix );
	$this->setProperty('FN',"$prefix $first_name $last_name"); 
}
function setEmail($address){ $this->setProperty('EMAIL;INTERNET', $address); }
function setPhoneNumber( $number, $type){ $this->setProperty("TEL;$type", $number); }
function setBirthDate($date){ $this->setProperty('BDAY',$date); }
function getProperty($name){ 
	if(isset($this->properties[$name])) return $this->properties[$name]; 
	return null;
}
function setProperty($name, $value){ $this->properties[$name] = $value; }
function toString(){
    $temp = "BEGIN:VCARD\n";
    foreach($this->properties as $key=>$value){
	$temp .= $key. ':'. $value."\n";
    }
    $temp.= "END:VCARD\n";
    return $temp;
}
function saveVCard(){
	global $app_strings;
	$content = $this->toString();
	header("Content-Disposition: attachment; filename={$this->name}.vcf");
	header("Content-Type: text/x-vcard; charset={$app_strings['LBL_CHARSET']}");
	header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
	header( "Cache-Control: post-check=0, pre-check=0", false );
	header("Content-Length: ".strlen($content));
	print $content;
}
function importVCard($filename, $module='Contact'){
	global $current_user;
	$lines = file($filename);
	$start = false;
	require_once("modules/".$module."s/$module.php");
	$contact = new $module();
	$contact->title = 'imported';
	$contact->assigned_user_id = $current_user->id;
	$fullname = '';
	for($index = 0; $index < sizeof($lines); $index++){
		$line = $lines[$index];
		$line = trim($line);
		if($start){ //VCARD is done
			if(substr_count(strtoupper($line), 'END:VCARD')){
				if(!isset($contact->last_name)) $contact->last_name = $fullname;
				return $contact->save();
			}
			$keyvalue = split(':',$line);
			if(sizeof($keyvalue)==2) {
				$value = $keyvalue[1];
				for($newindex= $index + 1;  $newindex < sizeof($lines), substr_count($lines[$newindex], ':') == 0; $newindex++){
					$value .= $lines[$newindex];
					$index = $newindex;
				}
				$values = split(';',$value );
				$key = strtoupper($keyvalue[0]);
				$key = strtr($key, '=', '');
				$key = strtr($key, ',',';');
				$keys = split(';' ,$key);
				if($keys[0] == 'TEL'){
					if(substr_count($key, 'WORK') > 0){
						if(substr_count($key, 'FAX') > 0){
							if(!isset($contact->phone_fax)) $contact->phone_fax = $value;
						} else {
							if(!isset($contact->phone_work)) $contact->phone_work = $value;
						}
					}
					if(substr_count($key, 'HOME') > 0){
						if(substr_count($key, 'FAX') > 0){
							if(!isset($contact->phone_fax)) $contact->phone_fax = $value;
						} else {
							if(!isset($contact->phone_home)) $contact->phone_home = $value;
						}
					}
					if(substr_count($key, 'CELL') > 0){
						if(!isset($contact->phone_mobile)) $contact->phone_mobile = $value;
					}
					if(substr_count($key, 'FAX') > 0){
						if(!isset($contact->phone_fax)) $contact->phone_fax = $value;
					}
				}
				if($keys[0] == 'N'){
					if(sizeof($values) > 0) $contact->last_name = $values[0];
					if(sizeof($values) > 1)	$contact->first_name = $values[1];
				}
				if($keys[0] == 'FN') $fullname = $value;
				if($keys[0] == 'ADR'){
					if(substr_count($key, 'WORK') > 0 && (substr_count($key, 'POSTAL') > 0|| substr_count($key, 'PARCEL') == 0)){
							if(!isset($contact->primary_address_street) && sizeof($values) > 2) $contact->primary_address_street = $values[2];
							if(!isset($contact->primary_address_city) && sizeof($values) > 3) $contact->primary_address_city = $values[3];
							if(!isset($contact->primary_address_state) && sizeof($values) > 4) $contact->primary_address_state = $values[4];
							if(!isset($contact->primary_address_postalcode) && sizeof($values) > 5) $contact->primary_address_postalcode = $values[5];
							if(!isset($contact->primary_address_country) && sizeof($values) > 6) $contact->primary_address_country = $values[6];
					}
				}
				if($keys[0] == 'TITLE') $contact->title = $value;
				if($keys[0] == 'EMAIL'){
					if(!isset($contact->email1)) $contact->email1 = $value;
					else if(!isset($contact->email2)) $contact->email2 = $value;
				}
				if($keys[0] == 'ORG') if(sizeof($values) > 1)	$contact->department = $values[1];
			} 
		if(!$start && substr_count(strtoupper($line), 'BEGIN:VCARD')) $start = true;	//FOUND THE BEGINING OF THE VCARD
	}
	return $contact->save();
}
?>