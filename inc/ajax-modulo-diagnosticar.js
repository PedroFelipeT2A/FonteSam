// Função para diagnosticar problemas e corrigir
function diagnosticar_problemas( login ) {

  document.getElementById("log-sistema-conteudo").innerHTML = "<img src='/img/ajax-loader.gif' />";
  document.getElementById('log-sistema-fundo').style.display = "block";
  document.getElementById('log-sistema').style.display = "block";
  
  var http = new Ajax();
  http.open("GET", "/modulo-diagnosticar/"+login , true);
  http.onreadystatechange = function() {
  
  if(http.readyState == 4) {
  
  resultado = http.responseText;
  
  document.getElementById("log-sistema-conteudo").innerHTML = resultado;  
  
  }
  
  }
  http.send(null);
  delete http;
}