<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Address;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProspectiveController extends Controller
{
    //
    public function index()
    {
//        dd(Auth::user());
        if (Auth::user()->type === 'prospective') {
            $appointments = Appointment::where('user_id', Auth::user()->id)->get();
            if ($appointments->isNotEmpty()) {
                $appointmentDate = $appointments[0]->appointment_date;
                $appts = $appointments[0]->appointment_date . " " . $appointments[0]->appointment_time;
                $available = self::getAvailableTimes($appointments[0]->appointment_date);
            } else {
                $appts = null;
                $appointmentDate = null;
                $available = null;
            }
            return Inertia::render('Prospective/Landing', [
                'currentAppointmentDate' => $appointmentDate,
                'appointments' => $appts,
                'jsonData' => $available
            ]);
        } else if (Auth::user()->type === 'serviceman') {
            return redirect('dashboard');
        }
    }

    public function times(Request $request)
    {
//        dd($request);

        $available = self::getAvailableTimes($request->date);
        return Inertia::render('Prospective/Landing', [
            'jsonData' => $available,
        ]);

        // get all times associated to this date
        // if the date does not exist then all times are available
        // if date does exist then get all times that are not there
        // return the available times
    }

    private function getAvailableTimes($date)
    {
        $times = Appointment::where('appointment_date', $date)->get();

//        dd($times);

        if ($times->isEmpty()) {
            return  [
                '04' => true,
                '05' => true,
                '06' => true,
                '07' => true,
            ];
        } else {
            $available = [
                '04' => true,
                '05' => true,
                '06' => true,
                '07' => true,
            ];
            $avTimes = [];
            foreach ($times as $time) {
                $t = explode(':', $time->appointment_time);
//                dd($t[0]);
                foreach ($available as $key => $avail) {
                    if ($key === $t[0]) {
//                        dd($t[0]);
                        $available[$key] = false;
                    }
                }
            }
        }
        return $available;
    }

    public function requested(Request $request)
    {
        $appt = Appointment::where('user_id', Auth::user()->id)->get();

        if ($appt->isNotEmpty()) {
            $appt[0]->user_id = Auth::user()->id;
            $appt[0]->appointment_date = $request->date;
            $appt[0]->appointment_time = $request->time;
            $appt[0]->save();
        } else {
            Appointment::create([
                'user_id' =>  Auth::user()->id,
                'appointment_date' => $request->date,
                'appointment_time' => $request->time
            ]);
        }
    }

    public function customers()
    {
        $prospective = User::where('type', 'prospective')->get();

        $prospectiveUsers = [];
        foreach ($prospective as $prosp){
            $appointment = Appointment::where('user_id', $prosp->id)->get();
            $customer = Customer::where('user_id', $prosp->id)->get();
            $address = Address::where('customer_id', $customer[0]->id)->get();
            $prospUsers['address'] = $address[0]->address_line_1 . "," . $address[0]->city . " AZ " . $address[0]->zip;
            $prospUsers['id'] = $prosp->id;
            $prospUsers['name'] = $prosp->name;

            if ($appointment->isNotEmpty()){
                $prospUsers['appointment'] = $appointment[0]->appointment_date . " " . $appointment[0]->appointment_time;
            } else {
                $prospUsers['appointment'] = null;
            }

            array_push($prospectiveUsers, $prospUsers);
        }

        return Inertia::render('Prospective/Index', [
            'prospectiveUsers' => $prospectiveUsers
        ]);
    }
}
