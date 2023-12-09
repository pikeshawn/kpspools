<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Customer extends Model
{
    use HasFactory;
    use Notifiable;

    protected $guarded = [];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function taskStatuses(): HasManyThrough
    {
        return $this->hasManyThrough(TaskStatus::class, Task::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceStops()
    {
        return $this->hasMany(ServiceStop::class);
    }

    public function routeNotificationForNexmo($notification)
    {
        return $this->phone_number;
    }

    public function getTabs()
    {
        return DB::select('select sum(tabs_whole_mine) as tabs from service_stops where customer_id = ' . $this->id);
    }

    public static function allCustomers()
    {
//        $customers = DB::select('select c.first_name, c.last_name, c.id, c.service_day, c.assigned_serviceman,
//       c.phone_number,
//       a.community_gate_code, a.address_line_1, a.city, a.zip
//from customers c
//         join addresses a on c.id = a.customer_id
//where c.order is not NULL order By c.order DESC');

        $customers = Customer::select(
            'customers.first_name',
            'customers.last_name',
            'customers.id',
            'customers.service_day',
            'customers.assigned_serviceman',
            'customers.phone_number',
            'addresses.community_gate_code',
            'addresses.address_line_1',
            'addresses.city',
            'addresses.zip')
            ->join('addresses', 'customers.id', '=', 'addresses.customer_id')
            ->whereNotNull('customers.service_day')
            ->orderByDesc('customers.order')
            ->get();


        return self::completedCustomers($customers);
    }

    public static function allCustomersTiedToUser()
    {
//        $customers = DB::select('select c.first_name,
//       c.last_name,
//       u.name,
//       c.id,
//       c.service_day,
//       c.assigned_serviceman,
//       a.community_gate_code,
//       a.address_line_1,
//       a.city,
//       a.zip
//from customers c
//         join addresses a on c.id = a.customer_id
//         join users u on u.id = c.user_id
//where c.order is not NULL AND u.id = ' . Auth::user()->id . '
//order By c.order DESC');


        $userId = Auth::user()->id;

        $customers = Customer::select(
            'customers.first_name',
            'customers.last_name',
            'users.name',
            'customers.id',
            'customers.service_day',
            'customers.assigned_serviceman',
            'addresses.community_gate_code',
            'addresses.address_line_1',
            'addresses.city',
            'addresses.zip')
            ->join('addresses', 'customers.id', '=', 'addresses.customer_id')
            ->join('users', 'users.id', '=', 'customers.user_id')
            ->whereNotNull('customers.service_day')
            ->where('users.id', $userId)
            ->orderByDesc('customers.order')
            ->get();


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
where ss.customer_id = ' . $customer->id . ' and ss.time_in > "' .
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
