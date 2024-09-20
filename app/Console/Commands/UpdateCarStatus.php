<?php

namespace App\Console\Commands;

use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateCarStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-car-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now('+2');
        $cars = Car::where('status', '!=', Car::UNUSABLE)->get();

        foreach ($cars as $car){
            $notAvailable = $car->reservations()
                ->where('start_date','<=', $now)
                ->where('end_date', '>=', $now)
                ->exists();
            $car->status = $notAvailable ? Car::RESERVED : Car::AVAILABLE;

            $car->save();
        }
    }
}
