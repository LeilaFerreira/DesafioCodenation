<html>
<head></head>
<title></title>

<?php

   
    $token_id = '7a3969cbcc527c4d442d86d63af3d60352fd6868';
    $url = "https://api.codenation.dev/v1/challenge/dev-ps/generate-data?token=".$token_id;
    
    $client = curl_init($url);
   
    curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
    $response = curl_exec($client);
    $arquivo = fopen("answer.json", "x+" );
    fwrite($arquivo, $response);
    fclose($arquivo);
    $conteudo = file_get_contents("answer.json");
    $json = json_decode($conteudo);
    $texto = strtolower($json->cifrado);
    $array = str_split($texto);
    $decifrado = '';
    foreach ($array as $key => $value) {
      $valor =  ord($value);
      if($valor >= 97 && $valor <= 122){
        $decifrado .= chr($valor - $json->numero_casas);
      }else{
        $decifrado .= chr($valor);
      }
      
    }
   
    echo   $decifrado;
     $json->decifrado = $decifrado;
     $json->resumo_criptografico = sha1($decifrado); 
     $novoArquivo = fopen("answer.json", "r+" );
     fwrite($novoArquivo, json_encode($json));
     fclose($novoArquivo);


     $ch = curl_init('https://api.codenation.dev/v1/challenge/dev-ps/submit-solution?token='.$token_id); 
     $headers = array("Content-Type:multipart/form-data"); 
     curl_setopt($ch, CURLOPT_POST,1);
     curl_setopt_array($ch, 
     [ 
     CURLOPT_RETURNTRANSFER => true, 
     CURLOPT_POST => true, 
     CURLOPT_HTTPHEADER => $headers,
     CURLOPT_POSTFIELDS => [ 'answer' => curl_file_create('answer.json') ] ]); 
    
     $resposta = curl_exec($ch); 
     $err = curl_error($ch);
     echo $err;
     echo $resposta;
     curl_close($ch);
    
?>
</html>
