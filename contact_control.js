console.log('Hello!');

function legalBasisChanged(field_id_legal_basis, field_container_id_legal_basis) {
	var e = document.getElementById(field_id_legal_basis);
	var legalBasis = e.options[e.selectedIndex].value;
	var container = document.getElementById(field_container_id_legal_basis);
	if(legalBasis == '1') {
		container.style.display = "block";
	} else {
		container.style.display = "none";
	}
}