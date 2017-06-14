<?php

namespace App\Http\Controllers\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Message\Request as GuzzleRequest;
use GuzzleHttp\Client;
use Mockery\Exception;

class VerifySummonerName extends Controller
{
    protected $client;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function VerifySummonerName($summonerName)
    {
        $counter = 1;
        if( !Cache::has("counterBackendLolKey") or Cache::get("counterBackendLolKey") > 10){
            Cache::put("counterBackendLolKey", 1);
        }else{
            $counter = Cache::get("counterBackendLolKey") + 1 ;
            Cache::put("counterBackendLolKey", $counter);
        }
        $this->client = new Client();
        $apiKey = $_ENV['RIOT_API_KEY'.$counter];

        $url = 'https://na1.api.riotgames.com/lol/summoner/v3/summoners/by-name/' . $summonerName . '?api_key='. $apiKey;
        $info = $this->ApiRequest($url);

        return $info;
    }
    public function ApiRequest($url){
        $Info = false;

        #Request Info From Api
        $request = new GuzzleRequest('Get', $url);
        $response = $this->client->send($request);

        #If Request Goes Through Return Json Response, Else Try 10 Times then through exception.
        switch ($response->getStatusCode()){
            case '200':
                return true;
//                $Info = json_decode($response->getBody());
//                break;
            default:
                return false;
//                throw new Exception("Unknown Riot Api Error code:" . $response->getStatusCode() . " ApiKey: " . $this->apiKey);
        }

//        return $Info;
    }
}
