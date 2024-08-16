<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DateTime;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Http\Request;

class QRController extends Controller
{
    protected $username = '26551010';
    protected $pass = '1234';
    protected $aesKey = '40A318B299F245C2B697176723088629';
    protected $account = '1061602532';
    protected $url = 'https://apimktdesa.bancavive.com.bo/ApiGateway/api';

    public function index(Request $request)
    {
        $client = new Guzzle();

        $token = $this->token();
        $accountCredit = $this->accountCredit();

        $headers = [
            'Authorization' => "Bearer $token"
        ];
        $body = [
            'transactionId' => time(),
            'accountCredit' => $accountCredit,
            'currency' => 'BOB' ,
            'amount' => $request->amount,
            'description' => 'Description de prueba',
            'dueDate' => (new DateTime())->modify('+7day')->format('Y-m-d'),
            'singleUse' => true,
            'modifyAmount' => false
        ];
        $response = $client->request('POST', "$this->url/qrsimple/generateQR", ['headers' => $headers, 'json' => $body]);
        $json = json_decode($response->getBody());
        return [
            'id' => '<input type="hidden" name="qrId" value="' . $json->qrId . '">',
            'img' => '<img width="300px" src="data:image/png;base64, ' . $json->qrImage . '">',
            'button' => '<form method="POST" action="/qr/download"><input type="hidden" name="_token" value="' . csrf_token() . '"><input type="hidden" name="qr" value="' . $json->qrImage . '"><input type="submit" class="btn btn-secondary" value="Descargar QR"></form>'
        ];
    }

    public function accountCredit()
    {
        // Get encrypt account
        $client = new Guzzle();
        $response = $client->request('GET', "$this->url/authentication/encrypt?text=$this->account&aesKey=$this->aesKey");
        $accountCredit = json_decode($response->getBody());
        return $accountCredit;
    }

    public function download(Request $request)
    {
        $resultimg = str_replace("data:image/png;base64,","", $request->qr);
        header('Content-Disposition: attachment;filename="qr.png"');
        header('Content-Type: image/png');
        echo base64_decode($resultimg);
    }

    public function token()
    {
        // Get encrypted password
        $client = new Guzzle();
        $response = $client->request('GET', "$this->url/authentication/encrypt?text=$this->pass&aesKey=$this->aesKey");
        $pass = json_decode($response->getBody());

        // Get token
        $body = [
            'userName' => $this->username,
            'password' => $pass
        ];
        $headers = 
        $response = $client->request('POST', "$this->url/authentication/authenticate", ['json' => $body]);
        $response = json_decode($response->getBody());
        $token = $response->token;
        return $token;
    }

    public function status($qrId)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token()
        ];

        $body = [
            'qrId' => $qrId
        ];

        $client = new Guzzle();
        $response = $client->request('GET', "$this->url/qrsimple/statusQR", ['headers' => $headers, 'json' => $body]);
        $json = json_decode($response->getBody());

        return $json;
    }
}
