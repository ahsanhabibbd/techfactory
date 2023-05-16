<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Package;
use App\Models\Concern;
use DB;
class SubscribeController extends Controller
{
    public function store(Request $request)
    {
        // dd( $request);
        DB::beginTransaction();
        try {

            // category
            $user = new User();
            $user = $request->only($user->getFillable());
            $user['password']= Hash::make($request['password']),
            $user->create($user);

            // $package = new Package();
            // $package = $request->only($package->getFillable());
            // $package->create($package);

            $concern = new Concern();
            $concern = $request->only($concern->getFillable());
            $concern->create($concern);

            DB::commit();
            Toastr::success('',__('cmn.successfully_created'));
            return redirect(app()->getLocale().'/admin/settings/privacy-policy');

        }catch (\Exception $e) {

            DB::rollback();
            dd($e->getMessage());
            Toastr::error('',__('cmn.did_not_added'));
            return redirect()->back();
            
        }
    }
}
