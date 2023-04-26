//Desplegamos y plegamos el menú de comentarios cambiando width
function openSidebar() {
    document.getElementById("sidebar1").style.width = "40%";
  }

function closeSidebar() {
    document.getElementById("sidebar1").style.width = "0";
  } 


//Función auxiliar que comprueba si un campo está vacío
function checkEmpty(form) {
    var empty = false;
    var cleared = form.trim();
    if(cleared == "" || cleared === null || cleared === undefined) {
      empty = true;
    }
    return empty;
}
  
//Función auxiliar para validar mediante una expresión regular que el correo es válido
  function validateMail(mail) {
    var regexpression = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regexpression.test(String(mail.value).toLowerCase());
  }
  
//Variables auxiliares y lista de palabras censuradas
  palabraAux = "";
  index = 0;
  var blackList = [];

//Función principal a la que llama el HTML "onKeyUp" para chequear censura en tiempo de escritura
  function censor(event) {

    var word = /[a-zA-Z]/;
    var mensaje = document.getElementById("msg");
    var tecla  = String.fromCharCode(event.keyCode).toLowerCase();
  
    if(word.test(tecla)){
      palabraAux += tecla;
    }
    else{
      if(tecla === " " || tecla === "."){
        compruebaCensura(palabraAux); //Llama a la comprobación de blackList
        palabraAux = "";
      }
      if(event.keyCode === 8){ 
        palabraAux = palabraAux.substring(0, palabraAux.length - 1);
      }
    }
  
    index = mensaje.value.length;
  }
  
//Función que comprueba si cada palabra introducida debe o no censurarse, en cuyo caso llama a una función que lo haga. En la P3 consulta las palabras a la BBDD mediante una petición.
  function compruebaCensura(palabra) {
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        blackList = JSON.parse(this.responseText);
      }

      var i;
      for(i = 0; i< blackList.length; i++){
        if(palabra === blackList[i]){
          censorship(palabra);
        }
      }
    };
    
    xmlhttp.open("GET", "getCensored.php", true);
    xmlhttp.send();

  }
  
//Finalmente, función que aplica la censura si es necesario
  function censorship(palabra){
    var mensaje = document.getElementById("msg");
    var aux = "";
  
    for(var i = 0; i < palabra.length; i++){
      aux += "*";
    }
    var nuevomsj = mensaje.value.substring(0, index - palabra.length) + aux + " ";
  
    mensaje.value = nuevomsj;
  }

//Función que agrega el comentario despues de hacer todas las validaciones
  function addComment(event){
    event.preventDefault();
    var comment = document.getElementById('msg');
  
    if (checkEmpty(comment.value)){
      alert("Hay algun campo obligatorio vacio");
      return false
    }
    
   document.getElementById("fc1").submit();
    return true;
  }

  function confirmarDelete(){
    return confirm("¿Desea eliminar este científico de la web?");
  }

  function openEditar(id){
    var panel = document.getElementById("editar_comentario"+id).style.display = "inline";

  }