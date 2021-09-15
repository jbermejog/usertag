<div class="p-6 bg-white border-b border-gray-200 sm:px-20">
    @if(session()->has('message'))
        <div class="relative flex items-center px-4 py-3 text-sm font-bold text-white bg-blue-500" role="alert"
             x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg class="w-4 h-4 mr-2 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path
                    d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/>
            </svg>
            <p>{{ session('message') }}</p>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
            <svg class="w-6 h-6 text-white fill-current" role="button" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 20 20"><title>Close</title><path
                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
        </span>
        </div>
    @endif
    <div class="flex justify-between mt-8 text-2xl">
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
                       class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"/>
            </div>
            <div class="mr-2">
                <input type="checkbox" class="mr-2 leading-tight" wire:model="active"/>¿Solo activos?
            </div>
        </div>
        <table class="w-full table-auto">
            <thead>
            <tr>
                <th class="px-4 py-2">
                    <div class="flex items-center">
                        <button wire:click="sortBy('name')" class="inline-flex items-center ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            <span class="font-bold text-gray-500">&nbsp;Nombre</span>
                        </button>
                    </div>
                </th>

                <th class="px-4 py-2">
                    <div class="flex items-center">
                        <span class="font-bold text-gray-500">&nbsp;Email</span>
                    </div>
                </th>

                @if(!$active)
                    <th class="hidden px-4 py-2 md:table-cell">
                        <span class="font-bold text-gray-500">
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
                    {{--                    <td class="px-4 py-2 border">{{ $item->id}}</td>--}}
                    <td class="border px-2 py-2 capitalize {!! $item->working == true ? 'bg-green-300':'' !!}">{{ mb_strtolower($item->name,'UTF-8')}}</td>
                    <td class="px-2 py-2 border"><a href="mailto:{{$item->email}}">
                            <div class="flex justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5 ml-2 text-blue-600 rounded-full cursor-pointer stroke-current feather feather-x hover:text-blue-400"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </a>
                    </td>

                    @if(!$active)
                        <td class="hidden px-4 py-2 border md:table-cell">
                            @if($item->status)
                                <div wire:click="toggleState('{{$item->id}}')" class="flex justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-5 h-5 ml-2 text-green-600 rounded-full cursor-pointer stroke-current feather feather-x hover:text-green-400"
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
                                         class="w-5 h-5 ml-2 text-red-600 rounded-full cursor-pointer stroke-current feather feather-x hover:text-red-400">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </div>
                            @endif
                        </td>
                    @endif
                    <td class="px-4 py-2 border">
                        <div class="flex justify-center">
                            <div wire:click="confirmItemEdit({{ $item->id}})"
                                 class="inline-flex items-center ">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5 ml-2 text-green-600 rounded-full cursor-pointer stroke-current feather feather-x hover:text-green-400"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </div>


                            <div wire:click="confirmItemDeletion({{ $item->id}})" wire:loading.attr="disabled"
                                 class="inline-flex items-center ">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-5 h-5 ml-2 text-red-600 rounded-full cursor-pointer stroke-current feather feather-x hover:text-red-400"
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

            <div class="col-span-6 mt-4 sm:col-span-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <x-jet-label for="name" value="{{ __('Nombre completo') }}*"/>
                        <x-jet-input id="name" type="text" class="block w-full mt-1" wire:model.defer="item.name"/>
                        <x-jet-input-error for="item.name" class="mt-2"/>
                    </div>
                    <div>
                        <x-jet-label for="email" value="{{ __('Email') }}*"/>
                        <x-jet-input id="email" type="text" class="block w-full mt-1" wire:model.defer="item.email"/>
                        <x-jet-input-error for="item.email" class="mt-2"/>
                    </div>

                </div>
            </div>


            <div wire:ignore  class="col-span-6 mt-4 sm:col-span-4">
                <label for="tags">
                    Tags*
                </label>
                <select id="tags"
                    wire:model="selectedTags"
                        class="w-full h-full py-2 pl-2 pr-4 mt-2 text-sm border border-gray-400 rounded-lg sm:text-base focus:outline-none focus:border-blue-400 select2"
                        multiple>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->tag }}</option>
                    @endforeach
                </select>
            </div>
            @error('tags')
            <div class="ml-1 text-sm text-red-500">
                {{ $message }}
            </div>
            @enderror


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

@push('scripts')
    <script>
        document.addEventListener("livewire:load", () => {
          let el = $('#tags')
          initSelect()

          Livewire.hook('message.processed', (message, component) => {
            initSelect()
          })

        //   Livewire.on('setCategoriesSelect', values => {
        //     el.val(values).trigger('change.select2')
        //   })

          el.on('change', function (e) {
            @this.set('selectedTags', el.select2("val"))
            // console.log(el.select2("val"))
          })

          function initSelect () {
            el.select2({
              placeholder: '{{__('Añadir Etiqueta')}}',
              allowClear: !el.attr('required'),
            })
          }
        })
    </script>
@endpush
