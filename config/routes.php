<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response): Response {
    return $this->get('view')->render($response, 'index.html');
});

$app->post('/consultar-cep', function (Request $request, Response $response): Response {
    $cep = $request->getParsedBodyParam('cep');
    
    try {
	    $soapClient = new \SoapClient($this->get('settings')['wsdl'], ['exceptions' => true]);
	    $endereco = $soapClient->consultaCEP(['cep' => $cep]);
	    
	    return $response->withJson($endereco->return, 200);
    } catch (\SoapFault $exception) {
	    return $response->withJson(['error' => true, 'descrption' => $exception->getMessage()], 500);
    }
});