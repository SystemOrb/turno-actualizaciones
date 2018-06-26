<?php
//error_reporting(E_ALL);
 // error_reporting(0);
if($mes_num==""){ $mes_num = date("n"); }
if($anio==""){ $anio = date("Y"); }
$dayz = date("z", mktime(12,0,0,date("n"),date("d"),date("Y")));
?>
<?php
$sql = mysql_query("SELECT *, (SELECT em_nomfan FROM tu_emps WHERE em_id=pres.emp_id) empnom 
FROM tu_tmsp pres LEFT JOIN tu_emps_suc tsuc ON pres.suc_id=tsuc.suc_id WHERE tmsp_id='$presid'
&& tmsp_estado='ALTA' && tsuc.suc_estado='ALTA'",$conn);
$rows = mysql_num_rows($sql);
if($rows==0){
	echo "Actualmente El Prestador al que quiere ingresar no se encuentra disponible.";
	exit();	
}
$data = mysql_fetch_assoc($sql);
$sucid = $data['suc_id'];
$empid = $data['emp_id'];

//CONFIGURACION GENERAL DE LA EMPRESA
$empgcon = @mysql_query("select * from tu_emps_con where pres_id=0 && 
suc_id=0 && emp_id='$empid' ", $conn);
$datempgcon = @mysql_fetch_assoc($empgcon);
if($datempgcon['cf_bloqday']!=''){
	$set_bloqday = explode("-",$datempgcon['cf_bloqday']);
	$set_bqan = $set_bloqday[0];
	$set_bqme = $set_bloqday[1];
	$set_bqdi = $set_bloqday[2];
	$set_bloq = 1;
	$bloq_dayz = date("z", mktime(12,0,0,$set_bqme,$set_bqdi,$set_bqan));
}
if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
	//echo $set_bloq." aa<br>";
}	
//BUSCO CONFIGURACION
$gcon = mysql_query("select cf_turt, cf_simtu, cf_days, cf_daysp from tu_emps_con where pres_id='$presid' && suc_id='$sucid' && emp_id='$empid' order by pres_id", $conn);
$row = mysql_num_rows($gcon);
if($row == 0){
	$gcon = mysql_query("select cf_turt, cf_simtu, cf_days, cf_daysp 
	from tu_emps_con where pres_id=0 && 
	suc_id='$sucid' && emp_id='$empid' order by suc_id", $conn);
	$row = mysql_num_rows($gcon);
	if($row == 0){
		$gcon = mysql_query("select cf_turt, cf_simtu, cf_days, cf_daysp 
		from tu_emps_con where pres_id=0 && 
		suc_id=0 && emp_id='$empid' order by emp_id", $conn);
		$row = mysql_num_rows($gcon);
	}
}
$datcon = mysql_fetch_assoc($gcon);
$getmins = explode(":",$datcon['cf_turt']);
$hors = $getmins[0]*60;
$getmins = $hors+$getmins[1];
$simtu=$datcon['cf_simtu'];
$turn_dias = $datcon['cf_days'];
$turn_apartir = $datcon['cf_daysp'];
if($simtu<1){
	$simtu = 1;
}
if($turn_dias == 0 || $turn_dias  == ''){
	$turn_dias = 120;
}
if(($turn_apartir == 0 || $turn_apartir  == '') && $set_bloq != 1){
	$turn_apartir = 0;
}else if($turn_apartir > 0 && $set_bloq != 1){
	$set_bloq = 1;
	$bloq_dayz = date("z", mktime(12,0,0,date("n"),date("d")+$turn_apartir,date("Y")));
}
if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
	//echo $turn_apartir." ".$datcon['cf_daysp']." aa<br>";
}	

$verif_sev = @mysql_query("select serv_id, serv_nom from tu_emps_serv where serv_empid='$empid' && serv_presid='$presid' && serv_estado=1 && serv_tipo=1 ", $conn);
$servi = @mysql_num_rows($verif_sev);

$setemp = $data['empnom'];
	if($data['suc_nom']!='Unico'){
		$setemp .= " - ".$data['suc_nom'];
	}
	if($data['empnom'] == $data['suc_nom']){
		$setemp = $data['suc_nom'];
	}
	if($data['tmsp_pnom']!="Generico" && $data['tmsp_pnom']!="Unico"){
		$setemp .= " - ".$data['tmsp_pnom'];
	}
	$diasl = $data['tmsp_dias']; 
	if($diasl == ""){ $diasl = $data['suc_hor']; }
	
?><div class="blok">
<div class="infors"><div class="dets">
<div class="refer"><input type="hidden" name="set_serv_sel" id="set_serv_sel" />
<div class="setur"><div class="green">&nbsp;</div> 
<?php echo $cal_avd; ?></div>
<div class="senur"><div class="redu">&nbsp;</div> 
<?php echo $cal_navd; ?></div>
</div>
<div class="infpres"><?php echo utf8_encode($setemp); ?></div>
<div class="infpres2" data-geo-lat="<?php echo $data['suc_lat']; ?>" data-geo-long="<?php echo $data['suc_lng']; ?>"><?php echo utf8_encode($data['suc_dom']); ?> <?php echo $data['suc_domnum'] ; ?>
<?php
		//busco datos de ubiacion
		$getub = mysql_query("select loc_nom,(select prov_nom from tu_prov where
		prov_id=tlb.prov_id) prov from tu_locbar tlb where loc_id='$data[suc_locbar]' ", $conn);
		$datub = mysql_fetch_assoc($getub);
		
		if($data['suc_dompiso']){
			if($lang == "es/"){ echo " Piso ".$data['suc_dompiso'];
			}else if($lang == "en/"){ 
				$flor = $data['suc_dompiso'];
				if($flor == 'PB'){ $flor = "Ground"; }
				echo " ".$flor." Floor ";
			}else if($lang == "po/"){ 
				echo " ".$data['suc_dompiso']." Andar ";
			}
			
		}
		if($data['suc_dompnum']){
			echo " ".$data['suc_dompnum'];
		}
		echo " - ".utf8_encode($datub['loc_nom']).", ".utf8_encode($datub['prov']); 
?>
</div><?php
if($data['suc_hor']!=""){ ?>
<div class="infpres2"><?php echo $cal_at; ?>: <?php echo utf8_encode($diasl); ?></div><?php } ?></div></div>
<div class="titcal"> <?php echo $cal_av; ?></div>
<?php if($servi>0){ ?>
<div class="servis"><div class="selserv"> 
<select name="set_serv" id="set_serv" class="f_sel" onchange="addserv('<?php echo $emurl; ?>',this.value)">
<option value="">--<?php echo $cal_as; ?>--</option>
<?php
	$set_or = "serv_nom";
	$add_ln = "";
	if($lang != "es/"){
		$subln = substr($lang,0,2);
		$add_ln = ", serv_nom_".$subln;
		$set_or = "serv_nom_".$subln;
	}
$sevs = mysql_query("select serv_id, serv_nom ".$add_ln." from tu_emps_serv where serv_empid='$empid' && serv_sucid='$sucid' && serv_presid='$presid' && serv_estado=1 && serv_tipo=1 order by ".$set_or, $conn);
$rowserv = mysql_num_rows($sevs);
if($rowserv == 0){
	$sevs = mysql_query("select serv_id, serv_nom ".$add_ln." from tu_emps_serv 
	where serv_empid='$empid' && serv_sucid='$sucid' && serv_presid is NULL 
	&& serv_estado=1 && serv_tipo=1 order by ".$set_or, $conn);
	$rowserv = mysql_num_rows($sevs);
	if($rowserv == 0){
		$sevs = mysql_query("select serv_id, serv_nom ".$add_ln." from tu_emps_serv 
		where serv_empid='$empid' && serv_sucid is NULL && serv_presid is NULL && serv_estado=1 
		&& serv_tipo=1 order by ".$set_or, $conn);
		$rowserv = mysql_num_rows($sevs);
	}
}
while($datserv = mysql_fetch_assoc($sevs)){
	$servn = utf8_encode($datserv['serv_nom']);
	if($lang != "es/"){
		$sernf = utf8_encode($datserv['serv_nom_'.$subln]);
		if($sernf!=''){
			$servn = utf8_encode($datserv['serv_nom_'.$subln]);
		}
	}
?>
<option value="<?php echo $datserv['serv_id']; ?>"><?php echo $servn; ?></option>
<?php } ?>
</select>
</div>  
<div id="addeds"><span><?php echo $cal_nss; ?></span></div>
</div>
<?php } ?>
<div id="relocal">
<div class="lodcal"><?php include("lcal.php"); ?></div>
<?php 
$anio = date("Y", mktime(12,0,0,$mes_num+1,1,$anio));
$mes_num = date("n", mktime(12,0,0,$mes_num+1,1,$anio));    
?>
<div class="lodcal"><?php include("lcal.php"); ?></div>
<div class="naveg">
<div class="point"><div class="deg">></div>
<a href="<?php echo $rootdir; ?>e/<?php echo $lang; ?>cal/<?php echo $empid; ?>/<?php echo $sucid; ?>/<?php echo $presid; ?>/<?php echo $proxm; ?>/<?php echo $proxan; ?>/<?php echo $vcod; ?>" class="relcal"><?php echo $cal_nx; ?></a></div><?php if(($prevan==date("Y") && $prevm>=date("n")) || ($prevan>date("Y"))){ ?>
<div class="point2"><div class="deg2"><</div>
<a href="<?php echo $rootdir; ?>e/<?php echo $lang; ?>cal/<?php echo $empid; ?>/<?php echo $sucid; ?>/<?php echo $presid; ?>/<?php echo $prevm; ?>/<?php echo $prevan; ?>/<?php echo $vcod; ?>" class="relcal"><?php echo $cal_be; ?></a></div><?php } ?>
</div>
</div>
<div class="corte"></div>
</div>
<div id="lodinf"><div class="info"><b><?php echo $tu_fec; ?>: -</b></div><div class="txt"><?php echo $tu_ftxt; ?></div></div>
<?php

function Conecta() {
    return new PDO(PDO_HOSTNAME, PDO_USER, PDO_PASS);
    
}
function setPDOConfig($PDO_CONSTRUCTOR)
    {
        $obj = $PDO_CONSTRUCTOR;
        $obj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $obj->exec(PDO_CHAR);
    }
function selectDriver($condition,$bbdd, $PDO_port)
    {
        if($condition!=null){
           return $PDO_port->prepare("SELECT * FROM {$bbdd} WHERE {$condition}");
        }else{
           return $PDO_port->prepare("SELECT * FROM {$bbdd}");
            
            }       
    }
function scapeCharts($value)
    {
        return htmlentities(addslashes($value));
    }
function runDriver($sentence,$PDO_OBJECT)
   {
       if($sentence!=null)
       {
           $PDO_OBJECT->execute($sentence);
       }else{
           $PDO_OBJECT->execute();
       }
   }
function fetchDriver($PDO_OBJECTS)
   {
       return $PDO_OBJECTS->fetchAll(PDO::FETCH_OBJ);
   }?>