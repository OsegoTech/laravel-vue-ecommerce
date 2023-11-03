<x-app-layout>
    <div x-data="{
        flashMessage: '{{\Illuminate\Support\Facades\Session::get('flash_message')}}',
        init() {
            if (this.flashMessage) {
                setTimeout(() => this.$dispatch('notify', {message: this.flashMessage}), 200)
            }
        }
    }" class="container mx-auto lg:w-2/3 p-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            <div class="bg-white p-3 shadow rounded-lg md:col-span-2">
                <form x-data="{
                    countries: {{ json_encode($countries) }},
                    billingAddress: {{ json_encode([
                        'address1' => old('billing.address1', optional($billingAddress)->address1),
                        'address2' => old('billing.address2', optional($billingAddress)->address2),
                        'city' => old('billing.city', optional($billingAddress)->city),
                        'state' => old('billing.state', optional($billingAddress)->state),
                        'country_code' => old('billing.country_code', optional($billingAddress)->country_code),
                        'zipcode' => old('billing.zipcode', optional($billingAddress)->zipcode),
                    ]) }},
                    shippingAddress: {{ json_encode([
                        'address1' => old('shipping.address1', optional($shippingAddress)->address1),
                        'address2' => old('shipping.address2', optional($shippingAddress)->address2),
                        'city' => old('shipping.city', optional($shippingAddress)->city),
                        'state' => old('shipping.state', optional($shippingAddress)->state),
                        'country_code' => old('shipping.country_code', optional($shippingAddress)->country_code),
                        'zipcode' => old('shipping.zipcode', optional($shippingAddress)->zipcode),
                    ]) }},
                    get billingCountryStates() {
                        const country = this.countries.find(c => c.code === this.billingAddress.country_code)
                        if (country && country.states) {
                            return JSON.parse(country.states);
                        }
                        return null;
                    },
                    get shippingCountryStates() {
                        const country = this.countries.find(c => c.code === this.shippingAddress.country_code)
                        if (country && country.states) {
                            return JSON.parse(country.states);
                        }
                        return null;
                    }
                }" action="{{ route('profile.update') }}" method="post">
                    @csrf
                    <h2 class="text-xl font-semibold mb-2">Profile Details</h2>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <x-input
                            type="text"
                            name="first_name"
                            value="{{ old('first_name', optional($customer)->first_name) }}"
                            placeholder="First Name"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name', optional($customer)->last_name) }}"
                            placeholder="Last Name"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
                    <div class="mb-3">
                        <x-input
                            type="text"
                            name="email"
                            value="{{ old('email', optional($user)->email) }}"
                            placeholder="Your Email"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
                    <div class="mb-3">
                        <x-input
                            type="text"
                            name="phone"
                            value="{{ old('phone', optional($customer)->phone) }}"
                            placeholder="Your Phone"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>

                    <!-- Rest of your form -->

                    <x-button class="w-full">Update</x-button>
                </form>
            </div>
            <div class="bg-white p-3 shadow rounded-lg">
                <!-- The password update form -->
            </div>
        </div>
    </div>
</x-app-layout>
