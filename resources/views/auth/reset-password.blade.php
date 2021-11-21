<x-guest-layout>
<div class="font-sans">
        <div class="relative min-h-screen flex flex-col sm:justify-center items-center bg-gray-100 ">
            <div class="relative sm:max-w-sm w-full">
                <div class="card bg-blue-400 shadow-lg  w-full h-full rounded-3xl absolute  transform -rotate-6"></div>
                <div class="card bg-red-400 shadow-lg  w-full h-full rounded-3xl absolute  transform rotate-6"></div>
                <div class="relative w-full rounded-3xl  px-6 py-4 bg-gray-100 shadow-md">
                    <label for="" class="block mt-3 text-2xl text-gray-700 text-center font-semibold">
                        Laravel Library
                    </label>

                    <x-jet-validation-errors class="mb-4" />
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div>
                            <input type="email" name="email" placeholder="E-mail" class="mt-1 block w-full border-none bg-gray-100 h-11 rounded-xl shadow-lg hover:bg-blue-100 focus:bg-blue-100 focus:ring-0">
                        </div>
            
                        <div class="mt-7">                
                            <input type="password" name="password" placeholder="Password" class="mt-1 block w-full border-none bg-gray-100 h-11 rounded-xl shadow-lg hover:bg-blue-100 focus:bg-blue-100 focus:ring-0">                           
                        </div>
                        
                        <div class="mt-7">                
                            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="mt-1 block w-full border-none bg-gray-100 h-11 rounded-xl shadow-lg hover:bg-blue-100 focus:bg-blue-100 focus:ring-0">                           
                        </div>
            
                        <div class="mt-7">
                            <button class="bg-blue-500 w-full py-3 rounded-xl text-white shadow-xl hover:shadow-inner focus:outline-none transition duration-500 ease-in-out  transform hover:-translate-x hover:scale-105">
                                Reset Password
                            </button>
                        </div>        
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
