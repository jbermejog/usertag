<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    @if(session()->has('message'))
        <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3 relative" role="alert"
             x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path
                    d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/>
            </svg>
            <p>{{ session('message') }}</p>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
            <svg class="fill-current h-6 w-6 text-white" role="button" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 20 20"><title>Close</title><path
                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
        </div>
    @endif
    <div class="mt-8 text-2xl flex justify-between">
        <div>Empleados</div>
        <div class="mr-2">
                <x-jet-button wire:click="confirmItemAdd" class="bg-blue-500 hover:bg-blue-700">
                    Agregar nuevo Empleado
                </x-jet-button>
        </div>
    </div>
    <div class="mt-6">
        <div class="flex justify-between">
            <div class="">
                <input wire:model.debounce.500ms="q" type="search" placeholder="Buscar"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
            </div>
            <div class="mr-2">
                <input type="checkbox" class="mr-2 leading-tight" wire:model="active"/>¿Solo activos?
            </div>
        </div>
        <table class="table-auto w-full">
            <thead>
            <tr>
                <th class="px-4 py-2">
                    <div class="flex items-center">
                        <button wire:click="sortBy('name')" class="inline-flex items-center ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            <span class="text-gray-500 font-bold">&nbsp;Nombre</span>
                        </button>
                    </div>
                </th>

                <th class="px-4 py-2">
                    <div class="flex items-center">
                        <span class="text-gray-500 font-bold">&nbsp;Email</span>
                    </div>
                </th>

                @if(!$active)
                    <th class="hidden md:table-cell px-4 py-2">
                        <span class="text-gray-500 font-bold">
                            Activo
                        </span>
                    </th>
                @endif
                <th class="px-4 py-2">
                    &nbsp;
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr {!! $loop->index % 2 == 0 ?'':'class="bg-blue-50"' !!}}>
                    {{--                    <td class="border px-4 py-2">{{ $item->id}}</td>--}}
                    <td class="border px-2 py-2 capitalize {!! $item->working == true ? 'bg-green-300':'' !!}">{{ mb_strtolower($item->name,'UTF-8')}}</td>
                    <td class="border px-2 py-2"><a href="mailto:{{$item->email}}">
                            <div class="flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="feather stroke-current text-blue-600 feather-x cursor-pointer hover:text-blue-400 rounded-full w-5 h-5 ml-2"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </a>
                    </td>

                    @if(!$active)
                        <td class="hidden md:table-cell border px-4 py-2">
                            @if($item->status)
                                <div wire:click="toggleState('{{$item->id}}')" class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="feather stroke-current text-green-600 feather-x cursor-pointer hover:text-green-400 rounded-full w-5 h-5 ml-2"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @else
                                <div wire:click="toggleState('{{$item->id}}')" class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="feather stroke-current text-red-600 feather-x cursor-pointer hover:text-red-400 rounded-full w-5 h-5 ml-2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </div>
                            @endif
                        </td>
                    @endif
                    <td class="border px-4 py-2">
                        <div class="flex justify-center">
                            <div wire:click="confirmItemEdit({{ $item->id}})"
                                 class="inline-flex items-center ">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="feather stroke-current text-green-600 feather-x cursor-pointer hover:text-green-400 rounded-full w-5 h-5 ml-2"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </div>


                            <div wire:click="confirmItemDeletion({{ $item->id}})" wire:loading.attr="disabled"
                                 class="inline-flex items-center ">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="feather stroke-current text-red-600 feather-x cursor-pointer hover:text-red-400 rounded-full w-5 h-5 ml-2"
                                     fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $items->links() }}
    </div>
    <x-jet-confirmation-modal wire:model="confirmingItemDeletion">
        <x-slot name="title">
            {{ __('Eliminar empleado') }}
        </x-slot>

        <x-slot name="content">
            {{ __('¿Estas seguro de eliminar este empleado? ') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('confirmingItemDeletion', false)" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteItem({{ $confirmingItemDeletion }})"
                                 wire:loading.attr="disabled">
                {{ __('Eliminar') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
    <x-jet-dialog-modal wire:model="confirmingItemAdd">
        <x-slot name="title">
            {{ isset( $this->item->id) ? 'Editar empleado' : 'Agregar empleado'}}
        </x-slot>
        <x-slot name="content">

            <div class="col-span-6 sm:col-span-4 mt-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <x-jet-label for="name" value="{{ __('Nombre completo') }}*"/>
                        <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="item.name"/>
                        <x-jet-input-error for="item.name" class="mt-2"/>
                    </div>
                    <div>
                        <x-jet-label for="email" value="{{ __('Email') }}*"/>
                        <x-jet-input id="email" type="text" class="mt-1 block w-full" wire:model.defer="item.email"/>
                        <x-jet-input-error for="item.email" class="mt-2"/>
                    </div>

                </div>
            </div>


        </x-slot>
        <x-slot name="footer">
            <label class="flex items-center ml-2 text-sm text-gray-600">* Campos obligatorios</label>
            <x-jet-secondary-button wire:click="$set('confirmingItemAdd', false)" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="saveItem()" wire:loading.attr="disabled">
                {{ __('Guardar') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
