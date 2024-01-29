<!DOCTYPE html>
<html>
<head>
  <title>VISOR ZONA</title>
  <meta name="viewport" content="initial-scale=1.0">
  <meta charset="utf-8">
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0
    }
    #mapa {
      height: 100%
    }
  </style>
</head>
<body>
  <div id="mapa"></div>
  <script async src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsv3MEv58JZ42mQIqNyE_Zwpyo361dzhs&callback=inicio"></script>
  <script>
    function inicio() {

        var verticesPoligono = [
                { lat: 36.5995003, lng: -6.2450386 },
                { lat: 36.594022, lng: -6.2474847 },
                { lat: 36.5881643, lng: -6.2325073 },
                { lat: 36.592437, lng: -6.2280012 },
                { lat: 36.598949, lng: -6.2376142 },
                { lat: 36.5990524, lng: -6.242807 },
                { lat: 36.5995003, lng: -6.2450386, }
        ];
        var miMapa = new google.maps.Map(document.getElementById('mapa'), {
            center: { lat: 40, lng: -3.5 },
            zoom: 14
        });
        
        var mipol=JSON.parse(getParameterByName('zona'));
        var mia=new Array(mipol.length);
        for (j=0;j<mipol.length;j++){
            mia[j]={lat: parseFloat(mipol[j]['lat']), lng: parseFloat(mipol[j]['lng'])};
            
        }
       //console.log(mia);
         var poligono = new google.maps.Polygon({
            path: mia,
            map: miMapa,
            strokeColor: 'rgb(255, 0, 0)',
            fillColor: 'rgb(255, 255, 0)',
            strokeWeight: 4,
          });
        //console.log()
        miMapa.setCenter(mia[0]);

    }
      
     function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
} 
  
      
      
  </script>
</body>

</html>
