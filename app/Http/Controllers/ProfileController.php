<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Country;
use App\Models\CustomerAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function view(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;
        // $shippingAddress =  $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        // $billingAddress =  $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
        $shippingAddress = $customer ? $customer->shippingAddress : new CustomerAddress(['type' => AddressType::Shipping]);
        $billingAddress = $customer ? $customer->billingAddress : new CustomerAddress(['type' => AddressType::Billing]);
    //    dd($customer, $shippingAddress->attributesToArray(), $billingAddress, $billingAddress->customer);
    //    dd($customer);
        $countries = Country::query()->orderBy('name')->get();
        return view('profile.view', compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }

    public function store(ProfileRequest $request)
    {
        $customerData = $request->validated();
        $shippingData = $customerData['shipping'];
        $billingData = $customerData['billing'];

        /** @var \App\Models\User $user */
        $user = $request->user();

        /** @var \App\Models\Customer $customer */
        $customer = $user->customer;
        $customer->update($customerData);

        if($customer->shippingAddress){
            $customer->shippingAddress->update($shippingData);
        } else {
            $shippingData['customer_id'] = $customer->user_id;
            $shippingData['type'] = AddressType::Shipping->value;
            CustomerAddress::create($shippingData);
        }

        if($customer->billingAddress){
            $customer->billingAddress->update($billingData);
        } else {
            $billingData['customer_id'] = $customer->user_id;
            $billingData['type'] = AddressType::Billing->value;
            CustomerAddress::create($billingData);
        }

        $request->session()->flash('flash_message', 'Profile updated successfully');

        return redirect()->route('profile');
    }
}
