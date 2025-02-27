<?php
$pastaOrigem = "D:\Alceu\xml";
$arquivos = glob($pastaOrigem . "/*.xml");

foreach ($arquivos as $arquivo) {

  $arquivoTxt = './convertidas/' . pathinfo($arquivo, PATHINFO_FILENAME) . '.txt';
  file_put_contents($arquivoTxt, '');

  $xml = simplexml_load_file($arquivo);
  $xml->registerXPathNamespace("nfe", "http://www.portalfiscal.inf.br/nfe");


  $cnpjEmitente = $xml->xpath('//nfe:emit/nfe:CNPJ')[0];
  $numNF = $xml->xpath('//nfe:ide/nfe:nNF')[0];
  $serieNF = $xml->xpath('//nfe:ide/nfe:serie')[0];
  $dataEmissao = $xml->xpath('//nfe:ide/nfe:dEmi')[0];
  $dataEmissaoformat = DateTime::createFromFormat('Y-m-d', $dataEmissao)->format('d/m/Y');


  $conteudo = '';
  $conteudo .= "Arquivo: " . basename($arquivo);
  $conteudo .= "\nCNPJ Emitente: $cnpjEmitente\n";
  $conteudo .= "Número NF: $numNF\n";
  $conteudo .= "Série NF: $serieNF\n";
  $conteudo .= "Data de emissão: $dataEmissaoformat\n\n";

  $contador = 1;

  foreach ($xml->xpath('//nfe:det/nfe:prod') as $produto) {
    $codProduto = $produto->cProd;
    $descProduto = $produto->xProd;
    $valorProduto = $produto->vProd;
    $qtdProduto = $produto->qCom;
    $conteudo .= "Produto $contador\n";
    $conteudo .= "Código Produto: $codProduto \n";
    $conteudo .= "Descrição Produto: $descProduto \n";
    $conteudo .= "Quantidade Produto: $qtdProduto \n";
    $conteudo .= "Valor Produto: R$ $valorProduto \n\n";
    $contador++;
  }
  file_put_contents($arquivoTxt, $conteudo, FILE_APPEND);
}
