<?php

namespace App\Repositories;

use App\Models\Player;
use App\Models\Playerposition;

class PlayerRepository
{

    public function storePlayer(array $request)
    {
        $playerPosition = Playerposition::findOrFail($request['playerposition_id']);
        $newPLayer = new Player();
        $newPLayer->playerposition_id = $playerPosition->id;
        $newPLayer->api_pid = $request['api_pid'];
        $newPLayer->name = $request['name'];
        if (isset($request['image'])) {
            $newPLayer->image = $request['image'];
        }
        $newPLayer->rating = $request['rating'];
        $newPLayer->code = random_string(10) . time();

        $newPLayer->save();

        return $newPLayer;

    }
}
