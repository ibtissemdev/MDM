function request(id_statut,id_product) {

const  httpRequest = new XMLHttpRequest();
//    requête en mode GET, construction de l'URL en récupérant l'id_product et l'id_statut directement, rendre la requête asynchrone
httpRequest.open('GET', 'http://localhost/MDM/api/update/'+id_product+'/'+id_statut, true);
httpRequest.send();
}