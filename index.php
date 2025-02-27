<?php
$pastaOrigem = "DiretorioDeOrigem";
$arquivos = glob($pastaOrigem . "/*.xml");

$arquivoTxt = './convertidas/XMLs.txt';
file_put_contents($arquivoTxt, '');

$conteudo = '';

foreach ($arquivos as $arquivo) {

  $xml = simplexml_load_file($arquivo);
  $xml->registerXPathNamespace("nfe", "http://www.portalfiscal.inf.br/nfe");

  foreach ($xml->xpath('//nfe:det/nfe:prod') as $produto) {

    $cnpjEmitente = $xml->xpath('//nfe:emit/nfe:CNPJ')[0];
    $numNF = str_pad($xml->xpath('//nfe:ide/nfe:nNF')[0], 10, '0', STR_PAD_LEFT);
    $serieNF = str_pad($xml->xpath('//nfe:ide/nfe:serie')[0], 3, '0', STR_PAD_LEFT);
    $dataEmissao = $xml->xpath('//nfe:ide/nfe:dEmi')[0];
    $dataEmissaoformat = DateTime::createFromFormat('Y-m-d', $dataEmissao)->format('Ymd');
    $codProduto = str_pad($produto->cProd, 10, '0', STR_PAD_LEFT);
    $descProduto = str_pad($produto->xProd, 200, ' ', STR_PAD_RIGHT);
    $valorProduto = (int) $produto->vProd;
    $partes = explode('.', number_format($valorProduto, 4, '.', ''));
    $parteInt = str_pad($partes[0], 12, '0', STR_PAD_LEFT);
    $parteDec = $partes[1];

    $conteudo .= "$cnpjEmitente$numNF$serieNF$codProduto$descProduto$parteInt,$parteDec$dataEmissaoformat\n";
  }
}
file_put_contents($arquivoTxt, $conteudo, FILE_APPEND);
