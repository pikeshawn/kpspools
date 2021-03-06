<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory;
    use Notifiable;

    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    public function routeNotificationForNexmo($notification)
    {

//        dd($this->phone_number);

        return $this->phone_number;
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

        $dayBeforeStartOfWeek = $startOfWeek->subDays(1)->toDate()->format('Y-m-d H:i');

        foreach ($customers as $customer) {

            $query = 'select count(ss.time_in) as stops
from service_stops ss
where ss.customer_id = ' . $customer->id . ' and ss.created_at > "' .
                $dayBeforeStartOfWeek . '" and ss.service_type = "Service Stop" order by ss.time_in DESC Limit 1';

            $stops = DB::select($query);

            if ($stops[0]->stops > 0) {
                $customer->completed = true;
            } else {
                $customer->completed = false;
            }


        }

        return $customers;

    }

}
