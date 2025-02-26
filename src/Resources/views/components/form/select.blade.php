@php
    $quick_add = !isset($info['quick_add']) ? null : $info['quick_add'];
    $unique_id = $info['id'];

@endphp

<div class="twa-select" 
x-on:record-created-{{ $unique_id }}.window='handleCreateCallback' 

x-data="Functions.initSelect({{ $info['visible_selections'] ?? 5 }} , '{{$info['dispatch']['init'] ?? ''}}' , '{{$info['dispatch']['change'] ?? ''}}')">
    <label class="twa-form-label">
        {{ $info['label'] }}
    </label>
    <div class="twa-select-container form-input-element  @if ($quick_add) quick-add @endif">
        <div class="twa-select-container-inner">
            <div @click="openOptions()" class="twa-select-selections-container"
                :class="open ? 'twa-select-selections-container-opened' : ''">
                <template x-if="selectedOptions || (Array.isArray(selectedOptions) && selectedOptions.length > 0)">
                    <template x-for="selectedOption in visibleOptions" :key="selectedOption.value">
                        @if ($info['multiple'])
                            <span class="twa-select-selection-box">
                        @endif
                        <span x-text="selectedOption.label"></span>
                        @if ($info['multiple'])
                            <button @click="handleClearSelection($event, selectedOption.label)" type="button"
                                role="button">
                                <i class="fa-regular fa-x text-[10px]"></i>
                            </button>
                            </span>
                        @endif
                    </template>
                </template>
                <template
                    x-if="selectedOptions == null || !(Array.isArray(selectedOptions) && selectedOptions.length > 0)">
                    <span class="placeholder-class">{{ $info['placeholder'] }}</span>
                </template>
                <template x-if="hiddenOptions.length > 0">
                    <span class="twa-select-selection-box">
                        <button @click="showMoreHandler(event)" type="button" class="text-blue-500">
                            <span x-text="showMore ? 'Show Less' : `+${hiddenOptions.length} more`"></span>
                        </button>
                    </span>
                </template>
                <div class="twa-select-actions ">
                    <div @click="handleClear(event)" class="h-full w-[14px]  items-center flex justify-center">
                        <button type="button" role="button" class="twa-select-selections-remove"
                            x-show="Array.isArray(selectedOptions) && selectedOptions.length > 0">
                            <i class="fa-regular fa-x"></i>
                        </button>
                    </div>
                    <div class="w-[36px] h-full flex justify-center items-center">
                        <i class="fa-regular fa-angles-up-down"></i>
                    </div>
                    @if ($quick_add)
                        <div @click="openQuickAdd($event)"
                            class="flex w-[36px] cursor-pointer  rounded-r-md  justify-center h-full border-l border-twafieldsblack-200 hover:bg-twafieldsgray-100 ">
                            <button type="button" role="button" class="twa-select-add">
                                <i class="fa-regular fa-plus"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            <template x-teleport="body">
                <div
                    x-show="drawerOpen"
                    @keydown.window.escape="drawerOpen=false"
                    class="relative z-[99]">
                    <div x-show="drawerOpen" x-transition.opacity.duration.600ms @click="drawerOpen = false" class="fixed inset-0 bg-twafieldsblack bg-opacity-10"></div>
                    <div class="fixed inset-0 overflow-hidden">
                        <div class="absolute inset-0 overflow-hidden">
                            <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                                <div
                                    x-show="drawerOpen"
                                    @click.away="drawerOpen = false"
                                    x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                                    x-transition:enter-start="translate-x-full"
                                    x-transition:enter-end="translate-x-0"
                                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                                    x-transition:leave-start="translate-x-0"
                                    x-transition:leave-end="translate-x-full"
                                    class="w-screen max-w-md">
                                    <div class="flex flex-col h-full py-5 overflow-y-scroll bg-white border-l shadow-lg border-neutral-100/70">
                                        <div class="px-4">
                                            <div class="flex items-start justify-between pb-1">
                                                <h2 class="text-base font-semibold leading-6 text-twafieldsgray-900" id="slide-over-title">{{$info['label']}}</h2>
                                                <div class="flex items-center h-auto ml-3">
                                                    <button @click="drawerOpen=false" class="absolute top-0 right-0 z-30 flex items-center justify-center px-3 py-2 mt-4 mr-5 space-x-1 text-xs font-medium uppercase  rounded-md  text-neutral-600 hover:bg-neutral-100">
                                                        <i class="fa-regular fa-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-4 pt-4">
                                            @if (!empty($quick_add))
                                                    @livewire('entity-forms.form' , [ 'wire:key' => uniqid() ,'unique_id'=> $unique_id , 'slug' => $quick_add  ])
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            <div x-cloak x-show="open" x-on:click.outside="open = false" x-on:keydown.escape.window="open = false"
                x-intersect:leave="open = false" x-transition:enter="transition duration-100 ease-out"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2" class="twa-select-options-container">
                <div class="mt-2 px-2" :class="loading ? '' : 'mb-2'">
                    <div class="twa-select-search-container ">
                        <div class="twa-select-search-container-box">
                            <input type="text" @input.debounce.500ms="searchHandler()" x-model="search"
                                class="twa-select-search-input" placeholder="Search options">
                        </div>
                    </div>
                </div>
                <template x-if="loading">
                    <div class="twa-select-options-load">
                        <i class="fa-regular fa-loader"></i>
                        <span class="text-[14px]">Loading...</span>
                    </div>
                </template>
                <template x-if="!loading">
                    <div class="max-h-[300px] overflow-auto">
                        <template x-for="option in options" :key="option.value">
                            <template x-if="option.label">
                                <div class="twa-select-options-list-item">
                                    <input :id="option.identifier" type="{{ $info['multiple'] ? 'checkbox' : 'radio' }}"
                                        :value="option.value" name="{{ $info['name'] }}"
                                        x-model= "selectedValues"
                                           
                                        @foreach($info['events'] ?? [] as $key => $infoEvent)
                                               {{$key}}="{{$infoEvent}}"
                                        @endforeach
                                    >
                                    <label class="twa-select-options-label" :for="option.identifier"
                                        x-text="option.label"></label>
                                </div>
                            </template>
                        </template>
                        <template x-if="options.length == 0">
                            <div class="twa-select-options-list-item p-2 px-5">
                                <h2 class="twa-select-no-options-label">
                                    No results found
                                </h2>

                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
    @error(get_field_modal($info) ?? 'value')
    <span class="form-error-message {{get_field_modal($info)}}">
        {{$message}}
        </span>
    @enderror

</div>
