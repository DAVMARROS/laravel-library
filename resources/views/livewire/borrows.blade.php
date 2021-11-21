<div>
    @if(Auth::check() && Auth::user()->role_id == 1)
        <div class="flex flex-row-reverse">
            <div>
                <button class="m-5 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" wire:click="openModal()">
                <i class="fas fa-plus"></i> New Borrow</button>
            </div>
        </div>
    @endif
    <div class="flex flex-col">
        <div class="p-4 flex sm:flex-row flex-col">
            <div class="flex flex-row mb-1 sm:mb-0">
                <div class="relative w-18">
                    <select wire:model = "showByPage"
                        class="appearance-none h-full rounded-l border block appearance-none w-full bg-white border-gray-400 text-gray-700 py-2 px-4 pr-8 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        <option>5</option>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    </div>
                </div>
            </div>
            <div class="block relative w-full md:w-1/3 lg:w-1/3">
                <span class="h-full absolute inset-y-0 left-0 flex items-center pl-2">
                    <i class="fas fa-search"></i>
                </span>
                <input placeholder="Search" wire:model.debounce.200ms = "search"
                    class="appearance-none rounded-r rounded-l sm:rounded-l-none border border-gray-400 border-b block pl-8 pr-6 py-2 w-full bg-white text-sm placeholder-gray-400 text-gray-700 focus:bg-white focus:placeholder-gray-600 focus:text-gray-700 focus:outline-none" />
            </div>
        </div>
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-x-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="table-auto min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                             <tr>
                                <th scope="col" class="max-w-1/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Book
                                </th>
                                <th scope="col" class="max-w-1/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User
                                </th>
                                <th scope="col" class="max-w-1/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Expiration
                                </th>
                                <th scope="col" class="max-w-1/5 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="max-w-1/5 relative px-6 py-3">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(count($borrows))
                                @foreach($borrows as $borrow)
                                <tr>
                                    <td class="w-auto px-6 py-4">
                                        <div class="text-sm text-gray-900">{{$borrow->book->name}}</div>
                                    </td>
                                    <td class="w-auto px-6 py-4">
                                        <div class="text-sm text-gray-900">{{$borrow->user->name}}</div>
                                    </td>
                                    <td class="w-auto px-6 py-4">
                                        <div class="text-sm text-gray-900">{{$borrow->expired_at}}</div>
                                    </td>
                                    <td class="w-auto px-6 py-4">
                                        <div class="text-sm text-gray-900">{{$borrow->statusObj->name}}</div>
                                    </td>
                                    @if(Auth::check())
                                        <td class="w-auto px-6 py-4 text-right text-sm font-medium">
                                            <div class="">
                                                @if(Auth::user()->role_id == 1)
                                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" style="margin-right:20px;margin-bottom:5px;" 
                                                    wire:click="openModal({{$borrow->id}})">
                                                    <i class="fas fa-edit" style="margin-right:10px"></i>Change</button>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="w-auto px-6 py-4">
                                        <div class="text-gray-900 text-center font-bold">No data to display</div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{$borrows->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::check() && Auth::user()->role_id == 1)
        <x-jet-dialog-modal wire:model="triggerModal">
            <x-slot name="title">
                <h3>{{$changeStatus ? 'Change Borrow Status' : 'Add Borrow' }} </h3>
            </x-slot>
            <form wire:submit.prevent="submit">
                <x-slot name="content">
                    @if($changeStatus)
                        <x-jet-label for="Book" class="font-bold" value="{{ __('Book') }}" />
                        <x-jet-input id="Book" class="block mt-1 w-full" type="text" wire:model="book" required autocomplete="off" disabled/>
                        <x-jet-label for="user" class="font-bold" value="{{ __('User') }}" />
                        <x-jet-input id="user" class="block mt-1 w-full" type="text" wire:model="user" required autocomplete="off" disabled/>
                        <x-jet-label for="expiredAt" class="font-bold" value="{{ __('Expiration Date') }}" />
                        <x-jet-input id="expiredAt" class="block mt-1 w-full" type="date" wire:model="expiredAt" required autocomplete="off" disabled/>
                        <x-jet-label for="status" class="font-bold" value="{{ __('Status') }}" />
                        <select class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" wire:model="status">
                            @foreach($availableStatus as $status)
                                <option value="{{$status->id}}">{{$status->name}}</option>
                            @endforeach
                        </select>
                    @else
                        <x-jet-label for="book" class="font-bold" value="{{ __('Book') }}" />
                        <x-jet-input id="book" class="block mt-1 w-full" type="text" wire:model="book" required autocomplete="off"
                        wire:keydown.tab="$set('clear',1)" wire:keydown.debounce.100ms="autocomplete($event.target.value,1)" onfocusout="clearInputs()"/>
                            @if(count($resultBooks))
                                <div class="w-3/4 flex flex-col" style="position: absolute;z-index: 200; background-color: white; overflow-y: auto; max-height: 48%;">
                                @foreach($resultBooks as $result)
                                    <div class="cursor-pointer rounded-t border hover:bg-gray-100" wire:click="setInput('{{$result->name}}',1)">
                                        <div class="flex items-center p-2 pl-2 border-transparent border relative hover:bg-gray-100">
                                            <div class="items-center flex">
                                                <div class="mx-2 -mt-1">{{$result->name}}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            @endif
                        @error('category') <span class="error text-red-500">* {{ $message }}</span> @enderror
                        <x-jet-label for="user" class="font-bold" value="{{ __('User') }}" />
                        <x-jet-input id="user" class="block mt-1 w-full" type="text" wire:model="user" required autocomplete="off"
                        wire:keydown.tab="$set('clear',1)" wire:keydown.debounce.100ms="autocomplete($event.target.value,2)" onfocusout="clearInputs()" />
                        @if(count($resultUsers))
                                <div class="w-3/4 flex flex-col" style="position: absolute;z-index: 200; background-color: white; overflow-y: auto; max-height: 48%;">
                                @foreach($resultUsers as $result)
                                    <div class="cursor-pointer rounded-t border hover:bg-gray-100" wire:click="setInput('{{$result->name}}',2)">
                                        <div class="flex items-center p-2 pl-2 border-transparent border relative hover:bg-gray-100">
                                            <div class="items-center flex">
                                                <div class="mx-2 -mt-1">{{$result->name}}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            @endif
                        @error('user') <span class="error text-red-500">* {{ $message }}</span> @enderror
                    @endif
                </x-slot>
                <x-slot name="footer">
                    <x-secondary-button class="bg-gray-300" wire:click="$toggle('triggerModal')" wire:loading.attr="disabled">
                    {{ __('Close') }}
                    </x-secondary-button>
                    <x-secondary-button class="{{$changeStatus ? 'bg-blue-400' : 'bg-green-400' }}" wire:loading.attr="disabled" style="margin-left:20px;" wire:click="saveBorrow()">
                        {{$changeStatus ? 'Update' : 'Add' }}
                    </x-secondary-button>
                </x-slot>
            </form>
        </x-jet-dialog-modal>
    @endif

    @push('js')
        <script>
            function clearInputs(type = null){
                Livewire.emit('resetArrays');
            }

            window.livewire.on('autofocus', inputID => { 
                setTimeout(function(){ 
                    document.getElementById(inputID).focus(); 
                }, 300);
            });

            Livewire.on('Toast', title => {
                showToast("success",title);
            });

            function showToast(type,title){
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                })

                Toast.fire({
                    icon: type,
                    title: title
                });
            }
        </script>
    @endpush
</div>