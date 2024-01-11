<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Customer;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     */
    public function create(array $input): User
    {
//        dd($input);

        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phoneNumber' => ['required', 'string', 'min_digits:10', 'max_digits:11'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules()
        ])->validate();

        $phone = self::validatePhoneNumber($input['phoneNumber']);

        $user = User::create([
            'name' => $input['first_name'] . " " . $input['last_name'],
            'email' => $input['email'],
            'type' => 'prospective',
            'active' => 1,
            'is_admin' => 0,
            'phone_number' => $phone,
            'password' => Hash::make($input['password']),
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'active' => 0,
            'type' => 'Service',
            'plan' => null,
            'plan_duration' => 'Monthly',
            'service_day' => null,
            'plan_price' => null,
            'chemicals_included' => 1,
            'assigned_serviceman' => null,
            'phone_number' => $phone,
            'terms' => 'begin'
        ]);

        Address::create([
            'customer_id' => $customer->id,
            'address_line_1' => $input['addressLine1'],
            'city' => $input['city'],
            'state' => 'AZ',
            'zip' => $input['zip']
        ]);

        return $user;
    }

    private function validatePhoneNumber($phone): string
    {
        if (strlen($phone) === 10) {
            return '1' . $phone;
        } else if (strlen($phone) === 11){
            return $phone;
        }
        return '';
    }
}

