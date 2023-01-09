<?php

namespace App\Repositories;

use App\Models\Player;
use App\Models\Playerposition;

class PlayerRepository
{
    public function getUnrated()
    {
        return Player::where('rating', 0)->get();
    }

    public function storePlayer(array $request)
    {
        $playerPosition = Playerposition::findOrFail($request['playerposition_id']);
        $newPLayer = new Player();
        $newPLayer->playerposition_id = $playerPosition->id;
        $newPLayer->api_pid = $request['api_pid'];
        $newPLayer->name = $request['name'];
        $newPLayer->battingstyle = $request['battingstyle'];
        $newPLayer->bowlingstyle = $request['bowlingstyle'];
        $newPLayer->image = $request['image'] ?? null;
        $newPLayer->rating = $request['rating'] ?? null;
        $newPLayer->code = random_string(10) . time();

        $newPLayer->save();

        return $newPLayer;

    }

    public function updatePlayerRatings($player_ratings){
        foreach ($player_ratings as $player_rating){
            $player = Player::findOrFail($player_rating['id']);
            $player->rating = $player_rating['rating'];
            $player->save();
        }
    }
}
