<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public static function allCustomers()
    {

        $customers = DB::select('select c.first_name, c.last_name, c.id, c.service_day, c.assigned_serviceman,
       a.community_gate_code, a.address_line_1, a.city, a.zip
from customers c
         join addresses a on c.id = a.customer_id
where c.order is not NULL order By c.order DESC');

        return self::completedCustomers($customers);

    }

    public static function allCustomersTiedToUser()
    {



       $customers = DB::select('select c.first_name,
       c.last_name,
       u.name,
       c.id,
       c.service_day,
       c.assigned_serviceman,
       a.community_gate_code,
       a.address_line_1,
       a.city,
       a.zip
from customers c
         join addresses a on c.id = a.customer_id
         join users u on u.id = c.user_id
where c.order is not NULL AND u.id = ' . Auth::user()->id . '
order By c.order DESC');

       return self::completedCustomers($customers);

    }

    public static function completedCustomers($customers)
    {
        $now = Carbon::now();
        $startOfWeek = $now->startOfWeek(CarbonInterface::MONDAY)->format('Y-m-d H:i');
        $endOfWeek = $now->endOfWeek(CarbonInterface::SUNDAY)->format('Y-m-d H:i');

        $startOfWeek = new Carbon($startOfWeek);
        $endOfWeek = new Carbon($endOfWeek);

        foreach ($customers as $customer) {

            $stops = DB::select('select count(ss.time_in) as stops
from service_stops ss
where ss.customer_id = ' . $customer->id . ' order by ss.time_in DESC Limit 1');

            if ($stops[0]->stops > 0) {

                $lastServiceStop = DB::select('select ss.time_in
from service_stops ss
where ss.customer_id = ' . $customer->id . ' order by ss.time_in DESC Limit 1')[0]->time_in;


                $lastServiceStop = new Carbon($lastServiceStop);

                if ($lastServiceStop > $startOfWeek && $lastServiceStop < $endOfWeek) {
                    $customer->completed = true;
                } else {
                    $customer->completed = false;
                }
            }
        }

        return $customers;

    }

}
