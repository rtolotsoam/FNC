
function erreurRecherche(){
   
   //$txtModifDate        = $_REQUEST['txtModifDate'] ;
   
	$("body").append ('<div class="dialog" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessage"></p></p></div>') ;

		$("div.dialog").removeAttr("title") ;
		$("div.dialog").attr({title: "Information"}) ;
		$("p.pMessage").html("Il n'y a aucun enregistrement correspondant à vos critères de recherche dans la base!") ;
		$("div.dialog").dialog({
			modal: true,
			overlay: {
				backgroundColor: '#000000',
				opacity: 0.5
			},
			buttons: {
				Fermer: function() {
					$(this).dialog('close');
				}
			}			
		});			
}

function validerFNC(variable){
	
	$("body").append ('<div class="dialog" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessage"></p></p></div>') ;

	$("div.dialog").removeAttr("title") ;
	$("div.dialog").attr({title: "Ajout d'action"}) ;
	$("p.pMessage").html("Voulez vous vraiment valider cette fiche?") ;
		
	id = $("#ID_"+variable).val() ; 
	ref = $("#REF_"+variable).val() ;
	 
	$("div.dialog").dialog({
		modal: true,
		overlay: {
			backgroundColor: '#000000',
			opacity: 0.5
		},
		buttons: {
			'Oui': function() {
					window.location.href = "../script/FNCValider.php?txtId="+id+"&txtRef="+ref ;
			},
			'Non, ne pas valider': function() {
					$(this).dialog('close') ;
			}
		}
	});
}

function supprimerFNC(variable){
	
	$("body").append ('<div class="dialog" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessage"></p></p></div>') ;

	$("div.dialog").removeAttr("title") ;
	$("div.dialog").attr({title: "Ajout d'action"}) ;
	$("p.pMessage").html("Voulez vous vraiment supprimer cette fiche?") ;
		
	id = $("#ID_"+variable).val() ; 
	ref = $("#REF_"+variable).val() ;
	 
	$("div.dialog").dialog({
		modal: true,
		overlay: {
			backgroundColor: '#000000',
			opacity: 0.5
		},
		buttons: {
			'Oui': function() {
					window.location.href = "../script/FNCSupprimer.php?txtId="+id+"&txtRef="+ref ;
			},
			'Non, ne pas supprimer': function() {
					$(this).dialog('close') ;
			}
		}
	});
}


function valider()
{
	
	$("body").append ('<div class="dialog" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessage"></p></p></div>') ;

	$("div.dialog").removeAttr("title") ;
	$("div.dialog").attr({title: "Ajout d'action"}) ;
	$("p.pMessage").html("Voulez vous vraiment valider cette fiche?") ;
		
	id = $("#ID").val() ; 
	ref = $("#REF").val() ;
	 // alert('id'+id+'ref'+ref);
	$("div.dialog").dialog({
		modal: true,
		overlay: {
			backgroundColor: '#000000',
			opacity: 0.5
		},
		buttons: {
			'Oui': function() {
					window.location.href = "../script/FNCValider.php?txtId="+id+"&txtRef="+ref ;
			},
			'Non, ne pas valider': function() {
					$(this).dialog('close') ;
			}
		}
	});
}

function supprimer(){
	
	$("body").append ('<div class="dialog" style="display: none"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><p class="pMessage"></p></p></div>') ;

	$("div.dialog").removeAttr("title") ;
	$("div.dialog").attr({title: "Ajout d'action"}) ;
	$("p.pMessage").html("Voulez vous vraiment supprimer cette fiche?") ;
		
	id = $("#ID").val() ; 
	ref = $("#REF").val() ;
	 
	$("div.dialog").dialog({
		modal: true,
		overlay: {
			backgroundColor: '#000000',
			opacity: 0.5
		},
		buttons: {
			'Oui': function() {
					window.location.href = "../script/FNCSupprimer.php?txtId="+id+"&txtRef="+ref ;
			},
			'Non, ne pas supprimer': function() {
					$(this).dialog('close') ;
			}
		}
	});
}


