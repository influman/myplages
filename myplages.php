<?php
	
$xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";      
	//***********************************************************************************************************************
	// V1.0 : Script qui permet de déterminer si une plage horaire est en cours
	// V1.1 : Chevauchement sur 2 jours intégré
	//*********************************************************************************************************
	
	// recuperation des infos depuis la requete
	// jours : Tous, Pairs, Impairs, Lun>>Ven, Lun, Mar, Mer, Jeu, Ven, Sam, Dim, Sam-Dim, Mar-Jeu, Mer-Ven, Lun-Jeu, Lun-Ven, Lun-Mer
$jours = getArg("jour", $mandatory = true, $default = 'Tous'); //  pour le multilingue

  	// heure de début
$debut = getArg("deb", $mandatory = true, $default = '00:00');
  	// heure de fin
$fin = getArg("fin", $mandatory = true, $default = '00:00');
  
list($he, $mi) = sscanf($debut, "%d:%d");
$heuredeb = mktime($he, $mi, 0, date("m"), date("d"), date("Y"));
list($he, $mi) = sscanf($fin, "%d:%d");
$heurefin = mktime($he, $mi, 0, date("m"), date("d"), date("Y"));

$maintenant = time();
$jourpair = $cejourdec % 2 == 0 ;
$plage = 0;
//$plageok=false;

if ($heurefin<=$heuredeb) 
{
	$cejournum = date("N",mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
	$cejourdec = date("j",mktime(0, 0, 0, date("m"), date("d")-1, date("Y")));
	//If ($maintenant >= $heuredeb || $maintenant <= $heurefin) 
	//{
	//	$plageok=true;
	//}
	$plageok=($maintenant >= $heuredeb || $maintenant <= $heurefin);
}else
{
	$cejournum = date("N");
	$cejourdec = date("j");
	//If ($maintenant >= $heuredeb && $maintenant <= $heurefin) 
	//{
	//	$plageok=true;
	//}
	$plageok=($maintenant >= $heuredeb && $maintenant <= $heurefin);
}
  


	// vérification si dans la plage horaire
If ($plageok) 
{
	// Si Tous les jours
	If ($jours == 'Tous') //  pour le multilingue
	{
		$plage = 1;
	} 
	else 
	{
	// vérification si pair ou impair
	if ($jourpair && $jours == 'Pairs') 
	{
		$plage = 1;
	}
			
	if (!$jourpair && $jours == 'Impairs') 
	{
		$plage = 1;
	}
	// vérification jour de la semaine
	switch($cejournum)
	{
		case 1:
			if ($jours == 'Lun' || $jours == 'Lun>>Ven' || $jours == 'Lun-Mar' || $jours == 'Lun-Mer' || $jours == 'Lun-Jeu' || $jours == 'Lun-Ven' || $jours == 'Lun-Sam' || $jours == 'Lun-Dim') 
			{ 
				$plage=1;
			}
			break;
		case 2:
			if ($jours == 'Mar' || $jours == 'Lun>>Ven' || $jours == 'Lun-Mar' || $jours == 'Mar-Mer' || $jours == 'Mar-Jeu' || $jours == 'Mar-Ven' || $jours == 'Mar-Sam' || $jours == 'Mar-Dim') 
			{ 
				$plage=1;
			}
			break;
		case 3:
			if ($jours == 'Mer' || $jours == 'Lun>>Ven' || $jours == 'Lun-Mer' || $jours == 'Mar-Mer' || $jours == 'Mer-Jeu' || $jours == 'Mer-Ven' || $jours == 'Mer-Sam' || $jours == 'Mer-Dim') 
			{ 
				$plage=1;
			}
			break;
		case 4:
			if ($jours == 'Jeu' || $jours == 'Lun>>Ven' || $jours == 'Lun-Jeu' || $jours == 'Mar-Jeu' || $jours == 'Mer-Jeu' || $jours == 'Jeu-Ven' || $jours == 'Jeu-Sam' || $jours == 'Jeu-Dim') 
			{ 
				$plage=1;
			}
			break;
		case 5:
			if ($jours == 'Ven' || $jours == 'Lun>>Ven' || $jours == 'Lun-Ven' || $jours == 'Mar-Ven' || $jours == 'Mer-Ven' || $jours == 'Jeu-Ven' || $jours == 'Ven-Sam' || $jours == 'Ven-Dim') 
			{ 
				$plage=1;
			}
			break;
		case 6:
			if ($jours == 'Sam' || $jours == 'Sam-Dim' || $jours == 'Lun-Sam' || $jours == 'Mar-Sam' || $jours == 'Mer-Sam' || $jours == 'Jeu-Sam' || $jours == 'Ven-Sam') 
			{ 
				$plage=1;
			}
			break;
		case 7:
			if ($jours == 'Dim' || $jours == 'Sam-Dim' || $jours == 'Lun-Dim' || $jours == 'Mar-Dim' || $jours == 'Mer-Dim' || $jours == 'Jeu-Dim' || $jours == 'Ven-Dim') 
			{ 
				$plage=1;
			}
			break;
		}
	}
}


$xml="<PLAGES>";
$xml .= "<DEBUT>".$debut."</DEBUT>";
$xml .= "<FIN>".$fin."</FIN>";
$xml .= "<JOURS>".$jours."</JOURS>";
$xml .= "<DATE>".date('l j H:i:s')."</DATE>";
$xml .= "<RESULTAT>".$plage."</RESULTAT>";
$xml .= "</PLAGES>";
sdk_header('text/xml');
echo $xml;
?>