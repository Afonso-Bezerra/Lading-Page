<?php
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$anapro = $_POST['anapro'];
$dia = date('d');
$mes = date('m');
$ano = date('Y');
$dataHora = date('Y-m-d h:i:s');
$produto = $_POST['produto'];
$pagina = $_POST['pagina'];
$origem = $_POST['origem'];
$utm_source = 'utm_source';
$utm_medium = 'utm_medium';
$utm_name = 'utm_name';
$data = date("y-m-d");
$pontos = array("-", "(", ")", " ");
$whatsapp = str_replace($pontos, "", $telefone);

// INSERT DATA
include "config.php";

$sql = "INSERT into leads(nome, email, telefone, produto, pagina, origem, utm_source, utm_medium, utm_name, data)
VALUES ('$nome', '$email', '$telefone', '$produto', '$pagina', '$origem', '$utm_source', '$utm_medium', '$utm_name', '$dataHora')";

if ($conn->query($sql) === TRUE) {
    
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// SEND EMAIL

$emailbody = "
<table style='border-collapse:collapse;border:1px solid #E6E6E6;background-color:#FFF;width:500px;' align='center'><tbody>
<tr><td style='padding:20px;'>
<table width='100%' style='font:12px Arial, Helvetica, sans-serif;border-collapse:collapse;'>
<tbody>

<tr>
	<td style='padding:3px;border-bottom:1px solid #E6E6E6;color:#2D2D2D;'>
		<strong>Nome:</strong>
	</td>
	<td style='padding:3px;border-bottom:1px solid #E6E6E6;color:#666666;'>$nome</td>
</tr>
<tr>
	<td style='padding:3px;border-bottom:1px solid #E6E6E6;color:#2D2D2D;'>
		<strong>E-mail:</strong>
	</td>
	<td style='padding:3px;border-bottom:1px solid #E6E6E6;color:#666666;'> $email</td>
</tr>
<tr>
	<td style='padding:3px;border-bottom:1px solid #E6E6E6;color:#2D2D2D;'>
		<strong>Telefone:</strong>
	</td>
	<td style='padding:3px;border-bottom:1px solid #E6E6E6;color:#666666;'><a href='https://api.whatsapp.com/send?phone=55$whatsapp'>$telefone</a></td>
</tr>
<tr>
	<td style='padding:3px;border-bottom:1px solid #E6E6E6;color:#2D2D2D;'>
		<strong>Mensagem:</strong>
	</td>
	<td style='padding:3px;border-bottom:1px solid #E6E6E6;color:#666666;'> Gostaria de saber mais sobre o DOM</td>
</tr>
<tr>
	<td style='padding:3px;border-bottom:1px solid #E6E6E6;color:#2D2D2D;'>
		<strong>Data e Hora de envio:</strong>
	</td>
	<td style='padding:3px;border-bottom:1px solid #E6E6E6;color:#666666;'>	$dataHora</td>
</tr>

</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</blockquote>
</body>
</html>
";

//API Url
$url = 'http://crm.anapro.com.br/webcrm/webapi/integracao/v2/CadastrarProspect';
 
//Initiate cURL.
$ch = curl_init($url);
 
//The JSON data.
$jsonData = array(
	'Key' => 'm6GC1TBo8tc1',
	'KeyIntegradora' => 'EF209911-F929-4AD9-BFDE-0BB7AED52536',
	'KeyAgencia' => 'EF209911-F929-4AD9-BFDE-0BB7AED52536',
	'CampanhaKey' => 'hjGLVZHCOPQ1',
	'ProdutoKey' => 'dZ6zYv_HfaE1',
	'UsuarioEmail' => 'rafael.latance@crbconstrutora.com.br',
	'CanalKey' => '8mkcHBYaqJc1',
	'Midia' => 'Landingpage',
	'PessoaNome' => $nome,
	'PessoaEmail' => $email,
'ValidarTelefone' => 'false',
'PessoaTelefones[0].DDD'=> '00',
'PessoaTelefones[0].Numero'=> $telefone
);

//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);
 
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
 
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
 
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
 
//Execute the request
$result = curl_exec($ch);

//error_reporting(E_ALL);
error_reporting(E_STRICT);

date_default_timezone_set('America/Toronto');

require_once('phpmailer/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$body             = $emailbody;

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "smtp.office365.com"; // SMTP server
$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing) 1 = errors and messages
$mail->CharSet = 'UTF-8';                   // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "smtp.office365.com"; // sets the SMTP server
$mail->Port       = 587; 
$mail->SMTPSecure = 'tls';                  // set the SMTP port for the GMAIL server
$mail->Username   = "mailer@crbconstrutora.com.br"; // SMTP account username
$mail->Password   = "Site@crb10";        // SMTP account password

$mail->SetFrom('mailer@crbconstrutora.com.br', 'CRB Construtora');

$mail->AddReplyTo("$email","$nome");

$mail->Subject    = "LP DOM";

$mail->MsgHTML($body);

$mail->addAddress('leads@crbconstrutora.com.br');
$mail->addAddress('afonso.bezerra@crbconstrutora.com.br');



if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "<script>location.href='http://dom-campinas.crbconstrutora.com.br/obrigado.html'</script>";
}

?>