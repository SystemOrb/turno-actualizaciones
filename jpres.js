/*Jquery.noconflict();*/
	//VM
	function isVEA(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
	};
	
	function pacadt(){
		$('#usdata').css({display: "none"});
		$('#isucce').css({display: "none"});
		$('#ptnxtu').css({display: "none"});
		var rand_no = Math.random();
		rd = rand_no * 1000;
		var ihome = "http://www.turnonet.com/";
		var empid = $('#empid').val();
		var filtid = $('#bus_fil').val();
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
		$('.lodnpc').click(function(){		
			//var turid = $(this).attr('id');	
			var url = ihome+"fs/load_pacnw.php?empid="+empid+"&rd="+rd;
			$('#lod_tuid').val('');
			if($('#lod_tuid').val() == ''){
				$('#usdata').slideToggle('slow', function () {				
					$("#showd").load(url);
				});
			}else{
				$("#showd").load(url);
			}
		});
		$('.lodou').click(function(){
			$('#lod_tuid').val('');
			$('#usdata').slideToggle('slow');
		});
		$('.confas').click(function(){
			var turid = $(this).attr('id');	
			var exid = turid.split('_');
			var rr = exid[1];
			
			var url = ihome+"fs/load_exit.php?pag=pconftur&empid="+empid+"&turid="+rr+"&rd="+rd;
			$("#showsucce").load(url,function(){	
				$('#isucce').slideToggle('slow',function(){	
						$('#turi'+rr).slideUp('slow',function(){	
							$('#isucce').delay(900).slideUp(300);
						});	
				});					
			});
 
		});
		
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
		
		$('.turdbc').click(function(){
			var turid = $(this).attr('id');	
			var exid = turid.split('_');
			var rr = exid[1];
			
			var url = ihome+"fs/load_turdb.php?empid="+empid+"&turid="+rr+"&rd="+rd;
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
		
		$(".newt").click(function(evento){
			evento.preventDefault();
			var ustip = $('#tur_tip').val();	
			var usid = $('#tur_usid').val();	
			var empid = $('#empid').val();	
			var sucid = $('#sucid').val();	
			var presid = $('#presid').val();	
			var turfec = $('#tur_fec').val();	
			var turhor = $('#tur_hora').val();
			var turser = $('#tu_servs').val();
			var turdur = $('#tu_durac').val();
			var tusadm = $('#tu_usadm').val(); //USER ADM
			if($("#tur_st").is(":checked") == true){
				tur_st = 1;
			}else{ tur_st = 0; }
			var tur_ra = $('#tur_reag').val();
			var pr_ts = $('#pr_ts').val(); //TSIM
			tipo = " Paciente ";
			if(ustip != 1){ tipo = " Cliente "; }
			if(usid == ""){
				alert("Seleccione el "+tipo+" a agendar el turno."); return false;
			}
			if(sucid == "" || presid == ""){
				alert("Seleccione al prestador al cual desee asignar el turno."); return false;
			}
			if(turfec == ""){
				alert("Seleccione la fecha del turno."); return false;
			}else{
				validhor = valhor(turfec);	
				if(validhor == false){ return validhor; }
			}
			if(turhor == ""){
				alert("Seleccione la hora del turno."); return false;
			}else{
				validtim = valtim(turhor);	
				if(validtim == false){ return validtim; }
			}	
			if(empid=='115' && turser==''){
				alert("Seleccione un servicio para agregar el turno!"); return false;
			}
			//var rand_no = Math.random();
			var rd = 1 + Math.floor(Math.random() * 100);
			
			var url = ihome+"fs/load_exit.php?pag=cntur&empid="+empid+"&sucid="+sucid+"&presid="+presid+"&uid="+usid+"&tfec="+turfec+"&thor="+turhor+"&st="+tur_st+"&pts="+pr_ts+"&rag="+tur_ra+"&ts="+turser+"&td="+turdur+"&rd="+rd;
			//alert (url);
			//return false;
			
			/*$("#showsucce").load(url,function(){	
				$('#isucce').slideToggle('slow',function(){		
					$('#isucce').delay(5000).slideUp(300);
				});	
			});*/
			$.ajax({ 
				data: {pag:'cntur',empid:empid,sucid:sucid,presid:presid,uid:usid,tfec:turfec,thor:turhor,ts:turser,td:turdur,st:tur_st,pts:pr_ts,rag:tur_ra,tuadm:tusadm,rd:rd}, 
				type: 'POST', 
				url: ihome+'fs/load_exit.php', 
				beforeSend: function(){
					$('#totpg').fadeIn(200);					
				},
				success: function(response) { 
					$('#tur_fec').val('');
					$('#tur_hora').val('');					
					$('#tu_durac').val('');
					$('#pres_usid').val('');
					$('#tur_usid').val('');	
					$('#ures').html('');
					$('#set_servicios').html('Servicios: -');
					$('#set_dura').html('Duraci\u00f3n Estimada: -');
					$('#tur_reag').val('');
					$('#tu_servs').val('');
					$('#lodinf').html('<div class="info"><b>Fecha: -</b></div><div class="txt">Seleccion\u00e1 una fecha para ver los horarios de turnos disponibles.</div>');
					if(document.getElementById('set_serv')){
						$('#addeds').html('<span>No tenes servicios seleccionados</span>');
						document.getElementById('set_serv').value = '';
						$('#lodinf').html('<div class="info"><b>Fecha: -</b></div><div class="txt">Seleccion\u00e1 una fecha para ver los horarios de turnos disponibles.</div>');
						$('#set_serv_sel').val('');
					}
					$('#totpg').fadeOut(200);
					$('#showsucce').html("<div class='green'>El Turno ha sido cargado con exito!</div>");					
					$('#isucce').slideToggle('slow',function(){		
						$('#isucce').delay(5000).slideUp(300);
					});
				},
			  error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			  }
			});
		});
		$(".newtlim").click(function(evento){
			$('#tur_fec').val('');
			$('#tur_hora').val('');
			$('#tu_servs').val('');
			$('#tu_durac').val('');
			$('#pres_usid').val('');
			$('#tur_usid').val('');	
			$('#ures').html('');
		});
		
		$('.nxtur').click(function(){
			var sucid = $('#sucid').val();	
			var presid = $('#presid').val();	
			var url = ihome+"fs/load_nxtur.php?empid="+empid+"&sucid="+sucid+"&presid="+presid+"&rd="+rd;
			$("#shownxtur").load(url,function(){	
				$('#ptnxtu').slideToggle('slow');				
			});
 
		});	
		
		/*$('.nxhor').click(function(){
			var sucid = $('#sucid').val();	
			var presid = $('#presid').val();	
			var url = ihome+"fs/load_nxtur.php?empid="+empid+"&sucid="+sucid+"&presid="+presid+"&rd="+rd;
			$("#shownxtur").load(url,function(){	
				$('#ptnxtu').slideToggle('slow');				
			});
 
		});	*/
		
		$('.nxnots').click(function(){
			var sucid = $('#sucid').val();	
			var presid = $('#presid').val();	
			var url = ihome+"fs/load_nxnots.php?empid="+empid+"&sucid="+sucid+"&presid="+presid+"&rd="+rd;
			$("#shownxnot").load(url,function(){	
				$('#ptnxnot').slideToggle('slow');				
			});
 
		});	
		
		$('.newreg').click(function(){
			var sucid = $('#sucid').val();	
			var presid = $('#presid').val();	
			var bu_s = $(this).attr('id');
			var exid = bu_s.split('_');
			var rr = exid[1];
			var rn = exid[2];
			if($('#regn').length > 0){
				$('#rg_pac').val(rr);
				//$('#rg_pacv').val(rn);
				document.getElementById('rg_pacv').innerHTML = rn;
				return false;			
			}
			var url = ihome+"fs/load_regnew.php?empid="+empid+"&sucid="+sucid+"&presid="+presid+"&pacid="+rr+"&rd="+rd;
			$("#newreg").load(url,function(){	
				$('#newreg').show('slide', { direction: "up" },400);				
			});
 
		});	
		$('.vreg').click(function(){		
			var regid = $(this).attr('id');	
			var url = ihome+"fs/load_viewreg.php?regid="+regid+"&empid="+empid+"&rd="+rd;
			$("#lcari").load(url,function(){	
				$('#lcari').fadeIn(600);		
			});
		});	
		$('.oreg').click(function(){		
				$('#lcari').fadeOut(600);	
		});		
		
		//mostrar calendario
		$('.lodmecal').click(function(){		
			//var turid = $(this).attr('id');
			$('.setical').slideToggle('slow', function () {				
				//$("#showd").load(url);
			});
		});
		$('.lodmeclos').click(function(){
			$('.setical').slideToggle("slow");
		});	
	}
		
	//TT
	this.tooltip = function(){	
		/* CONFIG */		
			xOffset = 45;
			yOffset = -80;		
			// these 2 variable determine popup's distance from the cursor
			// you might want to adjust to get the right result		
		/* END CONFIG */		
		$("a.tooltip").hover(function(e){											  
			this.t = this.title;
			this.title = "";	
			var anre = this.t.length;
			var count = this.t.match(/<br/g);
			if(count != null){
				var np2 = eval(count.length)+1;
				var numplus = eval(count.length);
				xOffset = 45+(numplus*20);
				var anre = anre/np2;
			}
			var totar = (anre*6);				
			yOffset = -((totar/2))+15;
			//alert(yOffset);
			var maxan = eval(e.pageX-yOffset);
			var porct = "45%";
			if(maxan>980 && screen.width<=1024){
				yOffset = yOffset-((maxan-970));	
				porct = "85%";	
			}					  
			$("body").append("<p id='tooltip'>"+ this.t +"</p>");
			$("#tooltip")
				.css("top",(e.pageY - xOffset) + "px")
				.css("left",(e.pageX + yOffset) + "px")
				.fadeIn("fast");				
		},
		function(){
			this.title = this.t;		
			$("#tooltip").remove();
		});	
		$("a.tooltip").mousemove(function(e){
			$("#tooltip")
				.css("top",(e.pageY - xOffset) + "px")
				.css("left",(e.pageX + yOffset) + "px");
		});			
	};
/*
 * AQUI VA LA BUSQUEDA
 */	
$(document).ready(function(){

	$("#buspac").click(function(evento) {
		evento.preventDefault();
		var rand_no = Math.random();
		rd = rand_no * 1000;
		var ihome = "http://www.turnonet.com/";
		//alert($('#pac_nom').val());
		var bu_s = $.trim($('#pac_nom').val());
		var bu_s = bu_s.replace(" ", ";");
		var bu_s = bu_s.replace(" ", ";");
		//alert(bu_s);
		var em_s = $('#empid').val();
		var us_s = $('#usid').val();
                var timeBy = $('#getHour').val();
                var canSum = $('#canSum').val();
		var err; 
		if (bu_s == ""){
			err = 1;
		}		
		if(err == 1){ 
			alert("ingrese al menos 4 caracteres para poder realizar la busqueda!");
			return false;
		}		
		var url = ihome+"fs/list_pac2.php?val="+bu_s+'&empid='+em_s+'&usid='+us_s+'&rd='+rd+'&time='+timeBy+'&canSum='+canSum;
		$("#listpac").load(url,function(){			
			 $('#listpac').show('slide', { direction: "up" },400);
		});
	});
	$(".letre").click(function(evento) {
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
		var url = ihome+"fs/list_pac2.php?val2="+bu_s+'&empid='+em_s+'&usid='+us_s+'&rd='+rd;
		$("#listpac").load(url,function(){			
			 $('#listpac').show('slide', { direction: "up" },400);
		});
	});
	$(".letrr").click(function(evento) {
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
		var url = ihome+"fs/list_pacreg.php?val2="+bu_s+'&empid='+em_s+'&usid='+us_s+'&rd='+rd;
		$("#listpac").load(url,function(){			
			 $('#listpac').show('slide', { direction: "up" },400);
		});
	});
	$(".lpact").click(function(evento) {
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
	$(".ltchoy").click(function(evento) {
		evento.preventDefault();
		var rand_no = Math.random();
		rd = rand_no * 1000;
		var ihome = "http://www.turnonet.com/";
		var em_s = $('#empid').val();
		var us_s = $('#usid').val();
		var err; 
		if(err == 1){ 
			return false;
		}		
		var url = ihome+'fs/list_tur.php?empid='+em_s+'&usid='+us_s+'&fect=1&rd='+rd;
		$("#listur").load(url,function(){			
			 $('#listur').show('slide', { direction: "up" },400);
		});
	});
	$('.delpac').click(function(){		
		var rand_no = Math.random();
		rd = rand_no * 1000;
		var ihome = "http://www.turnonet.com/";		
		var turid = $(this).attr('id');	
		var em_s = $('#empid').val();
		var url = ihome+"fs/load_pacdel.php?tusid="+turid+"&empid="+em_s+"&rd="+rd;

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
	$(".lregt").click(function(evento) {
		evento.preventDefault();
		var rand_no = Math.random();
		rd = rand_no * 1000;
		var ihome = "http://www.turnonet.com/";
		var bu_s = $(this).attr('id');		
		var em_s = $('#empid').val();
		var sucid = $('#sucid').val();
		var presid = $('#presid').val();
		var err; 
		if (bu_s == ""){
			err = 1;
		}		
		if(err == 1){ 
			return false;
		}		
		var url = ihome+"fs/list_reg.php?pacid="+bu_s+'&empid='+em_s+'&sucid='+sucid+'&presid='+presid+'&rd='+rd;
		$("#newreg").load(url,function(){			
			 $('#newreg').show('slide', { direction: "up" },400);
		});
	});
	$(".stpg").click(function(evento) {
		evento.preventDefault();
		var rand_no = Math.random();
			rd = rand_no * 1000;
		var ihome = "http://www.turnonet.com/";
		var bu_s = $(this).attr('id');		
		var pg = bu_s.split("_");
			pg = pg[1];
		var em_s = $('#r_empid').val();
		var sucid = $('#r_sucid').val();
		var presid = $('#r_presid').val();
		var pacid = $('#r_pacid').val();
		var err; 
		if (bu_s == ""){
			err = 1;
		}		
		if(err == 1){ 
			return false;
		}		
		var url = ihome+"fs/list_reg.php?pacid="+pacid+'&empid='+em_s+'&sucid='+sucid+'&presid='+presid+'&pg='+pg+'&rd='+rd;
		$("#newreg").load(url,function(){			
			 $('#newreg').show('slide', { direction: "up" },400);
		});
	});
	// CARGA HORARIOS DEL DIA
	$(".preshr").click(function(evento){
		evento.preventDefault();
		var reff = $(this).attr('href');	
		var empi = $('#empid').val();	
		if($('#set_serv').length){
			var servis = $('#set_serv_sel').val();
			if(servis == ""){
				if(empi == 115){
					alert("Debe seleccionar un servicio para poder continuar.");
					return false;
				}
			}else{
				reff += "/serv/"+servis;
			}
		}
		$('#lodinf').html('<p><img src="http://www.turnonet.com/frame/imagenes/loader.gif" width="16" height="16" class="img_b" /></p>');
		
		$("#lodinf").load(reff,function(){			
			$('#lodinf').show('slide', { direction: "up" },400);
		});
		return false;
	});
	
	$(".relcal").click(function(evento){
		evento.preventDefault();		
		$('#relocal').html('<div class="load"><img src="http://www.turnonet.com/frame/imagenes/loader.gif" width="16" height="16" class="img_b" /></div>');
		$("#relocal").load($(this).attr('href'),function(){			
			$('#relocal').show('slide', { direction: "left" },400);
		});
		return false;
	});
	
	tooltip();
	pacadt();
});	

	// CARGA de SERVICIOS
	function addserv(empid,valor){
		if(valor!=""){
			if($('#set_serv').length){
				var servis = $('#set_serv_sel').val();
				if(servis == ""){
					var addserv = valor;				
				}else{
					var addserv = servis+"-"+valor;			
				}				
				$('#set_serv_sel').val(addserv);
				//alert("llego "+$('#set_serv_sel').val());
				var reff = "http://www.turnonet.com/ctur/servs/"+empid+"/"+addserv;
				$("#addeds").load(reff,function(){			
					$('#addeds').fadeIn('slow');
					$('#lodinf').html('<b>Fecha: -</b><br />Seleccion\u00e1 una fecha para ver los horarios de turnos disponibles.');
					$('#set_servicios').html('Servicios: -');
					$('#tu_servs').val('');		
					$('#set_dura').html('Duraci\u00f3n Estimada: -');
					$('#tu_durac').val('');
					$('#tur_fec').val('');
					$('#tur_hora').val('');
				});
			}
		}
		return false;
	};
	// BORRO de SERVICIOS
	function delserv(empid,valor){
		if(valor!=""){
			if($('#set_serv_sel').length){
				var servis = $('#set_serv_sel').val();
				servis = " "+servis+" ";
				servis = servis.replace(" "+valor+"-", "");
				servis = servis.replace("-"+valor+" ", "");
				servis = servis.replace("-"+valor+"-", "-");
				servis = servis.replace(" "+valor+" ", "");
				
				var addserv = $.trim(servis);	
				$('#set_serv_sel').val(addserv);
				if(addserv == ""){addserv= 0;}

				var reff = "http://www.turnonet.com/e/servs/"+empid+"/"+addserv;
				$("#addeds").load(reff,function(){			
					$('#addeds').fadeIn('slow');
					$('#lodinf').html('<b>Fecha: -</b><br />Seleccion\u00e1 una fecha para ver los horarios de turnos disponibles.');
					$('#set_servicios').html('Servicios: -');
					$('#tu_servs').val('');		
					$('#set_dura').html('Duraci\u00f3n Estimada: -');
					$('#tu_durac').val('');
					$('#tur_fec').val('');
					$('#tur_hora').val('');
				});
			}
		}
		return false;
	};
function showu(usid,usnom,usma){
	document.getElementById('ures').innerHTML = usnom;
	$('#tur_usid').val(usid);
	$('#pres_usid').val(usid);
	$('#pres_usnom').val(usnom);
};
function reatu(usid,usnom,tuid, est){
	var addin = "";
	if(est!='BAJA'){
		addin = " (el turno actual sera cancelado al momento de cargar este turno)";
	}
	document.getElementById('ures').innerHTML = usnom+addin;
	$('#tur_usid').val(usid);
	$('#tur_reag').val(tuid);
};
function loadh(hora, fecham, fecha){
	$('#tur_fec').val(fecham);
	$('#tur_hora').val(hora);
};
function loadh2(hora, fecham, st){
	$('#tur_fec').val(fecham);
	$('#tur_hora').val(hora);
	$('#ptnxtu').slideToggle('slow');
	if(st == 1){
		document.getElementById('tur_st').checked = true;
	}else{
		document.getElementById('tur_st').checked = false;
	}
};
function valtim(gtim){    
	var spt = gtim.split(':');
	var thor = spt[0];
	var tmin = spt[1];
	if(thor == '' || tmin == ''){
		alert("el horario seleccionado no es valido!");
		return false;
	}
	var vhor = IsNum(thor);	
	var vmin = IsNum(tmin);
	if(vhor == false || vmin == false){
		alert("el horario seleccionado no es valido!");
		return false;
	}	
	if(thor>24 || tmin>59 ){
		alert("el horario seleccionado no es valido!");
		return false;
	}
 	if(gtim.length>5){
   		alert("el horario seleccionado no es valido!");
   		return false;
  	}
	return true;
}
function valhor(gthor){    
	var spt = gthor.split('/');
	var tdia = spt[0];
	var tmes = spt[1];
	var tani = spt[2];
	if(tdia == '' || tmes == '' || tani == ''){
		alert("la fecha seleccionada no es valida!");
		return false;
	}
	var vdia = IsNum(tdia);	
	var vmes = IsNum(tmes);
	var vani = IsNum(tani);
	if(vdia == false || vmes == false || vani == false){
		alert("la fecha seleccionada no es valida!");
		return false;
	}	
	if(tdia>31 || tmes>12){
		alert("la fecha seleccionada no es valida!");
		return false;
	}
 	if(gthor.length>10){
   		alert("la fecha seleccionada no es valida!");
   		return false;
  	}
	return true;
}
function IsNum(sText){
	var ValidChars = "0123456789";
	var IsNumber = true;
	var Char;
	for (i=0; i<sText.length && IsNumber==true; i++){ 
		Char = sText.charAt(i); 
		if (ValidChars.indexOf(Char) == -1){
			IsNumber = false;
		}
	}
	return IsNumber;
}