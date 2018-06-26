<?php
$rd = $_GET['rd'];
if($rd!=''){
	include_once ("../js/func.php");
	include_once ("../js/cambiocaracteres.php"); 
	include_once ("../js/class.php"); 
	include_once("../js/globalinc.php");
	include_once ("../js/loginpconf.php");
        include_once '../es/app/controllers/userAdmin.php';
//        include_once '../es/app/model/config.php';
//        include_once '../es/app/model/connection.php';
	if($_COOKIE['tu_set_usu']==""){
		echo "la sesion a expirado! ingrese nuevamente al sistema para poder seguir administrando. 
		gracias.";
		exit();
	}
	$tipc = TNet::tipus($empid, 'empid');
	if($tipc === " | DEMO "){ $conn = TNet::conectarD(); }else if($tipc === "pos"){ $conn = TNet::conectar(); }
	$rootdir = "http://www.turnonet.com/";
	$lang = "es/";
	$hoy = date("Y-m-d");

	$getemp = mysql_query("select em_nomfan from tu_emps where em_id='$empid' && (em_uscid='$usid' 
	|| em_id IN(select ma_empid from tu_manag where ma_empid='$empid' 
	&& ma_usuid='$usid') ) ", $conn);
	$rowemp = mysql_num_rows($getemp);
	if($rowemp == 0){
		echo "No posee permisos para administrar esta empresa.";
		exit();
	}
?><script>
$(function(){	
<?php if($rd!=''){ ?>
		var rand_no = Math.random();
		rd = rand_no * 1000;
		var ihome = "http://www.turnonet.com/";
		var empid = $('#empid').val();
		var filtid = $('#bus_fil').val();
<?php } ?>		
	$('#list_tur').jScrollPane();
	
	$(".turadm").hover(
		function(){	$(this).stop().animate({backgroundColor: '#C30'});
		$('.chara', this).fadeIn(400); },
		function(){	$(this).stop().animate({backgroundColor: '#FFF'});
		$('.chara', this).fadeOut(400); }				
	);
<?php if($rd!=''){ ?>		
//CANCELACION
		$('.turcanc').click(function(){
			var turid = $(this).attr('id');	
			var exid = turid.split('_');
			var rr = exid[1];
			
			var url = ihome+"fs/load_turcan.php?empid="+empid+"&turid="+rr+"&rd="+rd;
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
		$('.lodin').click(function(){		
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
<?php } ?>	
});
</script><?php } ?><div id="list_tur" class="scroll-pane"><p>
<?php
if($rd!=""){
	if($pacid!=''){
		$add_opt = " && t.us_id='$pacid' "; 
	}
	if($fect==1){
		$seldate = date("Y-m-d");
		$add_opt = " && t.tu_bloqfec='$seldate' "; 
	}
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
}
	
	if($_SERVER['REMOTE_ADDR'] == '181.171.232.97'){
	echo "SELECT uma.usm_gen3 dato,t.*,e.*,s.*,p.*,u.*,ud.* FROM tu_turnos t  
	left join tu_emps e on e.em_id=t.emp_id 
	left join tu_emps_suc s on s.suc_id=t.suc_id 
	left join tu_tmsp p on p.tmsp_id=t.pres_id 
	left join tu_users u on u.us_id=t.us_id
	left join tu_ususmd uma on t.tu_id=uma.usm_turid
	left join tu_usdat ud on u.us_id=ud.ud_usid	
	WHERE t.emp_id='$empid' ".$add_opt." order by tu_fec, tu_hora";
	echo "<br><br>FILTROS: ".$add_opt."<br>";
    }
	
if($rowtur == 0){
	echo $txt_cero;
}
while($data = mysql_fetch_assoc($sql)){
                   	
	//$sqlmd = mysql_query("SELECT usm_gen3 dato FROM tu_ususmd  
	//WHERE usm_turid='".$data['tu_id']."' ", $conn);
	//$datamd = mysql_fetch_assoc($sqlmd);
	//echo $datamd['dato'];
		$setemp = $data['tmsp_pnom']." - ".$data['suc_nom'];
		if($rd!=''){
			$setemp = utf8_encode($setemp);
		}
		$img = $data['ud_pfot'];
		$fotk = "usuarios";
		if($img == ""){
			$fotk = "prestadores";
			$img = "empty.jpg";
		}
		
		// VERIFICO QUIEN CARGO EL TURNO            
		$set_cargadopor = '';
		if($data['tu_carga'] != 0){
			$set_cargadopor .= 'usuario';
		}else if($data['tu_carga'] == 0 && $data['tu_usadm']!=0){
			$get_usad = mysql_query("SELECT * FROM tu_users_admins 
			WHERE us_id='".$data['tu_usadm']."' ", $conn);
			
			$dat_usad = mysql_fetch_assoc($get_usad);
			$set_cargadopor .= 'prestador ('.$dat_usad['us_nom'].')';
		}else if($data['tu_carga'] == 0){
			$set_cargadopor .= 'administrador';
		}
                /*
                 * Modificacion para saber quien cancelo el turno
                 */
		// echo $data['tu_id'];
		//VERIFICO QUIEN CANCELO EL TURNO
                //		$set_canpor = '';
                // tu_usadm
		$set_canpor = '';
		if($data['tu_estado']=='BAJA'){
			$get_usca = mysql_query("SELECT * FROM tu_tucan 
			WHERE tucan_turid='".$data['tu_id']."' ", $conn);
			
			$dat_usca = mysql_fetch_assoc($get_usca);
			$set_canpor .= "".$dat_usca['tucan_tipo']." ";
			if($dat_usca['tucan_mot']!=''){
				$set_canpor .= ' ('.$dat_usca['tucan_mot'].')';
			}
		}
                /*
                 * Verificación si es un ADMIN O NO
                 */              
//                $set_canpor = '';
//                if (strcasecmp($data['tu_estado'], 'BAJA') == 0) {
//                    if ($data['tu_usadm'] != 0) {
//                        // Entonces cargo un administrador       
//                        $admin = new userAdmin();
//                        $isAdmin = $admin->VerifyAdmin($data['tu_usadm']);
//                        if ($isAdmin != null) {
//                            // Entonces encontro data
//                            foreach ($isAdmin as $adminData) {
//                                $get_usca = mysql_query("SELECT * FROM tu_tucan 
//                                WHERE tucan_turid='".$data['tu_id']."' ", $conn);
//                                $dat_usca = mysql_fetch_assoc($get_usca);
//                                //$set_canpor .= "".$dat_usca['tucan_tipo']." ";
//                                $set_canpor .= "administrador {$adminData->us_nom}";
//                                  if($dat_usca['tucan_mot']!=''){
//                                    $set_canpor .= ' ('.$dat_usca['tucan_mot'].')';
//                                }
//                            }
//                        }
//                    } else if ($data['tu_carga'] == 0) { // LO CARGA EL DUEÑO
//                        $get_usca = mysql_query("SELECT * FROM tu_tucan 
//			WHERE tucan_turid='".$data['tu_id']."' ", $conn);
//			
//			$dat_usca = mysql_fetch_assoc($get_usca);
//			$set_canpor .= "administrador";
//			if($dat_usca['tucan_mot']!=''){
//                          $set_canpor .= ' ('.$dat_usca['tucan_mot'].')';
//			}
//                    } else {
//                        // USUARIO
//                        $get_usca = mysql_query("SELECT * FROM tu_tucan 
//			WHERE tucan_turid='".$data['tu_id']."' ", $conn);
//			
//			$dat_usca = mysql_fetch_assoc($get_usca);
//			$set_canpor .= "".$dat_usca['tucan_tipo']." ";
//			if($dat_usca['tucan_mot']!=''){
//				$set_canpor .= ' ('.$dat_usca['tucan_mot'].')';
//			}
//                    }
//                }
		
		//VERIFICO SI FUE ATENDIDO
		$set_aten = '';
		if($data['tu_asist']==1){
			$set_aten = 'ATENDIDO';
		}
		
		//if($_SERVER['REMOTE_ADDR'] == '186.22.160.151'){
			if($empid != 21){
				$servic_s = $data['tu_servid'];	
				$servics = '';
				if($servic_s!='' && $servic_s!=0){
					$bus_servs = str_replace("-",",",$servic_s); 
					$bus_servs = rtrim($bus_servs,","); 
					
					$gets = mysql_query("select serv_nom from tu_emps_serv 
					where serv_id IN($bus_servs) order by serv_nom ", $conn);
					/*if($_SERVER['REMOTE_ADDR'] == '181.171.232.97' && $data['tu_code']=='115517764243018'){
						echo "select serv_nom from tu_emps_serv 
					where serv_id IN($bus_servs) order by serv_nom ";
					}*/
					while($datserv = mysql_fetch_assoc($gets)){
						$servics .= $datserv['serv_nom']." | ";
					}
				}
			}
		//}
		$set_car = utf8_decode($set_cargadopor);
		$set_can = utf8_decode($set_canpor);
		if($rd!=""){
			$set_car = $set_cargadopor;
			$set_can = $set_canpor;
		}
?>
<div class="turadm" id="turi<?php echo $data['tu_id']; ?>">
<?php /*  && $data['tu_estado']!='BLOQUEADO' */ 
//if($_SERVER['REMOTE_ADDR'] == '181.171.232.97' || $empid=666){
?>
<?php //if($empid==666 || $empid==1209 || $empid==1011){ ?>
<div class="carpre"><?php echo $set_car; ?></div>
<?php if($set_canpor!=''){ ?>
<div class="canpre"><?php echo $set_can; ?></div>
<?php } ?>
<?php //} ?>
<?php if($set_aten!=''){ ?>
<div class="carate"><?php echo $set_aten; ?></div>
<?php } ?>
<div class="chara"><?php echo $data['tu_code']; ?>
    <?php if(($data['tu_estado']!='BAJA' && $data['tu_estado']!='BLOQUEADO') && $data['tu_asist']!=1){ ?>
    <a href="javascript:void(0)" title="Cancelar Turno" class="turcanc" id="tca_<?php echo $data['tu_id']; ?>">
        <img src="<?php echo $rootdir; ?>imagenes/menu/ic_canc.png" width="25" height="25" class="img_b">
    </a><?php } ?><?php if($data['tu_estado']=='BLOQUEADO'){ ?><a href="javascript:void(0)" title="Desbloquear Turno" class="turdbc" id="dbca_<?php echo $data['tu_id']; ?>">
        <img src="<?php echo $rootdir; ?>imagenes/menu/ic_canc.png" width="25" height="25" class="img_b"></a><?php } ?><br /><br />
<?php if($data['tu_asist']!=1){ ?>
        <a href="javascript:void(0)" title="Reasignar Fecha del Turno" 
   onClick="reatu('<?php echo $data['us_id']; ?>','<?php echo $data['us_nom']; ?>','<?php echo $data['tu_id']; ?>','<?php echo $data['tu_estado']; ?>')" >
            <img src="<? echo $rootdir; ?>imagenes/menu/ic_reas.png" width="25" height="25" class="img_b">
        </a><?php } ?><a href="javascript:void(0)" title="Agendar Nuevo Turno" 
                 onClick="showu('<?php echo $data['us_id']; ?>','<?php echo $data['us_nom']; ?>','<?php echo $data['us_mail']; ?>')">
                 <img src="<?php echo $rootdir; ?>imagenes/menu/ic_ctur.png" width="25" height="25" class="img_b">
</a>
        <a href="javascript:void(0)" title="Datos del Paciente" class='lodin' id="tuid_<?php echo $data['us_id']; ?>_<?php echo $data['tu_id']; ?>">
            <img src="<?php echo $rootdir; ?>imagenes/menu/ic_usp.png" width="25" height="25" class="img_b"></a>
            <?php if($data['tu_asist']!=1 && $data['tu_estado']=='ALTA' && $data['tu_fec']<=$hoy){ ?>
        <a href="javascript:void(0)" title="Confirmar Asistencia" class="confas" id="tucon_<?php echo $data['tu_id']; ?>">
            <img src="<?php echo $rootdir; ?>imagenes/menu/ic_conf.png" width="25" height="25" class="img_b"></a>
            <?php }else if($data['tu_asist']==1 && $data['tu_estado']=='ALTA'){ ?>
        <a href="javascript:void(0)" title="Atendido" id="tucon_<?php echo $data['tu_id']; ?>">
            <img src="<?php echo $rootdir; ?>imagenes/menu/icg_conf.png" width="25" height="25" class="img_b"></a>
            <?php } ?><a href="<?php echo $rootdir; ?>administrar-agendas/v2/imprimir/<?php echo $usid; ?>/<?php echo $data['us_id']; ?>/<?php echo $data['emp_id']; ?>/<?php echo $data['suc_id']; ?>/<?php echo $data['pres_id']; ?>/<?php echo $data['tu_id']; ?>" target="_blank" title="Imprimir Datos Paciente" id="tuprt_<?php echo $data['tu_id']; ?>">
                <img src="<?php echo $rootdir; ?>imagenes/menu/ic_print.png" width="25" height="25" class="img_b"></a>
</div>
<?php /*<div class="fost"><div class="fot"><img src="<? echo $rootdir; ?>fotos/<? echo $fotk; ?>/<? echo $img; ?>" width="50"></div></div>*/ ?>
<div class="setfec"><?php if($data['tu_estado']=='BAJA'){ ?><a href="javascript:void(0)" title="Turno Cancelado">
        <img src="<?php echo $rootdir; ?>imagenes/menu/icr_canc.png" width="25" height="25" class="img_b"></a><?php } ?><?php echo fec($data['tu_fec']); ?> <span><?php echo hora($data['tu_hora']); ?> hs.<?php if($data['tu_estado']=='BAJA'){ echo " | CANCELADO"; } ?><?php if($data['tu_estado']=='BLOQUEADO'){ echo " | CANCELADO | HORARIO BLOQUEADO"; } ?><?php if($data['tu_st'] == 1){ echo " | SOBRETURNO"; } ?></span></div>
<div class="setpres"><?php echo $data['us_nom']; ?></div>
<div class="setfidir"><?php echo $setemp; ?></div>
<div class="setfidir">Duraci&oacute;n Estimada: <?php echo hora($data['tu_durac']); ?> hs. <?php if($data['emp_id']==666 && $data['usm_gen3']!=''){ echo "(".$data['usm_gen3'].")"; } ?></div>
<?php if($servics!=''){ echo $servics; } ?>
</div>
<?php } ?>
</p></div>