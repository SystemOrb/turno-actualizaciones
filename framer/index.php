<?
//include("js/globalinc.php");
include_once ("../js/func.php");
include_once ("../js/cambiocaracteres.php"); 
//include_once ("../_connax.php"); 
include("../js/class.php"); 
include_once ("../js/loginconf.php");
$rootdir = "http://www.turnonet.com/";
if($lang == ""){
	$lang = "es/";
}
if($pag == ""){
	$pag = "home";
}
$logged = 0;
if($s_usu_nom!=""){//usuario logueado
	$logged = 1;
}
$fecha = date("Y-n-d");
$hoy = date("Y-m-d");
?><!DOCTYPE html>
<html lang="es">
<head>
<? include_once ("js/met.php"); ?>
<? include_once ("js/css.php"); ?>
<? include_once ("js/jav.php"); ?>
</head><body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_ES/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="content">
	<div id="header"><? include($lang."login.php"); ?></div>
    <div class="corte"></div>
<? if(($logged!=1 && $log_res!=1) || ($log_res==3 || $log_res==4)){ ?>    
    <div id="heh2"><div id="bas"><? include($lang."homehead.php"); ?></div></div>
<? }else  if ($pr_es>0 && $logged == 1) { 
include("js/logs.php");
?><div id="hepres"><div id="basp"><? include($lang."headpres.php"); ?></div></div>
<? }else  if ($pr_es==0 && $logged == 1) { 
include("js/logs.php");
?><div id="hepres"><div id="basp"><? include($lang."headuser.php"); ?></div></div>
<? } ?>       
    <div id="cont"><div id="cfo"><? include("js/loadfile.php"); ?><a href="#cfo" id="back_to_top">Subir</a></div><div class="corte"></div></div>
    <div id="footer"><? include($lang."footer.php"); ?></div>
</div>
</body></html>
<? //echo $log_res; ?>
<? mysql_close($dbhtu); ?>