<?php
//$dia = new Calendario();
$dl = date("w", mktime(12,0,0,$mes_num,$i,$anio));
$setsql = "select lab_hin, lab_hou, lab_hin2, lab_hou2 from tu_dlab where lab_presid='$presid' && lab_sucid='$sucid' && lab_empid='$empid' && lab_dian='$dl' 
order by lab_presid ";
$sqlgetin = mysql_query($setsql ,$conn);
$rowgett = mysql_num_rows($sqlgetin);
if($rowgett == 0){
	$setsql = "select lab_hin, lab_hou, lab_hin2, lab_hou2 from tu_dlab
	where lab_presid=0 && lab_sucid='$sucid' && lab_empid='$empid' && lab_dian='$dl' 
	order by lab_presid ";
	$sqlgetin = mysql_query($setsql, $conn);
	$rowgett = mysql_num_rows($sqlgetin);
	if($rowgett == 0){
		 $setsql = "select lab_hin, lab_hou, lab_hin2, lab_hou2 from tu_dlab
		 where lab_presid=0 && lab_sucid=0 && lab_empid='$empid' && lab_dian='$dl' 
		 order by lab_presid ";
		 $sqlgetin = mysql_query($setsql, $conn);
	}
}
if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
//echo $setsql."<br>";
}
$rowst = mysql_num_rows($sqlgetin);
$data = mysql_fetch_assoc($sqlgetin);
$labin = $data['lab_hin'];
$exdin = explode(":",$labin);
$labou = $data['lab_hou'];
$exdon = explode(":",$labou);
$labou = @date("H:i", mktime($exdon[0],$exdon[1],0,$mes_num,$i,$anio));
$labin = @date("H:i", mktime($exdin[0],$exdin[1],0,$mes_num,$i,$anio));
$minex = $getmins;
$def_minex = $minex;
$j = 0;

if($labou=='00:00' && $labin!='00:00'){
	$labou = '24:00';
}

$i8 = $i;
if($i8<10){ $i8="0".$i; }
$mes_num4 = $mes_num;
if($mes_num<10){ $mes_num4 = "0".$mes_num; }
$fec=$anio."-".$mes_num4."-".$i8;
if($rowst>0){
	$hay_turno = 0;
	while($labin<$labou){
		$setdif = ($minex)-1;
		if($set_cantdif>0 && $set_cantdif>$getmins){
			$setdif = ($minex+($set_cantdif-$getmins))-1;
		}
		$set_newminex = 0;
		$setnewtime = 0;
		$labinnt = '';
		//busco tiempo si hay turno entre horario del turno
		
		$setime2 = date("H:i", mktime($exdin[0],$exdin[1]+$setdif,0,$mes_num4,$dia,$anio));
		if($setime2>$labou){
			break;
		}
		$sqlth=mysql_query("select tu_id, tu_hora, tu_durac from tu_turnos where pres_id='$presid' 
		&& tu_fec='$fec' && tu_hora BETWEEN '$labin' AND '$setime2' && tu_estado!='BAJA' 
		&& tu_st=0 order by tu_hora ",$conn);
		$nh2=mysql_num_rows($sqlth);
			if($nh2>=$simtu) {
				$set_newminex = $def_minex;
				while($datiu = mysql_fetch_assoc($sqlth)){
					$max_time = $datiu['tu_durac'];
					$set_horamt = explode(":",$max_time);
					$set_horamt2 = $set_horamt[0]*60;
					$max_time = $set_horamt2+$set_horamt[1];
					
					//defino hora inicio
					$settur_hora = explode(":",$datiu['tu_hora']);
					
					if($max_time>$getmins){
						$set_horat = explode(":",$datiu['tu_durac']);
						$set_horat2 = $set_horat[0]*60;
						$set_newminex = $set_horat2+$set_horat[1];
						$setnewtime = 1;
						$set_turdif = ceil($set_newminex / $getmins);
						$set_turdif = $set_turdif*$getmins;
						$minex+= $set_turdif;
					}
					if($set_turdifs2 !=''){	
			$labinnt = date("H:i", mktime($settur_hora[0],$settur_hora[1]+$set_turdif,0,$mes_num4,$dia,$anio));
					}
				}
			} else {
				$showsi = 1;
				if($hoysi==1 && $labin<$hoy_hora){
					$showsi = 2;
				}
				if($showsi==1){
					$hay_turno = 1;
					/*if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
						echo $hay_turno;
					}*/
					break;
				}
			}
			$set_addtime = $minex;	
			if($setnewtime == 1){
				$set_newminex = $set_newminex-$getmins;	
				$minex+= $set_cantdif;
				$set_addtime = $minex+$set_cantdif;	
			}
		
		$labin = date("H:i", mktime($exdin[0],$exdin[1]+$set_addtime,0,$mes_num4,$dia,$anio));
		if($labinnt!=''){
			$labin = $labinnt;
		}
		$minex+=$getmins;
		$j++;	
		if($j>53){
			exit();
		}
	}
	if($data['lab_hin2']!='00:00:00' && $data['lab_hin2']!=""){//si tiene horario 2
			$labin = $data['lab_hin2'];
			$exdin = explode(":",$labin);
			$labou = $data['lab_hou2'];
			$exdon = explode(":",$labou);
			$labou = date("H:i", mktime($exdon[0],$exdon[1],0,$mes_num4,$dia,$anio));
			$labin = date("H:i", mktime($exdin[0],$exdin[1],0,$mes_num4,$dia,$anio));
			$minex = $getmins;
	
		while($labin<$labou){
			
			$setdif = ($minex)-1;
			if($set_cantdif>0 && $set_cantdif>$getmins){
				$setdif = ($minex+($set_cantdif-$getmins))-1;
			}
		
			$set_newminex = 0;
			$setnewtime = 0;
			$labinnt = '';
			
		   $setime2 = date("H:i", mktime($exdin[0],$exdin[1]+$setdif,0,$mes_num4,$dia,$anio));
		    if($setime2>$labou){
				break;
			}
		$sqlth=mysql_query("select tu_id, tu_hora, tu_durac from tu_turnos where pres_id='$presid' 
		&& tu_fec='$fec' && tu_hora BETWEEN '$labin' AND '$setime2' && tu_estado!='BAJA' 
		&& tu_st=0 order by tu_hora ",$conn);
		$nh2=mysql_num_rows($sqlth);
		
			if($nh2>=$simtu) {
				$set_newminex = $def_minex;
				while($datiu = mysql_fetch_assoc($sqlth)){
					$max_time = $datiu['tu_durac'];
					$set_horamt = explode(":",$max_time);
					$set_horamt2 = $set_horamt[0]*60;
					$max_time = $set_horamt2+$set_horamt[1];
					
					//defino hora inicio
					$settur_hora = explode(":",$datiu['tu_hora']);
					
					if($max_time>$getmins){
						$set_horat = explode(":",$datiu['tu_durac']);
						$set_horat2 = $set_horat[0]*60;
						$set_newminex = $set_horat2+$set_horat[1];
						$setnewtime = 1;
						$set_turdif = ceil($set_newminex / $getmins);
						$set_turdif = $set_turdif*$getmins;
						$minex+= $set_turdif;
					}
					if($set_turdifs2 !=''){	
			$labinnt = date("H:i", mktime($settur_hora[0],$settur_hora[1]+$set_turdif,0,$mes_num4,$dia,$anio));
					}
				}
			}else{				
				$showsi = 1;
				if($hoysi==1 && $labin<$hoy_hora){
					$showsi = 2;
				}
				if($showsi==1){
					$hay_turno = 1;
					break;
				}
			}
			$set_addtime = $minex;	
			if($setnewtime == 1){
				$set_newminex = $set_newminex-$getmins;	
				$minex+= $set_cantdif;
				$set_addtime = $minex+$set_cantdif;	
			}	
				
			$labin = date("H:i", mktime($exdin[0],$exdin[1]+$set_addtime,0,$mes_num4,$dia,$anio));
			if($labinnt!=''){
				$labin = $labinnt;
			}
			$minex+=$getmins;
			$j++;	
			if($j>90){
				exit();
			}	
		}
	}//---fin si tiene horario 2
	if($hay_turno !=1){
		$class= "cal_diaoc";
		$text = $i;	
		//echo "no quedan turnos disponibles para la fecha seleccionada.";
	}
	//echo "<br>".$hay_turno."<br>";
}
?>