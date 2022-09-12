<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class CricApiDataProvider {
//    private $api_key;
//    private $api_baseUrl = 'https://cricket.sportmonks.com/api/v2.0';
    public function __construct() {
//        $this->api_key = config('sport_apikey.cricket_cric_api');
        $this->client = new Client([
            'base_url' => ['https://cricket.sportmonks.com/api/{version}/', ['version' => 'v2.0']],
            'defaults' => [
//                'headers' => ['Foo' => 'Bar'],
                'query' => ['api_token' => config('sport_apikey.cricket_cric_api')],
//                'auth'    => ['username', 'password'],
//                'proxy'   => 'tcp://localhost:80'
            ]
        ]);
    }


    public function fetchUpcomingFixtures() {
        $upcomingFixturees = $this->client->get("/fixtures");
        return $upcomingFixturees['data'];
    }

//    public function fetchFixtureById($fixture_id) {
//        $fixtureDetails = $this->client->get(`/fixtures/${fixture_id}`);
//        return $fixtureDetails['data'];
//    }

    public function fetchFixtureTeamDetail($fixture_id) {
        $teamDetails = $this->client->get(`/fixtures/${fixture_id}`, [
            'query' => [
                'include' => 'localteam,visitorteam,lineup',
            ]
        ]);
        return $teamDetails['data'];
    }

    public function fetchFixtureScoreboard($fixture_id) {
        $fixtureScoreboards = $this->client->get(`/fixtures/${fixture_id}`, [
            'query' => [
                'include' => 'bowling, batting',
            ]
        ]);
        return $fixtureDetails['data'];
    }
//    public function fetchPlayerFromApiPid($pid){
//        $playerStatistics = Http::get("{$this->api_baseUrl}/playerStats?apikey={$this->api_key}&pid={$pid}");
//        return $playerStatistics;
//    }


//    public function fetchSquadByFixtureFromApiFixtureId($mid, $gameapi){
//        $fixtureSquads = Http::get("{$this->api_baseUrl}/fantasySquad?apikey={$this->api_key}&unique_id={$mid}");
//        $fixtureSquads = $fixtureSquads['squad'];
//        for($i=0; $i < count($fixtureSquads); $i++){
//            for($j=0; $j < count($fixtureSquads[$i]['players']); $j++){
//                $dbPlayer = Player::where('gameapi_id', $gameapi->id)
//                    ->where('api_pid', $fixtureSquads[$i]['players'][$j]['pid'])->first();
//
//                if($dbPlayer){
//                    $fixtureSquads[$i]['players'][$j]['id'] = $dbPlayer->id;
//                    $fixtureSquads[$i]['players'][$j]['rating'] = $dbPlayer->rating;
//                    $fixtureSquads[$i]['players'][$j]['playerposition_id'] = $dbPlayer->playerposition_id;
//                } else{
//                    $fixtureSquads[$i]['players'][$j]['id'] = null;
//                    $fixtureSquads[$i]['players'][$j]['rating'] = null;
//                    $fixtureSquads[$i]['players'][$j]['playerposition_id'] = null;
//                }
//            }
//        }
//        return $fixtureSquads;
//    }


}

