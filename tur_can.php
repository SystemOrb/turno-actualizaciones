<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
<!--        <script src="https://www.google.com/recaptcha/api.js" async defer></script>-->
<!--          <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
           </script>-->
    </head>
    <body>
    <script type="text/javascript">
      var onloadCallback = function() {
        grecaptcha.render('html_element', {
          'sitekey' : '6LfuGmAUAAAAAFCnH4tSXojSYS8tQoMp9FbrzTWI'
        });
      };
    </script>
          
<?php
if($pag == "turcancel" ){
	$tipc = TNet::tipus($empid, 'empid');
	if($tipc == " | DEMO "){ $conn = TNet::conectarD(); }
	
	/*$getur = mysql_query("select tu_id, tu_estado from tu_turnos where tu_id='$turid' 
	&& us_id='$usid' && pres_id='$presid' && suc_id='$sucid' && emp_id='$empid' ", $conn);*/
	
	$getur = mysql_query("SELECT * FROM tu_turnos t left join tu_emps e on e.em_id=t.emp_id 
	left join tu_emps_suc s on s.suc_id=t.suc_id 
	left join tu_tmsp p on p.tmsp_id=t.pres_id 
	left join tu_users u on u.us_id=t.us_id
	left join tu_usdat ud on u.us_id=ud.ud_usid
	WHERE t.tu_id='$turid' && t.us_id='$usid' && t.pres_id='$presid' 
	&& t.suc_id='$sucid' && t.emp_id='$empid' ".$add_opt." ", $conn);
	$rowtur = mysql_fetch_assoc($getur);
	
	//BUSCO TIEMPO DE CANCELACION DE TURNO
	//BUSCO CONFIGURACION
	$gcon = mysql_query("select cf_tcan from tu_emps_con where pres_id='$presid' 
	&& suc_id='$sucid' && emp_id='$empid' order by pres_id", $conn);
	$row = mysql_num_rows($gcon);
	if($row == 0){
		$gcon = mysql_query("select cf_tcan from tu_emps_con where pres_id=0 && 
		suc_id='$sucid' && emp_id='$empid' order by suc_id", $conn);
		$row = mysql_num_rows($gcon);
		if($row == 0){
			$gcon = mysql_query("select cf_tcan from tu_emps_con where pres_id=0 && 
			suc_id=0 && emp_id='$empid' order by emp_id", $conn);
			$row = mysql_num_rows($gcon);
		}
	}
	$datcon = mysql_fetch_assoc($gcon);
	$tiempo_de_cancelacion = $datcon['cf_tcan'];
		
		$set_tday = $rowtur['tu_fec'];
		$set_thor = $rowtur['tu_hora'];		
		$get_cando = tcan($tiempo_de_cancelacion, $set_tday, $set_thor);
		//echo $get_cando;
				
		$setemp = $rowtur['em_nomfan']." - ".$rowtur['suc_nom'];
		if(trim($rowtur['em_nomfan']) == trim($rowtur['suc_nom'])){
			$setemp = $rowtur['suc_nom'];
		}
		if($rowtur['tmsp_pnom']!="Unico" && $rowtur['tmsp_pnom']!="Generico"){
			$setemp .= "<br />".$rowtur['tmsp_pnom'];
		}
		$setemp = utf8_encode($setemp);
	if($setmot == ""){
		if($rowtur['tu_id'] != "" && $rowtur['tu_estado'] == "ALTA" && $get_cando == 2){
			
			if($rowtur['tur_canid']!=''){
				$baj = 5; 
			}else{
				mysql_query("update tu_turnos set tu_estado='BAJA' 
				where tu_id='".$rowtur['tu_id']."' ",$conn);
				$fec = date("Y-n-d");
				$hor = date("H:i:s");
				$getcandat = @mysql_query("select * from tu_tucan where tucan_turid='$turid' ", $conn);
				$rowdatcon = @mysql_num_rows($getcandat);
				if($rowdatcon == 0){
					$can_ip = $_SERVER['REMOTE_ADDR'];
					$can_url = $_SERVER['REQUEST_URI'];
					$can_ref = $_SERVER['HTTP_REFERER'];
					mysql_query("insert into tu_tucan set tucan_turid='$turid', 
					tucan_fec='$fec', tucan_hor='$hor', tucan_usid='$usid',tucan_tipo='USUARIO',
					tucan_ip='$can_ip', tucan_url='$can_url', tucan_ref='$can_ref' ",$conn);
				}else{
					$can_ip = $_SERVER['REMOTE_ADDR'];
					mysql_query("update tu_tucan set tucan_mot='$mot_can' 
					where tucan_turid='$turid',tucan_tipo='USUARIO',
					tucan_ip='$can_ip', tucan_url='$can_url' ", $conn);
				}
				$baj = 1; 
			}
		}else if($rowtur['tu_id'] != "" && $rowtur['tu_estado'] == "ALTA" && $get_cando == 1){
			$baj = 4; 
		}else if($rowtur['tu_id'] != "" && $rowtur['tu_estado'] == "BAJA"){
			$baj = 2; 
		}else{ $baj = 3; }
		
		
		
	}else if($setmot == 2 && $rowtur['tu_id'] != ""){
		
		if($rowtur['tur_canid']!=''){
			
			if(!$cod_can == $rowtur['tur_canid'] || ($cod_can==0)) {
				mysql_query("update tu_turnos set tu_estado='BAJA' 
				where tu_id='".$rowtur['tu_id']."' ",$conn);
				$fec = date("Y-n-d");
				$hor = date("H:i:s");
				$getcandat = @mysql_query("select * from tu_tucan where tucan_turid='$turid' ", $conn);
				$rowdatcon = @mysql_num_rows($getcandat);
				if($rowdatcon == 0){
					$can_ip = $_SERVER['REMOTE_ADDR'];
					$can_url = $_SERVER['REQUEST_URI'];
					$can_ref = $_SERVER['HTTP_REFERER'];
					mysql_query("insert into tu_tucan set tucan_turid='$turid', 
					tucan_fec='$fec', tucan_hor='$hor', tucan_usid='$usid',
					tucan_mot='$mot_can', tucan_tipo='USUARIO',
					tucan_ip='$can_ip', tucan_url='$can_url', tucan_ref='$can_ref'
					, tucan_cod='' ",$conn); // $cod_can
				}else{
					$can_ip = $_SERVER['REMOTE_ADDR'];
					mysql_query("update tu_tucan set tucan_mot='$mot_can'
					, tucan_cod='' ,
					tucan_mot='$mot_can', tucan_tipo='USUARIO',
					tucan_ip='$can_ip', tucan_url='$can_url'
					where tucan_turid='$turid' ", $conn); // $cod_can en el ''
				}
				$baj = 1; 
			}else{
				$baj = 10; 
			}
		}else{
			if($mot_can != ""){
				$mot_can = novalidos($mot_can);
				$can_ip = $_SERVER['REMOTE_ADDR'];
				mysql_query("update tu_tucan set tucan_mot='$mot_can', 
				tucan_tipo='USUARIO', tucan_ip='$can_ip'
				where tucan_turid='$turid' ", $conn);
			}
		}
	}
}
?><div class="hpresl">
<?php
$tit = "Cancelación de Turno";
?>
<h1 class="tit3 s26"><img src="<? echo $rootdir; ?>imagenes/menu/icb_tur.png" width="25" height="30" class="img_b" alt=""> <? echo $tit; ?></h1>
<?php 
if($setmot == ""){
	if($baj == 1){ 
		$txtcan = "Su turno fue cancelado con exito!";	
		$getcancel = TNet::canmailturno($empid,$turid,$usid,'usuario',$logid,0);
	}else if($baj == 2){ 
		$txtcan = "Su turno ya fue cancelado con anterioridad!";	
	}else if($baj == 3){ 
		$txtcan = "El turno que esta tratando de cancelar no existe!";	
	}else if($baj == 4){ 
		$getcandata = tcanresult($tiempo_de_cancelacion, $set_tday, $set_thor);
		$txtcan = "El Prestador solo perminte cancelar el turno hasta: ".$getcandata;	
	}else if($baj == 5){ 
		// $txtcan = "Ingrese el Código de Cancelación que aparece en su email para cancelar el Turno!";
                    $txtcan = 'Especificanos porque quieres cancelar tu turno!';
	}else if($baj == 10){ 
		$txtcan = "Código de Cancelación de Turno incorrecto!";	
	}
}else{
	if($baj == 1){ 
		$txtcan = "Su turno fue cancelado con exito!";	
		$getcancel = TNet::canmailturno($empid,$turid,$usid,'usuario',$logid,0);
	}else if($baj == 5){ 
		$txtcan = "Ingrese el Código de Cancelación que aparece en su email para cancelar el Turno!";	
	}else if($baj == 10){ 
		$txtcan = "Código de Cancelación de Turno incorrecto!";	
		$setmot = "";
		$baj = 5;
	}
}
?>

<div class="stit s16"><?php echo $txtcan; ?></div>
<?php if($setmot == "" && ($baj == 1 || $baj == 5)) { ?>
<div id="wspace">
<form action="" method="post" name="form">
<div class="setl">
<div class="tinfop2">Prestador
  <input name="setmot" type="hidden" id="setmot" value="2" />
</div>
<div class="tinrop2"><?php echo $setemp; ?></div>
</div>

<div class="setl">
  <div class="tinfop2">Fecha del Turno</div>
<div class="tinrop2"><div class="setfec"><?php echo fec($rowtur['tu_fec']); ?> 
        <span><?php echo hora($rowtur['tu_hora']); ?> hs.</span>
    </div></div>
</div>
<?php if($rowtur['tur_canid']!=''){ ?>
<!--
    <div class="setl">
<div class="tinfop2"><span class="pred">*</span> Código de Cancelación</div>
<div class="tinrop2">
    <input type="text" name="cod_can" id="cod_can" class="inp_u">
</div>
</div>
    -->
    <br>
    <div style="margin-left:5em" class="container">
    <div style="float:left" class="row">
        <div style="display:none" class="col">
            <input required placeholder="Código de cancelación" value="0" type="hidden" name="cod_can" id="cod_can" class="form-control">
        </div> 
        <div class="col">
            <input required placeholder="Motivo de cancelación" type="text" name="mot_can" id="mot_can" class="form-control">
        </div>
    </div>
    <br><br>
                
            <!--<div style="margin-top:2em !important" class="g-recaptcha" data-sitekey="6LfuGmAUAAAAAFCnH4tSXojSYS8tQoMp9FbrzTWI"></div>-->
<div data-callback="recaptchaCallback" style="margin-top:2em" id="html_element"></div>
<br> 
    <script  src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
    </div>
   
    <script>
    function recaptchaCallback() {
            $('#button').removeAttr('disabled');
        };
    </script>
    
<div class="setl">
<div class="tinfop2">&nbsp;</div>
<div class="tinrop2">&nbsp;</div>
</div>
<?php } ?>
<!--<div class="setl">
<div class="tinfop2"><span class="pred">*</span> Motivo de Cancelación</div>
<div class="tinrop2"><input type="text" name="mot_can" id="mot_can" class="inp_u"></div>
</div>-->
<div class="setl">
<div class="tinfop2">&nbsp;</div>
<div class="tinrop2">&nbsp;</div>
</div>
<div class="setl">
<div class="tinfop2">&nbsp;</div>
<div class="tinrop3">
    <!-- fsub -->
    <input disabled style="background-color: #ff9900; color:#fff; float:left" type="submit" name="button" id="button" value="Enviar" class="btn btn-block">
</div>
</div>
</form>
</div><?php } else if($setmot == 2 && $mot_can!=""){ ?>
<div class="txtc">Gracias!</div>
<?php } ?>
</div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
        </body>
    </html>