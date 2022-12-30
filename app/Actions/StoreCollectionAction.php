<?php

namespace App\Actions;

use App\Models\Collection;
use Illuminate\Http\Request;

class StoreCollectionAction {

    public function handle($data) {

        Collection::create([
            'user_id' => $data['user']->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);

    }
}