<?php
// server should keep session data for AT LEAST 2 hour
ini_set('session.gc_maxlifetime', 7200);
// each client should remember their session id for EXACTLY 2 hour
session_set_cookie_params(7200);
include_once ("../js/func.php");
include_once ("../js/cambiocaracteres.php"); 
include_once ("../js/class.php"); 
include_once("../js/globalinc.php");
include_once ("../js/loginconf.php");
if($_COOKIE['tu_set_usu']==""){
	echo "la sesion a expirado! ingrese nuevamente al sistema para poder seguir administrando. 
	gracias. turnonet.com";
	exit();
}
//$s_usu_idadmin = $_SESSION['tu_set_uidadmin'];
if($s_usu_idadmin!=''){
	//echo "LLEGo ".$s_usu_idadmin;
}
$tipc = TNet::tipus($empid, 'empid');
if($tipc === " | DEMO "){ $conn = TNet::conectarD(); }else if($tipc === "pos"){ $conn = TNet::conectar(); }
$rootdir = "http://www.turnonet.com/";
$lang = "es/";


$getemp = mysql_query("select em_nomfan, em_tipo, em_ctf from tu_emps where em_id='$empid' && (em_uscid='$usid' || em_id IN(select ma_empid from tu_manag where ma_empid='$empid' && ma_usuid='$usid') ) ", $conn);
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

/*$getpa = mysql_query("select count(*) tt from tu_users us where us_id IN(select tp_usid from tu_emp_cli where tp_empid='$empid' && tp_usid=us.us_id) || us_id IN(select us_id from tu_turnos where emp_id='$empid' && us_id=us.us_id) ", $conn);
$datpa = mysql_fetch_assoc($getpa);*/

if($sucid == "" && $presid!=""){
	$getsuc = mysql_query("select suc_id from tu_tmsp 
	where emp_id='$empid' && tmsp_id='$presid' ", $conn);
	$datsuc = mysql_fetch_assoc($getsuc);
	$sucid = $datsuc['suc_id']; 
}

//minimo dia de carga de turnos
$min_an = date("Y", mktime(12,0,0,date("n"),date("d")-10,date("Y")));
$min_mo = date("n", mktime(12,0,0,date("n"),date("d")-10,date("Y")));
$min_di = date("d", mktime(12,0,0,date("n"),date("d")-10,date("Y")));
$hoy = date("Y-m-d");
$now =date("H:i:s", mktime(date("H"),date("i")-60,date("s"),date("m"),date("d"),date("Y"))); 
if($fecfil !=""){
	$fec2 = explode("/",$fecfil);
	$dia = $fec2[0];
	$mes_num = $fec2[1];
	$anio = $fec2[2];
	$sel_day = date("w", mktime(12,0,0,$fec[1],$fec[0],$fec[2]));	
	$fec2 = $fec2[2]."-".$fec2[1]."-".$fec2[0];
	$fec = $fec2;
}
if($fechoy !=""){
	$dia = date("d");
	$mes_num = date("n");
	$anio = date("Y");
	$sel_day = date("w", mktime(12,0,0,$mes_num,$dia,$anio));	
	$fec2 = date("Y-n-d");;
	$fec = $fec2;
	$fechsi = 1;
}
if($fec==''){
	$fec2 = date("Y-m-d");
	$dia = date("d");
	$mes_num = date("m");
	$anio = date("Y");
	$sel_day = date("w", mktime(12,0,0,$mes_num,$dia,$anio));	
}
$hornow = date("H");
$eldia = $dia;
$dia_letra = date("w", mktime(12,0,0,$mes_num,$eldia,$anio));
$infodia = setdayl($dia_letra,$lang)." ".$dia." de ".mes($mes_num)." de ".$anio;
$dl = date("w", mktime(12,0,0,$mes_num,$dia,$anio));

//HORARIOS DEL PRESTADOR
if($presid!=""){
	//include_once("list_proxtur.php");
}


?><!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="<?php echo $rootdir; ?>css/formus.css" />
<?php /*<link rel="stylesheet" href="<?php echo $rootdir; ?>css/s_cal.css" />*/ ?>
<link rel="stylesheet" href="<?php echo $rootdir; ?>css/s_calsm.css" />
<link rel="stylesheet" href="<?php echo $rootdir; ?>css/estilos.css" />
<link rel="stylesheet" href="<?php echo $rootdir; ?>css/agenda.css" />
<?php /*<link rel="stylesheet" href="<?php echo $rootdir; ?>css/calendario.css" />*/ ?>
<link rel="stylesheet" href="<?php echo $rootdir; ?>css/agpres.css" />
<link rel="stylesheet" href="<?php echo $rootdir; ?>css/jquery-ui.css" />
<link href="<?php echo $rootdir; ?>favicon.ico" rel="shortcut icon" />
<title><?php echo $datemp['em_nomfan']; ?></title>
<script src="<?php echo $rootdir; ?>js/genva.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script src="<?php echo $rootdir; ?>js/jquery.mousewheel.js"></script>
<script src="<?php echo $rootdir; ?>js/jquery.jscrollpane.min.js"></script>
<script src="<?php echo $rootdir; ?>js/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo $rootdir; ?>js/jpres.js"></script>
<script>
function NW(mypage, myname, w, h, scroll) {
var winl = (screen.width - w) / 2;
var wint = (screen.height - h) / 2;
winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+'';
win = window.open(mypage, myname, winprops);
}
function enviar(){
	var form = document.getElementById("buscapres");
	
	
	document.getElementById("buscapres").action = "http://www.turnonet.com/administrar-agendas/v2/imprimir/turnos";
	//document.getElementById("form_id").submit();
	
	window.open(document.getElementById("buscapres").submit());
	return false;
}
$(function(){	
	$('#list_pac').jScrollPane();
	$('#list_tur').jScrollPane();
	$('#tur_hora').timepicker({
		timeOnlyTitle: 'Seleccionar Horario',
		timeText: 'Horario',
		hourText: 'Hora',
		minuteText: 'Minutos',
		currentText: 'Ahora',
		closeText: 'Listo'
	});
	<?php if($datemp['em_ctf'] == 0){ ?>
	$('#tur_fec').datepicker({
		numberOfMonths: 3,
		showButtonPanel: true,
		minDate: new Date(<?php echo $min_di; ?>, <?php echo $min_mo; ?>, <?php echo $min_di; ?>),
		dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
		monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
		dateFormat: "dd/mm/yy",
		prevText: "Anterior",
		nextText: "Siguiente",
		currentText: 'Ahora',
		closeText: 'Listo'
	});
	<?php } ?>
	$('#fecfil').datepicker({
		numberOfMonths: 3,
		showButtonPanel: true,
		minDate: new Date(<?php echo $min_di; ?>, <?php echo $min_mo; ?>, <?php echo $min_di; ?>),
		dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
		monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
		dateFormat: "dd/mm/yy",
		prevText: "Anterior",
		nextText: "Siguiente",
		currentText: 'Ahora',
		closeText: 'Listo'
	});
	
	//load times
	$(".hora").hover(
		function(){	$(this).stop().animate({backgroundColor: '#C30'}); },
		function(){	$(this).stop().animate({backgroundColor: '#0CC'}); }				
	);
	$(".pacadm").hover(
		function(){	$(this).stop().animate({backgroundColor: '#C30'});
		$('.chara', this).fadeIn(400); 
		$('.chdel', this).fadeIn(400); },
		function(){	$(this).stop().animate({backgroundColor: '#FFF'});
		$('.chara', this).fadeOut(400);
		$('.chdel', this).fadeOut(400);  }				
	);
	$(".turadm").hover(
		function(){	$(this).stop().animate({backgroundColor: '#C30'});
		$('.chara', this).fadeIn(400); },
		function(){	$(this).stop().animate({backgroundColor: '#FFF'});
		$('.chara', this).fadeOut(400); }				
	);
	
});
</script>
</head>
<body>
<div id="wrapp"><div id="totpg" class="opa80"></div>
<div id="usdata"><input name="lod_tuid" id="lod_tuid" type="hidden" value="">
<div id='showd'></div>
<div class="close"><a href="javascript:void(0)" class="lodou">X</a></div></div>
<div id="pr_top">
<form action="" method="POST" name="buscapres" id="buscapres">
<div class="pr_fil">
<?php 
if($empid!=""){
	$affducf = "";
	if($_COOKIE['tu_sucadm']!=''){
		$affducf = " && suc_id='$_COOKIE[tu_sucadm]' ";
	}
$sql = mysql_query("select suc_id, suc_nom from tu_emps_suc where suc_empid='$empid' && suc_estado='ALTA' ".$affducf." order by suc_nom", $conn);
$rows = mysql_num_rows($sql);
}
?>
Sucursal<br /><select name="sucid" id="sucid" class="sel_uprt">
<?php if($rows>1){ ?><option value="">--Seleccionar Sucursal--</option><?php } ?>
<?php
if($empid!=""){
while($data = mysql_fetch_assoc($sql)){
	$sel = "";
	if($sucid == $data['suc_id'] || $rows == 1){ $sel = " selected "; $suc_nom = $data['suc_nom']; $sucid = $data['suc_id']; }
?>
<option value="<?php echo $data['suc_id']; ?>"<?php echo $sel; ?>><?php echo $data['suc_nom']; ?></option>
<?php } 
}
?>
</select>
</div>
<div class="pr_fil">Prestadores<br />
<select name="presid" id="presid" class="sel_uprt">
<option value="">--Seleccionar Prestador--</option>
<?php
if($empid!=""){
	$addse = "";
	if($sucid!=""){
		$addse = " && suc_id='$sucid' ";
	}
	if($_COOKIE['tu_preadm']!=''){
		$addse .= " && tmsp_id='$_COOKIE[tu_preadm]' ";
	}
	$sql = mysql_query("select tmsp_id, tmsp_tit, tmsp_pnom from 
	tu_tmsp where emp_id='$empid' && tmsp_estado='ALTA' ".$addse." 
	order by tmsp_pnom", $conn);
	$rows = mysql_num_rows($sql);
	while($data = mysql_fetch_assoc($sql)){
		$sel = "";
		if($presid == $data['tmsp_id'] || $rows == 1){ $sel = " selected "; $pre_nom = $data['tmsp_pnom']; $presid = $data['tmsp_id']; }
?><option value="<?php echo $data['tmsp_id']; ?>"<?php echo $sel; ?>><?php if($data['tmsp_tit']!=""){ ?><?php echo $data['tmsp_tit']." "; ?><?php } ?><?php echo $data['tmsp_pnom']; ?></option>
<?php 
	} 
}
?>
</select>
</div>
<?php if($presid!=""){ 
	$getserv = mysql_query("select * from tu_emps_serv where
	serv_presid='$presid' && serv_sucid='$sucid' && serv_empid='$empid' 
	&& serv_tipo=1 && serv_estado=1 ", $conn);
	$rowserv = mysql_num_rows($getserv);
	if($rowserv>0){
?>
<div class="pr_fil">Servicio<br />
<select name="servid" id="servid" class="sel_upr2">
<option value="">--Seleccionar--</option>
<?php
	while($data = mysql_fetch_assoc($getserv)){
		$sel = "";
		if($servid == $data['serv_id']){ $sel = " selected "; $serv_nom = $data['serv_nom']; $serv_time = hora($data['serv_tudur']); $serv_tt = $data['serv_turx']; }
?><option value="<?php echo $data['serv_id']; ?>"<?php echo $sel; ?>><?php echo $data['serv_nom']; ?></option>
<?php 
	} 
?>
</select>
</div>
<?php 
	}
} 
?>
<div class="pr_fil">Filtros<br /><?php
if($bus_fil == ""){ $self_0 = " selected "; 
}else if($bus_fil == 3){ $self_5 = " selected "; 
}else if($bus_fil == "ALTA"){ $self_10 = " selected ";
}else if($bus_fil == 1){ $self_15 = " selected ";
}else if($bus_fil == 2){ $self_20 = " selected "; 
}else if($bus_fil == 4){ $self_25 = " selected "; 
}else if($bus_fil == 5){ $self_30 = " selected "; }
?>
<select name="bus_fil" id="bus_fil" class="sel_uli">
<option value="" <?php echo $self_0; ?>>Proximos Turnos</option>
<option value="4" <?php echo $self_25; ?>>Vigentes</option>
<option value="3" <?php echo $self_5; ?>>Todos</option>
<option value="1" <?php echo $self_15; ?>>Sobreturnos</option>
<option value="5" <?php echo $self_30; ?>>Atendidos</option>
<option value="2" <?php echo $self_20; ?>>Cancelados</option>
</select>
</div>
<div class="pr_fil">Fecha<br /><?php if($fecfil == ""){ $fecfil = fect($fec2); } 
if($fechsi!=''){ $fecfil = fect($fec2); }
?>
<input type="text" name="fecfil" id="fecfil" class="sel_fec" value="<?php echo $fecfil; ?>">
</div>
<div class="pr_fil">
<br /><input type="submit" name="fechoy" id="fechoy" class="fsubh" value="HOY">
</div>
<div class="pr_fil">
  <input name="empid" type="hidden" id="empid" value="<?php echo $empid ?>">
  <input name="usid" type="hidden" id="usid" value="<?php echo $usid; ?>">
  <input name="tur_tip" type="hidden" id="tur_tip" value="<?php echo $tur_tip; ?>">
  <input name="pres_usid" type="hidden" id="pres_usid" value="">
  <input name="pres_usnom" type="hidden" id="pres_usnom" value="">
  <br />
<input type="submit" name="buttonb" id="buttonb" class="fsub" value="Filtrar">
</div>
</form>
</div>
<div id="pr_stop">
<?php if($presid!="" && $empid!='885'){ ?>
<div class="prtmen"><a href="javascript:void(0)" class="nxtur"><img src="<?php echo $rootdir; ?>imagenes/menu/ic_ept.png" width="30" height="25" class="img_b">PROXIMOS TURNOS DISPONIBLES</a></div>
<?php if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){ ?>
<div class="prtmen"><a href="<?php echo $rootdir; ?>administrar-agendas/v2/horarios/<?php echo $usid; ?>/<?php echo $empid; ?>/<?php echo $sucid; ?>/<?php echo $presid; ?>" class="nxhor" target="_blank"><img src="<?php echo $rootdir; ?>imagenes/menu/ic_ept.png" width="30" height="25" class="img_b">HORARIOS DISPONIBLES</a></div>
<?php } ?>
<?php
$getn = mysql_query("select * from tu_emps_not where not_presid='$presid' && not_estado=1 
&&(not_caduc='0000-00-00' || not_caduc>='$hoy') ", $conn);
$rowsn = mysql_num_rows($getn);
if($rowsn>0){
?>
<div class="prtmen"><a href="javascript:void(0)" class="nxnots"><img src="<?php echo $rootdir; ?>imagenes/menu/ic_not.png" width="30" height="25" class="img_b"><?php echo $rowsn; ?> NOTAS</a></div><?php } ?>
<?php } ?><div class="prrmen"><a href="javascript:void(0)" class="lodnpc tooltip" title="Agregar <?php echo ucwords($tip_cor); ?>"><img src="<?php echo $rootdir; ?>imagenes/menu/icb_usad.png" width="25" height="25" class="img_b"></a></div><?php
$datho = date("Y-m-d");
$getth = mysql_query("select count(*) tott from tu_turnos where emp_id='$empid' && tu_bloqfec='$datho' ", $conn);
$rowsth = mysql_fetch_assoc($getth);
$ttxt = " turnos cargados";
if($rowsth['tott'] == 1){ $ttxt = " turno cargado"; } 
?><div class="prrlth">
<?php
if($_SERVER['REMOTE_ADDR'] == '181.171.232.97'){
?>
<script src="<?php echo $rootdir; ?>lib/easytimer/dist/easytimer.min.js"></script>
<script>
    //var timerInstance = new Timer();
	
	var timer = new Timer();
	timer.start();
	timer.addEventListener('secondsUpdated', function (e) {
		$('#basicUsage').html(timer.getTimeValues().toString());
	});
</script>
<span id="basicUsage">00:00:00</span>
<?php
}
?><a href="javascript:void(0)" class="ltchoy tooltip" title="Ver turnos cargados el <?php echo date("d/m/Y"); ?>"><?php echo "<span>".$rowsth['tott']."</span>".$ttxt; ?></a></div>
</div>
<div id="tur_dat">
<div id="ptnxtu"><div id='shownxtur'></div></div>
<div id="ptnxnot"><div id='shownxnot'></div></div>
<div id="isucce"><div id='showsucce'></div></div>
<div class="tubox">
<?php
$add_opt = "";
if($sucid!=""){ $add_opt .= " && t.suc_id='$sucid' "; }
if($presid!=""){ $add_opt .= " && t.pres_id='$presid' "; }
$add_opt .= " && t.tu_fec='$fec2' ";
$add_tots = $add_opt;

if($bus_fil == ""){
	$txt_cero = "No hay turnos vigentes para el d&iacute;a ".$infodia;
	if($fec2 == $hoy){
		$add_opt .= " && t.tu_hora>'".$now."' "; 
		$txt_cero = "No hay turnos vigentes para el d&iacute;a de hoy.";
		$add_tots .= " && t.tu_hora>'".$now."' "; 
	}
	$add_opt .= " && tu_asist=0 && tu_estado='ALTA' ";
	$add_tots .= " && tu_asist=0 && tu_estado='ALTA' ";  
}else if($bus_fil == 3){  
	$txt_cero = "No tiene turnos cargados para el ".$infodia;
}else if($bus_fil == 1){ $add_opt .= " && tu_st=1 "; 
	$add_tots .= " && tu_st=1 ";
	$txt_cero = "No se encontraron sobreturnos cargados para el ".$infodia;
}else if($bus_fil == 2){ $add_opt .= " && tu_estado='BAJA' ";
	$add_tots .=  " && tu_estado='BAJA' ";
	$txt_cero = "No se encontraron turnos cancelados para el ".$infodia;
}else if($bus_fil == 4){ $add_opt .= " && tu_estado='ALTA' "; 
	$add_tots .=  " && tu_estado='ALTA' "; 
	$txt_cero = "No se encontraron turnos vigentes para el ".$infodia;
}else if($bus_fil == 5){ $add_opt .= " && tu_estado='ALTA' && tu_asist=1 "; 
	$add_tots .=  " && tu_estado='ALTA' && tu_asist=1 "; 
	$txt_cero = "No se encontraron turnos vigentes para el ".$infodia;
}


$getot = mysql_query("SELECT 
SUM(CASE WHEN t.tu_estado='ALTA' THEN 1 ELSE 0 END) AS totalta,
SUM(CASE WHEN t.tu_estado='BAJA' THEN 1 ELSE 0 END) AS totbaja,
SUM(CASE WHEN t.tu_st=1 THEN 1 ELSE 0 END) AS totsobr,
SUM(CASE WHEN t.tu_asist=1 THEN 1 ELSE 0 END) AS totasis
FROM tu_turnos t 
WHERE t.emp_id='$empid' ".$add_tots." order by tu_fec, tu_hora", $conn);
$datot = mysql_fetch_assoc($getot);

$sql = mysql_query("SELECT * FROM tu_turnos t 
LEFT JOIN tu_emps e on e.em_id=t.emp_id 
LEFT JOIN tu_emps_suc s on s.suc_id=t.suc_id 
LEFT JOIN tu_tmsp p on p.tmsp_id=t.pres_id 
LEFT JOIN tu_users u on u.us_id=t.us_id
LEFT JOIN tu_usdat ud on u.us_id=ud.ud_usid
LEFT JOIN tu_ususmd uma on t.tu_id=uma.usm_turid
WHERE t.emp_id='$empid' ".$add_opt." 
GROUP BY tu_id
ORDER BY tu_fec, tu_hora", $conn);
$rowtur = mysql_num_rows($sql);
if($_SERVER['REMOTE_ADDR'] == '190.55.216.225'){
	echo "SELECT * FROM tu_turnos t 
left join tu_emps e on e.em_id=t.emp_id 
left join tu_emps_suc s on s.suc_id=t.suc_id 
left join tu_tmsp p on p.tmsp_id=t.pres_id 
left join tu_users u on u.us_id=t.us_id
left join tu_usdat ud on u.us_id=ud.ud_usid
left join tu_ususmd uma on t.tu_id=uma.usm_turid
WHERE t.emp_id='$empid' ".$add_opt." 
GROUP BY tu_id
order by tu_fec, tu_hora";
}
?>
<div class="ptidat"><?php echo $infodia; ?> | <?php echo $rowtur; ?> turnos encontrados</div>
<div class="stit2 s16">Turnos Solicitados</div>
<div id="listur"><?php include_once("../fs/list_tur.php"); ?></div>
</div>
<div class="tbox">
<div class="ptudat">turnos <img src="<?php echo $rootdir; ?>imagenes/menu/ic_alt.png" width="25" height="25" class="img_b"> <?php echo $datot['totalta']; ?> ALTA <img src="<?php echo $rootdir; ?>imagenes/menu/ic_conf.png" width="25" height="25" class="img_b"> <?php echo $datot['totasis']; ?> ATENDIDOS <img src="<?php echo $rootdir; ?>imagenes/menu/ic_sobt.png" width="25" height="25" class="img_b"> <?php echo $datot['totsobr']; ?> SOBRETURNOS <img src="<?php echo $rootdir; ?>imagenes/menu/ic_canc.png" width="25" height="25" class="img_b"> <?php echo $datot['totbaja']; ?> CANCELADOS <a href="javascript:void(0)" onclick="enviar()" target="_blank"><img src="<?php echo $rootdir; ?>imagenes/menu/ic_print.png" width="25" height="25" class="img_b"> IMPRIMIR</a></div><div class="corte"></div>
</div>
<?php /*<div class="tbox">
<div class="ptidat"><?php echo $infodia; ?></div>
<div class="stit2 s16">Horarios Disponibles</div>
<?php
if($presid == ''){
	echo "Seleccione un prestador para ver los horarios disponibles para dar turnos.";
}else{
	include_once("../fs/list_hordis.php");
}//presid OK
?><div class="corte"></div>
</div>
<?php if($presid!=""){ ?>
<div class="tbox">
<?php include_once("../fs/list_dayat.php"); ?><div class="corte"></div>
</div>
<?php } ?>*/ ?>
</div>
    
    
    
    <!-- Buscador de pacientes -->
<div id="inf_dat">
<div class="pbox">
<div class="ptidat"><?php /*echo $datpa['tt']; ?> <?php echo $tip_lar;*/ ?></div>
<div class="stit2 s16"><?php echo ucwords($tip_lar); ?></div>
<div class="letr"><a href="javascript:void(0)" class="letre" id="let_a">A</a> <a href="javascript:void(0)" class="letre" id="let_b">B</a> <a href="javascript:void(0)" class="letre" id="let_c">C</a> <a href="javascript:void(0)" class="letre" id="let_d">D</a> <a href="javascript:void(0)" class="letre" id="let_e">E</a> <a href="javascript:void(0)" class="letre" id="let_f">F</a> <a href="javascript:void(0)" class="letre" id="let_g">G</a> <a href="javascript:void(0)" class="letre" id="let_h">H</a> <a href="javascript:void(0)" class="letre" id="let_i">I</a> <a href="javascript:void(0)" class="letre" id="let_j">J</a> <a href="javascript:void(0)" class="letre" id="let_k">K</a> <a href="javascript:void(0)" class="letre" id="let_l">L</a> <a href="javascript:void(0)" class="letre" id="let_m">M</a> <a href="javascript:void(0)" class="letre" id="let_n">N</a> <a href="javascript:void(0)" class="letre" id="let_o">O</a> <a href="javascript:void(0)" class="letre" id="let_p">P</a> <a href="javascript:void(0)" class="letre" id="let_q">Q</a> <a href="javascript:void(0)" class="letre" id="let_r">R</a> <a href="javascript:void(0)" class="letre" id="let_s">S</a> <a href="javascript:void(0)" class="letre" id="let_t">T</a> <a href="javascript:void(0)" class="letre" id="let_u">U</a> <a href="javascript:void(0)" class="letre" id="let_v">V</a> <a href="javascript:void(0)" class="letre" id="let_w">W</a> <a href="javascript:void(0)" class="letre" id="let_x">X</a> <a href="javascript:void(0)" class="letre" id="let_y">Y</a> <a href="javascript:void(0)" class="letre" id="let_z">Z</a></div>
<!-- ../ -->
<script>
$(document).ready(function() {
   $('input[name=buspac]').click( function() {
      $.ajax({
         url:'http://www.turnonet.com/es/app/controllers/buscador.php',
         method: 'GET',
         data: $('#buscapacAJAX').serialize(),
         cache: false,
         success: function(XMR_RESPONSE) {
             // alert(XMR_RESPONSE);
         },
         error: function(ERR_) {
             alert("Error");
         } 
      });
      return false;
   });
});
</script>
<!-- ../fs/list_pac.php -->
<form action="../fs/list_pac.php" method="post" id="buscapacAJAX">
  <input name="pac_nom" id="pac_nom" type="text" class="inpsm_u tooltip" title="Nombre / Email">
  <input name="empid" type="hidden" value="<?php echo $empid ?>">
  <input type="submit" name="buspac" id="buspac" class="fsub" value="Buscar">
</form>
<!--<form action="../fs/list_pac.php" method="post" name="buscapac" id="buscapac" onSubmit="return false;">
  <input name="pac_nom" id="pac_nom" type="text" class="inpsm_u tooltip" title="Nombre / Email">
  <input name="empid" type="hidden" value="<?php echo $empid ?>">
  <input type="button" name="buspac" id="buspac" class="fsub" value="Buscar">
</form>-->
<div id="listpac"><?php  include_once("../fs/list_pac.php"); ?></div>

</div><?php
if($_SERVER['REMOTE_ADDR'] == '181.167.162.133'){
	
	//exit();
}
?>
<?php if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){ ?>
<div class="dibt"><a href='<?php echo $rootdir; ?>etur/<?php echo $empid; ?>/<?php echo $usid; ?>' onclick="NW(this.href,'TURNOS<?php echo $data['em_id']; ?>','990','680','yes'); return false;">Cargar Turno</a></div>
<?php } ?>
<form action="dsds" method="post" name="turnont" onSubmit="return false;"><div class="pbox">
<div class="stit2 s16">Cargar Turno
  <input type="hidden" name="tur_usid" id="tur_usid" value="<?php echo $pres_usid; ?>">
  <input type="hidden" name="tur_reag" id="tur_reag">
  <input type="hidden" name="tur_presid" id="tur_presid" value="<?php echo $presid; ?>">
  <input type="hidden" name="pr_ts" id="pr_ts" value="<?php echo $simtu; ?>">
  <input type="hidden" name="tu_servs" id="tu_servs" value="">
  <input type="hidden" name="tu_durac" id="tu_durac" value="">
  <input type="hidden" name="tu_usadm" id="tu_usadm" value="<?php echo $s_usu_idadmin; ?>">
</div>
<div class="ctd">Sucursal: <?php if($suc_nom!=""){ echo $suc_nom; } ?></div>
<div class="ctd" id="set_pres">Prestador: <?php if($pre_nom!=""){ echo $pre_nom; } ?></div>
<?php if($datemp['em_ctf'] == 1){
	if($presid!=''){
?>
<div class="ctd" id="set_servicios">Servicios: -</div>
<div class="ctd" id="set_dura">Duraci&oacute;n Estimada: -</div>
<?php 
	}	
}
?>
<div class="ctd"><?php if($datemp['em_ctf'] == 1){
echo "<div class='setical'>";
include_once("../agp/loadcal.php");
echo "</div>"; } ?>
Fecha: 
<input type="text" name="tur_fec" id="tur_fec" class="sel_fec<?php if($datemp['em_ctf'] == 1){ echo " lodmecal"; } ?>">

| 
Hora:
<label for="textfield"></label>
<input type="text" name="tur_hora" id="tur_hora" class="sel_hor"></div>
<div class="ctd"><div class="ctl"><?php echo ucwords($tip_cor); ?>:&nbsp;</div> <div id="ures"><?php if($pres_usnom!=''){ echo $pres_usnom; } ?></div></div><div class="corte"></div>
<div class="ctd"><input name="tur_st" id="tur_st" type="checkbox" value="1"> Cargar como Sobreturno</div>
<div class="dibt"> <input type="submit" name="cturno" id="cturno" class="newt fsub" value="Cargar Turno"></div>
</div>
</form>
</div>
    
    
    
    
    
</div>
</body>
</html>
<?php //mysql_close($dbhtu); ?>