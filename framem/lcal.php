<?php
include_once 'es/app/model/config.php';
$btclass = "preshr";
if($setcalod == 1){
	$btclass = "preshr2";
}
$hoyfe = date("Y-m-d");
$primer_dia=mktime(0,0,0,$mes_num,1,$anio);
$u_dia=mktime(12,0,0,$mes_num,1,$anio);
$dia1=date("D",$primer_dia);
$dias_cant = date("t",$u_dia);

$dias_mes = $dias_cant;
$letradia = date("w", mktime(12,0,0,$mes_num,1,$anio));
$empiezoen = 1 - ($letradia-1);
if($letradia==0){
	$empiezoen = -5;
}

$pru = ($dias_mes-$empiezoen)/7;
$prur = explode(".",$pru);
if($prur[1]>0){
	$lineas = $prur[0]+1;
}else{
	$lineas = $prur[0];
}
$tot = (6 * 7) + $empiezoen;

$total_estadia=0;
$est=1;


if($lang == "po/"){
	$getinmes = pomes($mes_num);
	$getitit = "Seleccione Data";
}else if($lang == "es/"){
	$getinmes = mes($mes_num);
	$getitit = "Ver Horarios Disponibles";
}else if($lang == "en/"){
	$getinmes = month($mes_num);
	$getitit = "Select Day";
}
		//busco dias de turnos
		$arraydab = array();
		$prdt = "select lab_dian from tu_dlab where lab_presid='$presid' 
		&& lab_sucid='$sucid' && lab_empid='$emurl' order by lab_dian";
		$ist = mysql_query($prdt,$conn);
		$rlab = @mysql_num_rows($ist);
		if($rlab == 0){
			$prdt = "select lab_dian from tu_dlab where lab_presid=0 
			&& lab_sucid='$sucid' && lab_empid='$emurl'	order by lab_dian";
			$ist = mysql_query($prdt,$conn);
			$rlab = @mysql_num_rows($ist);	
			if($rlab == 0){
				$prdt = "select lab_dian from tu_dlab where lab_presid=0 
				&& lab_sucid=0 && lab_empid='$emurl' order by lab_dian";
				$ist = mysql_query($prdt,$conn);
				$rlab = @mysql_num_rows($ist);	
			}
		}
		
		if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
			//echo $prdt."<br>";
		}
		if($rlab>0){
			while($rtlab = mysql_fetch_assoc($ist)){
				$arraydab[] = $rtlab['lab_dian'];
				if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
					//echo $rtlab['lab_dian']."<br>";
				}
			}			
		}
		//print_r($arraydab);
		
		//busco dias no laborables
		$arraynab = array();
		$istno = mysql_query("select fer_date from tu_emps_fer where fer_presid='$presid' 
		&& fer_sucid='$sucid' && fer_empid='$emurl' && fer_date>='$hoyfe' 
		group by fer_date order by fer_date",$conn);
		$rlabno = @mysql_num_rows($istno);		
		if($rlabno>0){
			while($rtlabno = mysql_fetch_assoc($istno)){
				$arraynab[] = $rtlabno['fer_date'];
				if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
					//echo $rtlabno['fer_date']."<br>";
				}
			}
		}
?>
<div id="s_cal">
<div class="s_day"><?php echo utf8_encode($getinmes).", ".$anio; ?></div>
<?php for($i=1;$i<8;$i++){ ?>          
<div class="s_sd"><?php echo setday($i,$lang); ?></div>
<?php // echo $i ?>
<?php } ?>   
<?php 
$count_dias=0;
$count = "-";

$lin = 1;
//$class = "cal_nodia";
// Empieza el for

for($i=$empiezoen;$i<$tot;$i++){ 
$class = "cal_nodia";
$text = "&nbsp;";
	if($i>0 && $i<$dias_mes+1){
		$class = "cal_dia";
		$presday = date("d/n/Y", mktime(12,0,0,$mes_num,$i,$anio));
		$presdia = date("d", mktime(12,0,0,$mes_num,$i,$anio));
		$presfecha = date("Y-m-d", mktime(12,0,0,$mes_num,$i,$anio));
		$dia_letra = date("w", mktime(12,0,0,$mes_num,$i,$anio));
		$dayz2 = date("z", mktime(12,0,0,$mes_num,$i,$anio));		
		if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
			//echo $presfecha."<br>";
		}	
		
		if($dayz2>=$dayz && $anio == date("Y")){
			$daytt = $dayz2-$dayz;			
		}else if($anio > date("Y")){
			$setand = $anio-date("Y");
			$dayz3 = (365*$setand)-$dayz;
			$daytt = $dayz3+$dayz2;			
		}else if($dayz2<$dayz){
			$dayz3 = 365-$dayz;
			$daytt = $dayz3+$dayz2;
		}
			$setnoclass = 0;
			if($daytt>$turn_dias){
				if( $i>0 && $i<$dias_mes+1 && $mes_num == date("n") && $i < date("d") && $anio == date("Y") && $pres!="adm" ){
					$class= "cal_dia";
					$setnoclass = 0;
				}else{
					//echo $daytt."<br>";
					$class= "cal_diaoc";
					$setnoclass = 1;
				}
			}
		if($set_bloq == 1 && $dayz2<$bloq_dayz){
			if( $i>0 && $i<$dias_mes+1 && $mes_num == date("n") && $i < date("d") && $anio == date("Y") && $pres!="adm" ){
				$class= "cal_dia";
				$setnoclass = 0;
			}else if( $i>0 && $i<$dias_mes+1 && ($anio > date("Y") || $mes_num > date("n")) && $pres!="adm" ){
				$class= "cal_dia";
				$setnoclass = 0;
				if($daytt>$turn_dias && $anio>date("Y")){
					$class= "cal_diaoc";
					$setnoclass = 1;                                      
				}
			}else{
				$class= "cal_diaoc";
				$setnoclass = 1;	
			}
		}
		
		if (in_array($dia_letra, $arraydab) && $setnoclass != 1){	
			if (in_array($presfecha, $arraynab)){	
				$class= "cal_nodia";
				$text = $i;
			}else{				
				$class= "cal_dia";		
				if($daytt>$turn_dias){
                                    // AQUI
					$class= "cal_diaoc";
				}		
				if ($pres=="adm"){
					$text = "<a href='".$rootdir."fs/load_times.php?mes_num=".$mes_num."&anio=".$anio."&eldia=".$presdia."&sucid=".$sucid."&presid=".$presid."&empid=".$empid."&dl=".$dia_letra."&dia=".$i."' id='dayid_$juno' title='$getitit: $presday' class='$btclass'>".$i."</a>";
				}else{		
					$text = "<a href='".$rootdir."e/".$lang."tur/".$empid."/".$sucid."/".$presid."/".$i."/".$mes_num."/".$anio."/".$vcod."' id='dayid_$juno' title='$getitit: $presday' class='$btclass'>".$i."</a>";
				}				
			}
		}else{
                    // AQUI
                    //echo $dia_letra;
                    // AQUI IDENTIFICAREMOS SI EXISTE O NO EL DOMINGO
			 $class= "cal_diaoc";
                        //$class = 'cal_nodianh';
                        $text = $i;                    
			// $text = "<span style='text-decoration:none'>".$i."</span>";	
		}
		$classtd = "tdid_".$juno;
	}
		$dia_com = 0;
		if($count!="-" && $count<$dias_mes+1 && $i>0 && $i<$dias_mes+1){
		    $count_dias++;
		  	$count++;	
		}
		  
		if($i==1){
		   	$primer_dia="";
		  	$count = 1;					
		}	
		if( $i>0 && $i<$dias_mes+1 && $mes_num == date("n") && $i < date("d") && $anio == date("Y") && $pres!="adm" ){
			$class = "cal_nodianh";
			$text = $i; 
		}		
		//$_SERVER['REMOTE_ADDR'] == '186.22.160.151' && 
		if($class == 'cal_dia'){
			include("verif_turdis.php");
			if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
					//echo "llego<br>";
				}			
		}
?>          
<div class="<?php echo $class; ?>" id="<?php echo $classtd; ?>"><?php 
echo $text; 
//echo $class." ".$classtd; 
?></div>
<?php 
	if($i>0 && $i<$dias_mes+1){
		$juno++;
	}
	if($lin == 7 && $i<$tot-1){
		//echo "</tr><tr>";
		$lin = 0;
	}
	$lin++;
} 
?>    
<?php
$prevm = date("n", mktime(12,0,0,$mes_num-2,1,$anio));
$proxm = date("n", mktime(12,0,0,$mes_num,1,$anio));
$prevan = date("Y", mktime(12,0,0,$mes_num-2,1,$anio));
$proxan = date("Y", mktime(12,0,0,$mes_num,1,$anio));
//echo $mes_num." ".$anio." - ".$prevm." ".$proxm;
?>
</div>

