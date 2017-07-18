<?php

namespace App\Http\Middleware;

use Doctrine\Common\Cache\Cache;
use Illuminate\Http\Request;
use GuzzleHttp\Message\Request as GuzzleRequest;
use GuzzleHttp\Client;
use Mockery\Exception;
use Closure;

class CheckSummonerName
{

    protected $client;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $name = $request['SummonerName'];
        $counter = 1;
        if( !Cache::has("counterBackendLolKey") or Cache::get("counterBackendLolKey") > 10){
            Cache::put("counterBackendLolKey", 1);
        }else{
            $counter = Cache::get("counterBackendLolKey") + 1 ;
            Cache::put("counterBackendLolKey", $counter);
        }
        $this->client = new Client();
        $apiKey = $_ENV['RIOT_API_KEY'.$counter];

        $Url = 'https://na1.api.riotgames.com/lol/summoner/v3/summoners/by-name/' . $name . '?api_key='. $apiKey;
        $Info = $this->ApiRequest($Url);

        if(!$Info){
          return false;
        }
        return $next($request);
    }
    public function ApiRequest($Url){

        #Request Info From Api
        $request = new GuzzleRequest('Get', $Url);
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
