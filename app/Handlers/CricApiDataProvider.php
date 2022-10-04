<?php

namespace App\Handlers;

use App\Data\FixtureDetailDTO;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CricApiDataProvider
{

    public function __construct()
    {
        $this->api_key = ['api_token' => config('sport_api.cricket_cric_api')];
        $this->client = new Client([
            'base_uri' => 'https://cricket.sportmonks.com/api/v2.0/',
        ]);
    }


    public function fetchUpcomingFixtures()
    {
        $today = Carbon::now();
        $upcomingFixturees = json_decode($this->client->get("fixtures", [
            'query' => $this->api_key + [
                    'include' => 'localteam,visitorteam',
                    'filter[starts_between]' => "{$today->format('Y-m-d')}, {$today->addWeek()->format('Y-m-d')}"
                ]
        ])->getBody()->getContents(), true);
        return FixtureDetailDTO::collection($upcomingFixturees['data']);
    }

//    public function fetchFixtureById($fixture_id) {
//        $fixtureDetails = $this->client->get(`/fixtures/${fixture_id}`);
//        return $fixtureDetails['data'];
//    }

    public function fetchFixtureInfo($fixture_id, $query_params = []): FixtureDetailDTO
    {
        $teamDetails = json_decode($this->client->get("fixtures/{$fixture_id}", [
            'query' => $this->api_key + $query_params,
        ])->getBody()->getContents(), true);
        return FixtureDetailDTO::from($teamDetails['data']);
    }

    public function fetchFixtureScoreboard($fixture_id): FixtureDetailDTO
    {
        $fixtureScoreboards = json_decode($this->client->get("fixtures/${fixture_id}", [
            'query' => $this->api_key + [
                    'include' => 'bowling, batting',
                ]
        ])->getBody()->getContents(), true);

        return FixtureDetailDTO::from($fixtureScoreboards['data']);
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

