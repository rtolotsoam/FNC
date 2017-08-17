function verifVide(){
	var zType = $("#slctTypeFNC").val () ;
	
	if (zType == "client"){
		if (($("#slctTypeFNC").val () == "") || ($("#txtClientName").val() == "") || ($("#txtCode").val() == "") || ($("#txtCP").val () == "") || ($("#txtMotif").val () == ""))
		return 0;
		else return 1;
	}
	if (zType != "client"){
		if (($("#slctTypeFNC").val () == "") || ($("#txtClientName").val() == "") || ($("#txtMotif").val () == ""))
		return 0;
		else return 1;
	}
}

function verifChampAjoutFNC(){
	
	$("body").append ('<div class="dialog" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessage"></p></p></div>') ;
	
	var vide = verifVide();
		
	if(vide == 0)
	{
			
			$("div.dialog").removeAttr("title") ;
			$("div.dialog").attr({title: "Champ vide"}) ;
			$("p.pMessage").html("Les champs suivis d'une étoile en rouge sont obligatoires!") ;
			$("div.dialog").dialog({
				modal: true,
				buttons: {
					Fermer: function() {
						$(this).dialog('close');
					}
				}			
			});
	}
	else{
			$("div.dialog").removeAttr("title") ;
			$("div.dialog").attr({title: "Ajout d'action"}) ;
			$("p.pMessage").html("Voulez vous vraiment ouvrir cette fiche?") ;
			
			$("div.dialog").dialog({
				modal: true,
				overlay: {
					backgroundColor: '#000000',
					opacity: 0.5
				},
				buttons: {
					'Oui, ouvrir': function() {
							$("#frmFNCAjout").removeAttr("action");
							$("#frmFNCAjout").attr({
								action: ""
							});
							$("#txtAjoutAction").val("true")
							document.frmFNCAjout.submit();
					},
					'Non, ne pas ouvrir': function() {
							$(this).dialog('close') ;
					}
				}
			});
	}	
}

function verifChampAjoutAction(){
	
	$("body").append ('<div class="dialogAction" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessageAction"></p></p></div>') ;
	
	if (($("#txtDateDeb").val() == "")||($("#slctTraitStatutAction").val() == "")||($("#slctType").val() == "")){
			
			$("div.dialogAction").removeAttr("title") ;
			$("div.dialogAction").attr({title: "Champ vide"}) ;
			$("p.pMessageAction").html("Les champs suivis d'une étoile en rouge sont obligatoires!") ;
			$("div.dialogAction").dialog({
				modal: true,
				buttons: {
					Fermer: function() {
						$(this).dialog('close');
					}
				}			
			});
	}
	else{
			$("div.dialogAction").removeAttr("title") ;
			$("div.dialogAction").attr({title: "Ajout d'action"}) ;
			$("p.pMessageAction").html("Voulez vous vraiment attribuer cette action à la fiche?") ;
			
			$("div.dialogAction").dialog({
				modal: true,
				overlay: {
					backgroundColor: '#000000',
					opacity: 0.5
				},
				buttons: {
					'Oui, ajouter': function() {
							$("#frmAjoutAutreAction").removeAttr("action");
							$("#frmAjoutAutreAction").attr({
								action: ""
							});
							$("#txtAjoutAutreAction").val("true")
							document.frmAjoutAutreAction.submit();
					},
					'Non, ne pas ajouter': function() {
						$(this).dialog('close');
					}
				}
			});
	}
}

function filtrerTouche(event){
	
	var isNS4 = (navigator.appName=="Netscape")?1:0; 
	var e ;
	
	if(!isNS4) 
		e = event.keyCode ;		
	else 
		e = event.which

	if(((e < 48) && (e != 0) && (e != 8) && (e != 9)) || ((e > 57) && (e != 127))) 
		return false;
}

function verifDate(){
	$("body").append ('<div class="dialogDate" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessageDate"></p></p></div>') ;
	
	var res;
	
	var dateDeb = $("#txtDateDeb").val();
	var dateFin = $("#txtDateFin").val();
	var reg = new RegExp("-");
	
	var tabDateDeb = dateDeb.split(reg);
	var	tabDateFin = dateFin.split(reg);
	
	anDeb = tabDateDeb[0];
	moisDeb = tabDateDeb[1];
	jourDeb = tabDateDeb[2];
	
	anFin = tabDateFin[0];
	moisFin = tabDateFin[1];
	jourFin = tabDateFin[2];
	
	if(anFin < anDeb) res = "true";
	else if(anFin > anDeb) res = "false";
		else{
			if(moisFin < moisDeb) res = "true";
			else if(moisFin > moisDeb) res = "false";
				else{
					if(jourFin > jourDeb) res = "false";
					else if(jourFin < jourDeb) res = "true";
					else res = "false";
				}
		}
	
	if(res == "true"){		
		$("div.dialogDate").removeAttr("title") ;
		$("div.dialogDate").attr({title: "Erreur de date"}) ;
		$("p.pMessageDate").html("La date de début d'une action doit être inférieure à la date de fin de cette action!") ;
		$("div.dialogDate").dialog({
			modal: true,
			buttons: {
				Fermer: function() {
					$(this).dialog('close');
				}
			}			
		});
	}
}

function removeStar(){	
	var zType = $("#slctTypeFNC").val () ;
	
	if (zType != "client"){
		
		$("#starCMD").css({ color: "white" });
		$("#starCP").css({ color: "white" });
		
		return 0;
	}
	else{
		$("#starCMD").css({ color: "red" });
		$("#starCP").css({ color: "red" });	

		return 1;
	}
}

// To fill automatically the CP Mat from commande id inserted
function fillMatCpFromCommande ()
{
	
	$("body").append ('<div class="dialog" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessage"></p></p></div>') ;
	
	var zType = removeStar();
	var zCommandId = $("#txtCode").val ().toUpperCase () ;

	if (zType == 0) {
		$("#txtCode").val (zCommandId) ;
		$.get(
				"../webservices/FNCAjout_FindCpMat.php", 
				{
					zCommandId : zCommandId
				},				
				function (_iData)
				{													
					tzDataTmp = _iData.split ("_") ;
					if (tzDataTmp[0] != 0)
					{						
						$("#txtCP").val (tzDataTmp[0]) ;
						$("#txtCPPrenom").val (tzDataTmp[1]) ;
					}
				}			
			);
		
			return false ;
	}
	else
	{	
		$("#txtCode").val (zCommandId) ;
		
		if (zCommandId == "")
		{
			$("div.dialog").removeAttr("title") ;
			$("div.dialog").attr({title: "Champ vide"}) ;
			$("p.pMessage").html("Veuillez renseigner le code de la commande") ;
			$("div.dialog").dialog({
				modal: true,
				buttons: {
					Fermer: function() {
						$(this).dialog('close');
					}
				}			
			});		
				
		}		
		else //if (zCommandId != "")
		{
			if (zCommandId != 'BPO000') // find the CP
			{
				$.get(
					"../webservices/FNCAjout_FindCpMat.php", 
					{
						zCommandId : zCommandId
					},				
					function (_iData)
					{													
						tzDataTmp = _iData.split ("_") ;

						if (tzDataTmp[0] == 0)
						{
							$("div.dialog").removeAttr("title") ;
							$("div.dialog").attr({title: "Champ vide"}) ;
							$("p.pMessage").html("Veuillez entrer un code de commande existant") ;
							$("div.dialog").dialog({
								modal: true,
								buttons: {
									Fermer: function() {
										$(this).dialog('close');	
										$("#txtCode").focus() ;									
									}
								}			
							});
			
						}
						else //if (tzDataTmp[0] != 0)
						{						
							$("#txtCP").val (tzDataTmp[0]) ;
							$("#txtCPPrenom").val (tzDataTmp[1]) ;	
						}						
					}			
				);
			
				return false ;
			}
		}

	}
}

