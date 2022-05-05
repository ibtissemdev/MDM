function request(id_statut,id_product) {

var  httpRequest = new XMLHttpRequest();
//    requête en mode GET, construction de l'URL en récupérant l'id_product et l'id_statut directement, rendre la requête asynchrone
httpRequest.open('GET', 'http://localhost/MDM/api/update/'+id_product+'/'+id_statut, true);

httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');//encapsule la requête dans une entête que l'on définit dans une URL
console.log('http://localhost/MDM/api/update/'+id_product+'/'+id_statut);
httpRequest.onreadystatechange = function() {
    console.log('variable à transmettre :'+id_statut+' '+id_product);
    //Si la requête a été reçu (statut 200 : réseau) et 4 : traité
    if (httpRequest.readyState == 4 && httpRequest.status == 200) {
 
       // Response
       var response = httpRequest.responseText; 
 console.log(response);    }
 };
 httpRequest.send();

}