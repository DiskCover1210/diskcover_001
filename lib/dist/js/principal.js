//función para llenar un select
function llenarComboList(datos,nombre){
    var nombreCombo = $("#"+nombre);
    nombreCombo.find('option').remove();
    for (var indice in datos) {
      nombreCombo.append('<option value="' + datos[indice].codigo + '">' + datos[indice].nombre + '</option>');
    }
}