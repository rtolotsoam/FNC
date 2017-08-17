
function charger(){
	
	$.get ("../webservices/FNCModif_FindTypologie.php",
	{
		valeur : $("#txtAreaImputation").val()
	},
	function (_result)
	{
		$("#slctTypologie").html (_result) ;
	}) ;
}

function remplirPrenom(){
	
	$.get ("../webservices/FNCModif_FillPrenom.php",
	{
		matrCP : $("#txtCP").val()
	},
	function (_resultat)
	{
		$("#txtPrenom").val (_resultat) ;
	}) ;
}