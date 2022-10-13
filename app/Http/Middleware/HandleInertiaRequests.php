<?php

namespace App\Http\Middleware;

use App\Models\Collection;
use Carbon\Carbon;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $unavailDates = [];
        $active = null;
        
        if($request->user()) {

            $collectionDates = Collection::where('user_id', $request->user()->id)->get(['start_date', 'end_date']);
            $active = Collection::active()->where('user_id', $request->user()->id)->get();
            
            
            $interval = new DateInterval('P1D');
            
            foreach($collectionDates as $date) {
                $currentDate = $date->start_date;
                
                while($currentDate <= $date->end_date) {
                    array_push($unavailDates, date($currentDate));
                    $currentDate->add($interval);
                }
            };
        }

        return array_merge(parent::share($request), [

            'auth' => [
                'user' => $request->user(),
            ],
            'unavail_dates' => $unavailDates,
            'global_active' => $active,
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
