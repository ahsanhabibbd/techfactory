<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Package;
use App\Models\Concern;
use DB;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $request)
    {

        DB::beginTransaction();
        try {

            //start Insert user information
            $user = new User();
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->email = $request['email'];
            $user->user_name = $request['user_name'];
            $user->password = Hash::make($request['password']);
            $user->save();
            //End Insert user information
            
            //start Insert Package information
            $package = new Package();
            $package->activation_date = Carbon::now();

            switch ($request['package_name']) {
                case 1:
                    $package->package_name = '1 Month';
                    $package->expire_date = Carbon::now()->addDay(30);
                    break;

                case 2:
                    $package->package_name = '6 Months';
                    $package->expire_date = Carbon::now()->addDay(180);
                    break;

                case 3:
                    $package->package_name = '1 year';
                    break;
                
                default:
                    $package->package_name = 'undefined';
                    break;
            }
            switch ($request['payment_term']) {
                case 1:
                    $package->payment_term = 'Bkash';
                    break;

                case 2:
                    $package->payment_term = 'Nagad';
                    break;

                case 3:
                    $package->payment_term = 'Card';
                    break;
                
                default:
                    $package->payment_term = 'undefined';
                    break;
            }
            $package->save();
            //End Insert Package information

            //start Insert Concern information
            $concern = new Concern();
            $concern->name = $request['first_name'].' '.$request['last_name'];
            $concern->address = $request['address'];
            $concern->email = $request['email'];
            $concern->phone = $request['phone'];
            $concern->save();
            //End Insert Concern information

            DB::commit();

            return $user;
         
        }catch (\Exception $e) {

            DB::rollback();
            dd($e->getMessage());
            Toastr::error('',__('cmn.did_not_added'));
            return redirect()->back();
            
        }
    }
}
