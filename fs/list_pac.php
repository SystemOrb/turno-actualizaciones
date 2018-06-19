<?php
// server should keep session data for AT LEAST 2 hour
ini_set('session.gc_maxlifetime', 7200);
// each client should remember their session id for EXACTLY 2 hour
session_set_cookie_params(7200);
$rd = $_GET['rd'];
if($rd!=''){
	include_once ("../js/func.php");
	include_once ("../js/cambiocaracteres.php"); 
	include_once ("../js/class.php"); 
	include_once("../js/globalinc.php");
	include_once ("../js/loginpconf.php");
	
	if($_SERVER['REMOTE_ADDR'] =='181.171.232.97'){
	echo "NOMBRE2: ".$s_usu_nom;
	}
	if($_COOKIE['tu_set_usu']==""){
		echo "la sesion a expirado! ingrese nuevamente al sistema para poder seguir administrando. 
		gracias.";
		exit();
	}
	$tipc = TNet::tipus($empid, 'empid');
	if($tipc === " | DEMO "){ $conn = TNet::conectarD(); }else if($tipc === "pos"){ $conn = TNet::conectar(); }
	$rootdir = "http://www.turnonet.com/";
	$lang = "es/";

	$getemp = mysql_query("select em_nomfan, em_tipo from tu_emps where em_id='$empid' 
	&& (em_uscid='$usid' || em_id IN(select ma_empid from tu_manag where ma_empid='$empid' 
	&& ma_usuid='$usid') ) ", $conn);
	$rowemp = mysql_num_rows($getemp);
	if($rowemp == 0){
		echo "No posee permisos para administrar esta empresa.";
		exit();
	}
	$datemp = mysql_fetch_assoc($getemp);
	$tur_tip = $datemp['em_tipo'];
	$tip_cor = "paciente";
	$tip_lar = "pacientes";
	if($tur_tip != 1){
		$tip_cor = "cliente";
		$tip_lar = "clientes";
	}
?><script>
$(function(){	
	$('#list_pac').jScrollPane();
	
	$(".pacadm").hover(
		function(){	$(this).stop().animate({backgroundColor: '#C30'});
		$('.chara', this).fadeIn(400); 
		$('.chdel', this).fadeIn(400); },
		function(){	$(this).stop().animate({backgroundColor: '#FFF'});
		$('.chara', this).fadeOut(400);
		$('.chdel', this).fadeOut(400);  }				
	);
	$(".lpact").click(function(evento) {
		evento.preventDefault();
		var rand_no = Math.random();
		rd = rand_no * 1000;
		var ihome = "http://www.turnonet.com/";
		var bu_s = $(this).attr('id');
		var em_s = $('#empid').val();
		var err; 
		if (bu_s == ""){
			err = 1;
		}		
		if(err == 1){ 
			return false;
		}		
		var url = ihome+"fs/list_tur.php?usid="+bu_s+'&empid='+em_s+'&rd='+rd;
		$("#listur").load(url,function(){			
			 $('#listur').show('slide', { direction: "up" },400);
		});
	});
});
</script><?php } ?>
<div id="list_pac" class="scroll-pane"><p><?php
if($rd!=''){
	if($_SERVER['REMOTE_ADDR'] == '181.171.232.97'){
		echo utf8_encode($val);
	}
	$addfilters = " (us.us_nom like '%$val%' || us.us_mail like '%$val%' 
	|| us.ud_emalt like '%$val%' ) && ";
}

/*$getpa = @mysql_query("SELECT *, (select count(*) from tu_turnos WHERE emp_id='$empid' && us_id=us.us_id && tu_estado='ALTA') totu FROM tu_users us left join tu_usdat ud on us_id=ud.ud_usid WHERE ".$addfilters." (us_id IN(SELECT tp_usid FROM tu_emp_cli WHERE tp_empid='$empid' && tp_usid=us.us_id) || us_id IN(SELECT us_id FROM tu_turnos 
WHERE emp_id='$empid' && us_id=us.us_id)) order by us_nom limit 10 ", $conn);*/

$getpa = @mysql_query("SELECT tp_usid us_id, (SELECT COUNT(*) FROM tu_turnos WHERE emp_id='$empid' && us_id=uscli.tp_usid && tu_estado='ALTA') totu, us.us_nom,us.us_mail
FROM tu_emp_cli uscli 
LEFT JOIN tu_users us ON tp_usid=us.us_id
WHERE ".$addfilters." tp_empid='$empid' order by us_nom limit 10 ", $conn);

if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
	/*echo "SELECT *, (select count(*) from tu_turnos WHERE emp_id='$empid' && us_id=us.us_id && tu_estado='ALTA') totu FROM tu_users us left join tu_usdat ud on us_id=ud.ud_usid WHERE ".$addfilters." (us_id IN(SELECT tp_usid FROM tu_emp_cli WHERE tp_empid='$empid' && tp_usid=us.us_id) || us_id IN(SELECT us_id FROM tu_turnos 
WHERE emp_id='$empid' && us_id=us.us_id)) order by us_nom limit 10 ";*/
}
$rowspa = mysql_num_rows($getpa);
if($rowspa == 0){
	echo "no se encontraron ".$tip_lar.".<br />";
	if($rd!=''){ echo "Busqueda: ".$val; }
}
while($datpa = mysql_fetch_assoc($getpa)){
	$titu = " turnos ";
	if($datpa['totu'] == 1){
		$titu = " turno ";
	}
?>
<div class="pacadm">
<?php if($datpa['totu'] == 0){ ?>
    <div class="chdel">
<a href="javascript:void(0)" title="Eliminar 
    <?php echo ucwords($tip_cor); ?>" class='delpac' id="tuid_<?php echo $datpa['us_id']; ?>">
    <img src="<?php echo $rootdir; ?>imagenes/menu/ic_del.png" width="25" height="25" class="img_b"></a>
</div><?php } ?>
    
<div class="chara">
    <a href="javascript:void(0)" class="lpact" id="<?php echo $datpa['us_id']; ?>">
        <?php echo $datpa['totu'].$titu ?>
    </a>
<?php if($_SERVER['REMOTE_ADDR'] == '181.171.232.97' || $empid == '929'){ ?>
<a href="<?php echo $rootdir; ?>administrar-agendas/v2/imppres/<?php echo $usid; ?>/<?php echo $datpa['us_id']; ?>/<?php echo $empid; ?>" title="Imprimir Datos del <?php echo ucwords($tip_cor); ?>" target="_blank" id="impdid_<?php echo $datpa['us_id']; ?>">
    <img src="<?php echo $rootdir; ?>imagenes/menu/ic_print.png" width="25" height="25" class="img_b">
</a>
<?php } ?>

<a href="javascript:void(0)" 
   title="Datos del <?php echo ucwords($tip_cor); ?>"
   class='lodin' id="tuid_<?php echo $datpa['us_id']; ?>">
    <img src="<?php echo $rootdir; ?>imagenes/menu/ic_usp.png" width="25" height="25" class="img_b">
</a>
<a href="javascript:void(0)" title="Seleccionar para Carga de Turno" class="tooltip" onClick="showu('<?php echo $datpa['us_id']; ?>','<?php echo $datpa['us_nom']; ?>','<?php echo $datpa['us_mail']; ?>')">
    <img src="<?php echo $rootdir; ?>imagenes/menu/ic_ctur.png" width="25" height="25" class="img_b">
</a>
</div>
<?php  echo utf8_encode($datpa['us_nom']);
	echo "<br />";
	echo $datpa['us_mail'];
	echo "<br />"; echo "<br />";
?>
</div><?php } ?></p>
</div>