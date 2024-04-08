// Este ejemplo muestra un formulario de dirección, utilizando la función de autocompletar
// de Google places API para ayudar a los usuarios rellenar la información.
var searchInput = 'search_input';

var autocomplete;
iniciaPlaces();


function iniciaPlaces(){
    autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
        types: ['geocode'],componentRestrictions: { country: "ES" }
    });
   
    
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var near_place = autocomplete.getPlace();
        document.getElementById('loc_lat').value = near_place.geometry.location.lat();
        document.getElementById('loc_long').value = near_place.geometry.location.lng();
        
        
        if (near_place['address_components'].length<5){
            return;
        }
        var domicilio=new Array();
        
        
        domicilio['direccion']=near_place['address_components'][1]['long_name']+', '+near_place['address_components'][0]['long_name'];
        
        
        domicilio['cod_postal']=near_place['address_components'][6]['long_name'];
        
        domicilio['poblacion']=near_place['address_components'][2]['long_name'];
        
        domicilio['provincia']=near_place['address_components'][3]['long_name'];
        domicilio['lat']=near_place.geometry.location.lat();
        domicilio['lng']=near_place.geometry.location.lng();
        /*
        console.log(near_place['address_components']);
        console.log(near_place['address_components'][1]['long_name']+', '+near_place['address_components'][0]['long_name']);
        
        console.log(near_place['address_components'][6]['long_name']+' - '+near_place['address_components'][2]['long_name']+'('+near_place['address_components'][3]['long_name']+')');
        */
            
        
        enviardatos(domicilio);
    });

}

function enviardatos(domicilio){
    //console.log(domicilio);
    
    window.parent.datosdegoogle(domicilio);
    
}
