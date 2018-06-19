<?php
$rd = $_GET['rd'];
if($rd!=''){
	include_once ("../js/func.php");
	include_once ("../js/cambiocaracteres.php"); 
	include_once ("../js/class.php"); 
	include_once("../js/globalinc.php");
	include_once ("../js/loginpconf.php");
	
	if($_SERVER['REMOTE_ADDR'] =='181.171.232.97'){
	echo "NOMBRE2: ".$_COOKIE['tu_set_usu']."<br>";
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

	$getemp = mysql_query("select em_nomfan, em_tipo 
	from tu_emps where em_id='$empid' && (em_uscid='$usid' 
	|| em_id IN(select ma_empid from tu_manag where ma_empid='$empid' 
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
?>
<script>
$(document).ready(function() {
    	$('#list_pac').jScrollPane();
	
	var rand_no = Math.random();
	rd = rand_no * 1000;
	var ihome = "http://www.turnonet.com/";
	var empid = $('#empid').val();
	
	$(".pacadm").hover(
		function(){	$(this).stop().animate({backgroundColor: '#C30'});
		$('.chara', this).fadeIn(400); 
		$('.chdel', this).fadeIn(400); },
		function(){	$(this).stop().animate({backgroundColor: '#FFF'});
		$('.chara', this).fadeOut(400);
		$('.chdel', this).fadeOut(400);  }				
	);
	
	$('.lodin2').click(function(){		
			var turid = $(this).attr('id');	
			var url = ihome+"fs/load_pacdat.php?tusid="+turid+"&empid="+empid+"&rd="+rd;
			if($('#lod_tuid').val() == turid){ $('#lod_tuid').val(''); }
			if($('#lod_tuid').val() == ''){
				$('#usdata').slideToggle('slow', function () {				
					$("#showd").load(url);
					$('#lod_tuid').val(turid);
				});
			}else{
				$("#showd").load(url);
				$('#lod_tuid').val(turid);
			}
	});
	$(".lpact2").click(function(evento) {
		evento.preventDefault();
		var rand_no = Math.random();
		rd = rand_no * 1000;
		var ihome = "http://www.turnonet.com/";
		var bu_s = $(this).attr('id');
		var em_s = $('#empid').val();
		var us_s = $('#usid').val();
		var err; 
		if (bu_s == ""){
			err = 1;
		}		
		if(err == 1){ 
			return false;
		}		
		var url = ihome+"fs/list_tur.php?pacid="+bu_s+'&empid='+em_s+'&usid='+us_s+'&rd='+rd;
		$("#listur").load(url,function(){			
			 $('#listur').show('slide', { direction: "up" },400);
		});
	});
	
	$('.delpac').click(function(){		
		var turid = $(this).attr('id');	
		var url = ihome+"fs/load_pacdel.php?tusid="+turid+"&empid="+empid+"&rd="+rd;
		if($('#lod_tuid').val() == turid){ $('#lod_tuid').val(''); }
		if($('#lod_tuid').val() == ''){
			$('#usdata').slideToggle('slow', function () {				
				$("#showd").load(url);
				$('#lod_tuid').val(turid);
			});
		}else{
			$("#showd").load(url);
			$('#lod_tuid').val(turid);
		}
	}); 
}); 	
</script>
    <?php } ?>
        <script>
        $(document).ready( function() {
           $.ajax({
              url:'http://www.turnonet.com/es/app/controllers/buscador.php?pac_nom=<?php echo str_replace(';',' ', $_GET['val']) ?>&empid=<?php echo $_GET['empid'] ?>',
              method:'GET',
              cache: false,
              success: function(XMR_SEARCH) {
                 // $('#list_pac').html(XMR_SEARCH);
                  console.log(XMR_SEARCH);
              },
              error: function(XMR_ERR) {
                  console.error(XMR_ERR);
              }
           });
        });
        </script>
<div id="list_pac" class="scroll-pane"><p><?php
if($rd!='' && $val!=''){
	//|| ud_emalt like '%$val%'
	$val = str_replace(";"," ",$val);
	if($_SERVER['REMOTE_ADDR'] == '181.171.232.97'){
		echo utf8_encode($val);
	}
        //echo $val;
        if (ctype_digit((int)$val) && (strlen($val >= 7))) {
              $addfilters = " (us_dni LIKE '%$val%')&& ";
        } else {
         if (filter_var($val, FILTER_VALIDATE_EMAIL)) {
             $addfilters = " (us_mail LIKE '%$val%')&& ";
         } else {
              $addfilters = " (us_nom LIKE '%$val%') && ";             
         }
        }
 	//$addfilters = " (us_nom like '%$val%' || us_mail like '%$val%' || us_dni like '%$val%') && ";        
         

}else if($rd!='' && $val2!=''){
	$val2 = explode("_",$val2);
	$val2 = $val2[1];
	$addfilters = " us_nom like '$val2%' && ";
}

$getpa = @mysql_query("SELECT *, (select count(*) from tu_turnos WHERE emp_id='$empid' && us_id=us.us_id && tu_estado='ALTA') totu FROM tu_users us left join tu_usdat ud on us_id=ud.ud_usid "
        . "WHERE ".$addfilters." (us_id IN(SELECT tp_usid FROM tu_emp_cli WHERE tp_empid='$empid' && tp_usid=us.us_id) || us_id IN(SELECT us_id FROM tu_turnos 
WHERE emp_id='$empid' && us_id=us.us_id)) order by us_nom ", $conn);
if($_SERVER['REMOTE_ADDR'] == '181.171.230.210'){
			/*echo "SELECT *, (select count(*) from tu_turnos WHERE emp_id='$empid' && us_id=us.us_id && tu_estado='ALTA') totu FROM tu_users us left join tu_usdat ud on us_id=ud.ud_usid WHERE ".$addfilters." (us_id IN(SELECT tp_usid FROM tu_emp_cli WHERE tp_empid='$empid' && tp_usid=us.us_id) || us_id IN(SELECT us_id FROM tu_turnos 
WHERE emp_id='$empid' && us_id=us.us_id)) order by us_nom ";*/
		}
$rowspa = mysql_num_rows($getpa);
if($rowspa == 0){
	echo "no se encontraron pacientes.<br />";
	if($rd!=''){ echo "Busqueda: ".$val; }
}

while($datpa = mysql_fetch_assoc($getpa)){
	$titu = " turnos ";
	if($datpa['totu'] == 1){
		$titu = " turno ";
	}
?>
      <?php
      /*
       * Array ( [val] => Rodolfo;Alejandro [empid] => 1456 [usid] => 376603 [rd] => 15.832757222915994 )
       */
      
      ?> 
    <div  id="PAC_AJAX">
        
    </div>
<div class="pacadm">
    
<?php if($datpa['totu'] == 0){ ?>
    <div class="chdel">
<a href="javascript:void(0)" title="Eliminar <?php echo ucwords($tip_cor); ?>" class='delpac' id="tuid_<?php echo $datpa['us_id']; ?>">
    <img src="<?php echo $rootdir; ?>imagenes/menu/ic_del.png" width="25" height="25" class="img_b">
</a>
</div><?php } ?>
<div class="chara">
    <a href="javascript:void(0)" class="lpact2" id="<?php echo $datpa['us_id']; ?>">
        <?php echo $datpa['totu'].$titu; ?>
    </a>

<?php if($_SERVER['REMOTE_ADDR'] == '181.171.232.97' || $empid == '929'){ ?>
<a href="<?php echo $rootdir; ?>administrar-agendas/v2/imppres/<?php echo $usid; ?>/<?php echo $datpa['us_id']; ?>/<?php echo $empid; ?>" 
   title="Imprimir Datos del <?php echo ucwords($tip_cor); ?>" 
   target="_blank" id="impdid_<?php echo $datpa['us_id']; ?>">
    <img src="<?php echo $rootdir; ?>imagenes/menu/ic_print.png" width="25" height="25" class="img_b">
</a>
<?php } ?>

<a href="javascript:void(0)" 
   title="Datos del Paciente" 
   class='lodin2' id="tuid_<?php echo $datpa['us_id']; ?>">
    <img src="<?php echo $rootdir; ?>imagenes/menu/ic_usp.png" width="25" height="25" class="img_b">
</a>
    <a  href="javascript:void(0)" 
       title="Seleccionar para Carga de Turno" 
       class="tooltip" 
       onClick="showu('<?php echo $datpa['us_id']; ?>','<?php echo utf8_encode($datpa['us_nom']); ?>','<?php echo $datpa['us_mail']; ?>')">
    <img src="<?php echo $rootdir; ?>imagenes/menu/ic_ctur.png" width="25" height="25" class="img_b">
    </a>
</div>
<?php  echo utf8_encode($datpa['us_nom']);
	echo "<br />";
	echo $datpa['us_mail'];
	echo "<br />";echo "<br />";
?>
</div><?php } ?></p>
</div>