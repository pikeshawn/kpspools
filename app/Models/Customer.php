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
use Stripe\Collection;
use function Sodium\add;
use Illuminate\Support\Facades\Log;

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

//        dd('allCustomers');

        $customers = Customer::select(
            'customers.first_name',
            'customers.last_name',
            'addresses.order',
            'customers.id',
            'addresses.service_day',
            'customers.assigned_serviceman',
            'customers.phone_number',
            'addresses.community_gate_code',
            'addresses.address_line_1',
            'addresses.city',
            'addresses.zip',
            'addresses.id as addressId'
        )
            ->join('addresses', 'customers.id', '=', 'addresses.customer_id')
            ->where('active', 1)
            ->whereNotNull('customers.service_day')
            ->orderByDesc('customers.order')
            ->get();

//        dd($customers);

//        dd(Auth::user()->getAuthIdentifier());
//        dd($customers);

        return self::completedCustomers($customers);
    }

    public static function allCustomersTiedToUser()
    {

//        dd('allCustomersTiedToUser');


        $servicemanId = Auth::user()->id;

//        dd($servicemanId);


//        $customers = Customer::select(['assigned_serviceman', 'phone_number', 'id', 'first_name', 'last_name', 'order', 'service_day'])
//            ->where('serviceman_id', Auth::user()
//                ->getAuthIdentifier())->orderBy('service_day')->orderBy('order')->get();

        $addresses = Address::where('serviceman_id', Auth::user()->id)->get();

//                    dd($addresses);

        $customerCollection = [];

        foreach ($addresses as $address) {

            $customerArray = [];

            $customer = Customer::where('id', $address->customer_id)->first();
            $user = User::find($address->serviceman_id);

            $customerArray['first_name'] = $customer->first_name;
            $customerArray['last_name'] = $customer->last_name;
            $customerArray['order'] = $address->order;
            $customerArray['id'] = $customer->id;
            $customerArray['service_day'] = $address->service_day;
            $customerArray['assigned_serviceman'] = $user->name;
            $customerArray['phone_number'] = $customer->phone_number;
            $customerArray['community_gate_code'] = $address->community_gate_code;
            $customerArray['address_line_1'] = $address->address_line_1;
            $customerArray['city'] = $address->city;
            $customerArray['zip'] = $address->zip;
            $customerArray['addressId'] = $address->id;
            $customerArray['completed'] = self::completedCustomer($address->id);

            $customerCollection[] = $customerArray;


        }
        return $customerCollection;
    }

    public static function completedCustomers($customers)
    {

        $now = Carbon::now();
        $startOfWeek = $now->startOfWeek(CarbonInterface::MONDAY)->format('Y-m-d H:i');

        $startOfWeek = new Carbon($startOfWeek);

        $dayBeforeStartOfWeek = $startOfWeek->subDays(1)->toDate()->format('Y-m-d H:i');

        foreach ($customers as $customer) {

            $addresses = Address::where('customer_id', $customer->id)->get();

            foreach ($addresses as $address) {

//                dd($address);

                $stops = ServiceStop::where('address_id', $address->id)
                    ->where('time_in', '>', $dayBeforeStartOfWeek)
                    ->where('service_type', 'Service Stop')
                    ->orderBy('time_in')
                    ->get();

//                dd();

                if($address->id === 63) {
//                    dd($stops);
                }


                if ($stops->isEmpty()) {
                    $customer->completed = false;
                } else {
                    $customer->completed = true;
                }
            }



        }

        return $customers;
    }

    public static function completedCustomer($id)
    {
        $now = Carbon::now();
        $startOfWeek = $now->startOfWeek(CarbonInterface::MONDAY)->format('Y-m-d H:i');

        $startOfWeek = new Carbon($startOfWeek);

        $dayBeforeStartOfWeek = $startOfWeek->subDays(1)->toDate()->format('Y-m-d H:i');

        $query = 'select count(ss.time_in) as stops
from service_stops ss
where ss.address_id = ' . $id . ' and ss.time_in > "' .
            $dayBeforeStartOfWeek . '" and ss.service_type = "Service Stop" order by ss.time_in DESC Limit 1';

        $stops = DB::select($query);

        if ($stops[0]->stops > 0) {
            return true;
        } else {
            return false;
        }

    }
}
