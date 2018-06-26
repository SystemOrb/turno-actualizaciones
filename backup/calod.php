<?php
error_reporting(0);
if($mes_num==""){ $mes_num = date("n"); } 
if($anio==""){ $anio = date("Y"); } 
$dayz = date("z", mktime(12,0,0,date("n"),date("d"),date("Y"))); 
$setcalod = 1;
?><script>
$(document).ready(function(){
	// CARGA HORARIOS DEL DIA
	$(".preshr2").click(function(evento){
		evento.preventDefault();
		var reff = $(this).attr('href');
		var rand_no = Math.random();
		rand_no = rand_no.toFixed(4);		
		//e/".$lang."tur/".$empid."/".$sucid."/".$presid."/".$i."/".$mes_num."/".$anio."/".$vcod			
		var res = reff.split("/");
		var lang = res[4]+"/";
		var emid = res[6];
		var suid = res[7];
		var peid = res[8];
		var sdia = res[9];
		var smes = res[10];
		var sani = res[11];
		var ecod = res[12];
		if($('#set_serv').length){
			var servis = $('#set_serv_sel').val();
			if(servis == ""){
				alert("debe seleccionar un servicio para poder continuar!");
				return false;
			}else{
				reff += "/serv/"+servis;
			}
		}else{
			var servis = '';
		}
		//$('#lodinf').html('<p><img src="http://www.turnonet.com/frame/imagenes/loader.gif" width="16" height="16" class="img_b" /></p>');
		
		/*$("#lodinf").load(reff,function(){			
			$('#lodinf').show('slide', { direction: "up" },400);
		});*/		//framem/load_times.php?pag=$9calod&emurl=$1&sucid=$2&presid=$3&dia=$4&mes_num=$5&anio=$6&iserv=$7&l=es/ 
		<?php if($_SERVER['REMOTE_ADDR'] == '186.18.104.207' || $_SERVER['REMOTE_ADDR'] == '190.113.232.2'){ ?>
		//alert("pag:'calod'"+emid+" "+suid+" "+peid+" "+sdia+",mes_num:"+smes+",anio:"+sani+",iserv:"+servis+",l:"+lang+",ecod:"+ecod+",r:"+rand_no);
		<?php } ?>
		$.ajax({ 
			data: {pag:'calod',emurl:emid,sucid:suid,presid:peid,dia:sdia,mes_num:smes,anio:sani,iserv:servis,l:lang,ecod:ecod,r:rand_no}, 
			type: 'POST', 
			url: reff, 
			beforeSend: function(){
				$('#lodinf').html('<p><img src="http://www.turnonet.com/frame/imagenes/loader.gif" width="16" height="16" class="img_b" /></p>');
			},
			success: function(response) { 
				$('#lodinf').html(response); 
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
		return false;
	});
});	
</script><?php
include_once ("../js/func.php");
include_once ("../js/cambiocaracteres.php"); 
include_once ("../js/globalinc.php"); 

$rootdir = "http://www.turnonet.com/";
$framdir = "framem/";

include_once("../js/class.php"); 
$empid = $emurl; 
$tipc = TNet::tipus($empid, 'empid');
if($tipc == " | DEMO "){ $conn = TNet::conectarD(); }else{ $conn = TNet::conectar(); }

$lang = "es/";
if($l!=''){ $lang = $l; }
include_once("js/langcode.php");

$sql = mysql_query("select em_valcod from tu_emps e where em_id='$empid' ",$conn);
$data = mysql_fetch_assoc($sql);
$vcod = substr($data['em_valcod'],0,4);
if($vcod != $ecod){
	echo $langnoentry;
	exit();
}

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
?>
<script>
	// PASA DE MES EL CALENDARIO
	$(".relcal2").click(function(evento){
		evento.preventDefault();	
		var reff = $(this).attr('href');
		var rand_no = Math.random();
		rand_no = rand_no.toFixed(4);
		var res = reff.split("/");
		var lang = res[4]+"/";
		var emid = res[6];
		var suid = res[7];
		var peid = res[8];
		var prme = res[9];
		var pran = res[10];
		var ecod = res[11];	
		<?php if($_SERVER['REMOTE_ADDR'] == '186.18.104.207' || $_SERVER['REMOTE_ADDR'] == '190.113.232.2'){ ?>
		//alert("pag:'calod',emurl:"+emid+",sucid:"+suid+",presid:"+peid+",mes_num:"+prme+",anio:"+pran+",l:"+lang+",ecod:"+ecod+",r:"+rand_no);
		<?php } ?>
		$.ajax({ 
			data: {pag:'calod',emurl:emid,sucid:suid,presid:peid,mes_num:prme,anio:pran,l:lang,ecod:ecod,r:rand_no}, 
			type: 'POST', 
			url: reff, 
			beforeSend: function(){
				$('#relocal').html('<div class="load"><img src="http://www.turnonet.com/frame/imagenes/loader.gif" width="16" height="16" class="img_b" /></div>');
			},
			success: function(response) { 
				$('#relocal').html(response); 
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});	
		/*$('#relocal').html('<p><img src="http://www.turnonet.com/frame/imagenes/loader.gif" width="16" height="16" class="img_b" /></p>');	
		$("#relocal").load($(this).attr('href'));*/
		return false;
	});
	// CARGA HORARIOS DEL DIA
	$(".preshr").click(function(evento){
		evento.preventDefault();		
		/*$('#lodinf').html('<p><img src="http://www.turnonet.com/frame/imagenes/loader.gif" width="16" height="16" class="img_b" /></p>');
		$("#lodinf").load($(this).attr('href'),function(){			
			$('#lodinf').show('slide', { direction: "up" },400);
		});*/
		evento.preventDefault();
		var reff = $(this).attr('href');
		var rand_no = Math.random();
		rand_no = rand_no.toFixed(4);		
		//e/".$lang."tur/".$empid."/".$sucid."/".$presid."/".$i."/".$mes_num."/".$anio."/".$vcod			
		var res = reff.split("/");
		var lang = res[4]+"/";
		var emid = res[6];
		var suid = res[7];
		var peid = res[8];
		var sdia = res[9];
		var smes = res[10];
		var sani = res[11];
		var ecod = res[12];
		if($('#set_serv').length){
			var servis = $('#set_serv_sel').val();
			if(servis == ""){
				alert("debe seleccionar un servicio para poder continuar!");
				return false;
			}else{
				reff += "/serv/"+servis;
			}
		}else{
			var servis = '';
		}
		//$('#lodinf').html('<p><img src="http://www.turnonet.com/frame/imagenes/loader.gif" width="16" height="16" class="img_b" /></p>');
		
		/*$("#lodinf").load(reff,function(){			
			$('#lodinf').show('slide', { direction: "up" },400);
		});*/		//framem/load_times.php?pag=$9calod&emurl=$1&sucid=$2&presid=$3&dia=$4&mes_num=$5&anio=$6&iserv=$7&l=es/ 
		<?php if($_SERVER['REMOTE_ADDR'] == '186.18.104.207' || $_SERVER['REMOTE_ADDR'] == '190.113.232.2'){ ?>
		//alert("pag:'calod'"+emid+" "+suid+" "+peid+" "+sdia+",mes_num:"+smes+",anio:"+sani+",iserv:"+servis+",l:"+lang+",ecod:"+ecod+",r:"+rand_no);
		<?php } ?>
		$.ajax({ 
			data: {pag:'calod',emurl:emid,sucid:suid,presid:peid,dia:sdia,mes_num:smes,anio:sani,iserv:servis,l:lang,ecod:ecod,r:rand_no}, 
			type: 'POST', 
			url: reff, 
			beforeSend: function(){
				$('#lodinf').html('<p><img src="http://www.turnonet.com/frame/imagenes/loader.gif" width="16" height="16" class="img_b" /></p>');
			},
			success: function(response) { 
				$('#lodinf').html(response); 
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
		return false;
	});
</script>
<div class="lodcal"><?php include("lcal.php"); ?></div>
<?php 
$anio = date("Y", mktime(12,0,0,$mes_num+1,1,$anio)); 
$mes_num = date("n", mktime(12,0,0,$mes_num+1,1,$anio)); 
?>
<div class="lodcal"><?php include("lcal.php"); ?></div>
<div class="naveg">
<div class="point"><div class="deg">></div>
<a href="<?php echo $rootdir; ?>e/<?php echo $lang; ?>cal/<?php echo $empid; ?>/<?php echo $sucid; ?>/<?php echo $presid; ?>/<?php echo $proxm; ?>/<?php echo $proxan; ?>/<?php echo $vcod; ?>" class="relcal2"><?php echo $cal_nx; ?></a></div><?php if(($prevan==date("Y") && $prevm>=date("n")) || ($prevan>date("Y"))){ ?>
<div class="point2"><div class="deg2"><</div><a href="<?php echo $rootdir; ?>e/<?php echo $lang; ?>cal/<?php echo $empid; ?>/<?php echo $sucid; ?>/<?php echo $presid; ?>/<?php echo $prevm; ?>/<?php echo $prevan; ?>/<?php echo $vcod; ?>" class="relcal2"><?php echo $cal_be; ?></a></div>
    <?php } ?>
</div>