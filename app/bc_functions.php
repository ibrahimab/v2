<?php

function txt($id,$page="",$settings=array(""),$html=false) {

    $taal = $GLOBALS['taal'];
    $txt  = $GLOBALS['txt'];
    $txta = $GLOBALS['txta'];
    $websitetype = $GLOBALS['website']['type'];
    $website = $GLOBALS['website']['website'];
    $websiteland = $GLOBALS['website']['country'];

	if($page) {
		if(!isset($txt["nl"][$page][$id])) {
			trigger_error("txt[nl][".$page."][".$id."] niet beschikbaar",E_USER_NOTICE);
		}
		if($websitetype==5 and isset($txt[$taal."_z_t"][$page][$id])) {
			# Chalettour zomer (niet in gebruik)
			$return=$txt[$taal."_z_t"][$page][$id];
		} elseif($websitetype==9 and $website=="Y" and isset($txt[$taal."_y"][$page][$id])) {
			# Venturasol Vacances
			$return=$txt[$taal."_y"][$page][$id];
		} elseif($websitetype==8 and isset($txt[$taal."_w"][$page][$id])) {
			# SuperSki
			$return=$txt[$taal."_w"][$page][$id];
		} elseif($websitetype==7 and isset($txt[$taal."_i"][$page][$id])) {
			# Italissima
			$return=$txt[$taal."_i"][$page][$id];
		} elseif(($websitetype==3 or $websitetype==5 or $websitetype==7) and isset($txt[$taal."_z"][$page][$id])) {
			# Zomerhuisje.nl / Italissima
			$return=$txt[$taal."_z"][$page][$id];
		} elseif(($websitetype==4 or $websitetype==5) and isset($txt[$taal."_t"][$page][$id])) {
			# Chalettour.nl
			$return=$txt[$taal."_t"][$page][$id];
		} elseif($websitetype==6 and isset($txt[$taal."_v"][$page][$id])) {
			# Chalets in Vallandry
			$return=$txt[$taal."_v"][$page][$id];
		} elseif($websiteland=="be" and isset($txt[$taal."_b"][$page][$id])) {
			# Belgie
			$return=$txt[$taal."_b"][$page][$id];
		} else {
			$return=$txt[$taal][$page][$id];
		}
	} else {
		if(!isset($txta[$taal][$id])) {
			trigger_error("txta[".$taal."][".$id."] niet beschikbaar",E_USER_NOTICE);
		}
		if($websitetype==5 and isset($txta[$taal."_z_t"][$id])) {
			# Chalettour zomer (niet in gebruik)
			$return=$txta[$taal."_z_t"][$id];
		} elseif($websitetype==9 and $website=="Y" and isset($txta[$taal."_y"][$id])) {
			# Venturasol
			$return=$txta[$taal."_y"][$id];
		} elseif($websitetype==8 and isset($txta[$taal."_w"][$id])) {
			# SuperSki
			$return=$txta[$taal."_w"][$id];
		} elseif($websitetype==7 and isset($txta[$taal."_i"][$id])) {
			# Italissima
			$return=$txta[$taal."_i"][$id];
		} elseif(($websitetype==3 or $websitetype==5 or $websitetype==7) and isset($txta[$taal."_z"][$id])) {
			# Zomerhuisje.nl / Italissima
			$return=$txta[$taal."_z"][$id];
		} elseif(($websitetype==4 or $websitetype==5) and isset($txta[$taal."_t"][$id])) {
			# Chalettour.nl
			$return=$txta[$taal."_t"][$id];
		} elseif($websitetype==6 and isset($txta[$taal."_v"][$id])) {
			# Chalets in Vallandry NL
			$return=$txta[$taal."_v"][$id];
		} elseif($websiteland=="be" and isset($txta[$taal."_b"][$id])) {
			# Belgie
			$return=$txta[$taal."_b"][$id];
		} else {
			$return=$txta[$taal][$id];
		}
	}
	if($return=="") {
		if($taal=="nl")	trigger_error("txt[nl][".$page."][".$id."] is leeg",E_USER_NOTICE);
#		$return="[[MISSING: ".$id."_".$taal."]]";
		if($vars["lokale_testserver"]) {
			$return="-".preg_replace("@_@", " ", $id)."-";
			if(is_array($settings)) {
				foreach ($settings as $key => $value) {
					if(preg_match("@^v_@", $key)) {
						$return .= " [[".$key."]]";
					}
				}
			}
		} else {
			$return="-";
		}
	}
	if($html) {
		$return=wt_he($return);
	}
	if(is_array($settings)) {
		while(list($key,$value)=each($settings)) {
		$value=strval($value);
			if(preg_match("@^(l[0-9]+)$@",$key,$regs)) {
				# Links
				if(substr($value,0,1)=="#") {
					$return=ereg_replace("\[\[".$regs[1]."\]\]","<a href=\"".$value."\">",$return);
				} else {
					# Wat voor soort link?
					if(ereg("^javascript",$value,$regs2))  {
						# Javascript
						$link=$value;
					} else {

						# Wel of geen aname
						if(ereg("(.*)#(.*)",$value,$regs2)) {
							$value=$regs2[1];
							$aname="#".$regs2[2];
						} else {
							$aname="";
						}

						# Wel of geen query-string
						if(ereg("(.*)\?(.*)",$value,$regs2)) {
							$value=$regs2[1];
							$qs="?".$regs2[2];
						} else {
							$qs="";
						}
						if(ereg("^http_(.*)",$value,$regs2))  {
							# Met volledag pad ervoor
							$link=$vars["basehref"].txt("menu_".$regs2[1]).".php".$qs.$aname;
						} else {
							# Gewone interne link
							$link=$path.txt("menu_".$value).".php".$qs.$aname;
						}
					}
					$return=ereg_replace("\[\[".$regs[1]."\]\]","<a href=\"".$link."\">",$return);
				}
				$return=ereg_replace("\[\[/".$regs[1]."\]\]","</a>",$return);
			} elseif(preg_match("@^(v_[a-z0-9]+)$@",$key,$regs)) {
				# Vars
				$return=ereg_replace("\[\[".$regs[1]."\]\]",($html ? wt_he($value) : $value),$return);
			} elseif(preg_match("@^(h_[a-z0-9]+)$@",$key,$regs)) {
				# HTML
				$return=preg_replace("@\[\[".$regs[1]."\]\]@",$value,$return);
			}
		}
	}

	// Solution for German grammatical case (naamval): in die/der Schweiz
	if($taal=="de") {
		if(preg_match("@\bin die Schweiz\b@", $return)) {
			$return = preg_replace("@\bin die Schweiz\b@", "in der Schweiz", $return);
		}
	}

	return $return;
}

function html($id,$page="",$settings="") {
	return txt($id,$page,$settings,true);
}

function wt_he($text) {
	//
	// htmlentities with correct character encoding
	//
	global $vars;
	if(is_array($text)) {
		return false;
	} else {
		if($vars["wt_htmlentities_cp1252"]) {
			$text=htmlentities($text,ENT_COMPAT,'cp1252');
		} elseif($vars["wt_htmlentities_utf8"]) {
			$text=htmlentities($text,ENT_COMPAT,'UTF-8');
		} else {
			$text=htmlentities($text);
		}
		return $text;
	}
}