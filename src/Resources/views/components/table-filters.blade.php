@php
    $filters = collect($filters);
@endphp

<div x-data="window.Functions.initFilter()" class="filter-dropdown-container" x-on:clear-filters.window="open = false"
    x-on:apply-filters.window="open = false">
    @if ($filters->count() > 0)
        <div class="filter-dropdown-container-header">
            <button class="filter-dropdown-button " @click="openDropdown">
                <i class="fa-solid fa-filter text-[10px]"></i>
                <div class="text-[12px] font-bold flex items-center gap-2">
                    Filter
                    <template x-if="$wire.enabledFilterCount > 0">
                        <div class="filter-dropdown-button-counter">
                            <span x-text="$wire.enabledFilterCount"> </span>
                        </div>
                    </template>
                </div>
            </button>
        </div>
    @endif
    <div x-cloak x-show="open" @click.away="open = false" class="filter-dropdown-content absolute top-[45px]">
        <div class="head ">
            <div class="head-content">
                <div>
                    <button wire:click="clearFilters"
                        class="bg-gray-200 px-3 py-1 text rounded-md text-[10px] hover:bg-gray-300">Clear</button>
                </div>
                <div class="text font-bold text-[12px]">Filters</div>
                <div>
                    <button wire:click="applyFilters()"
                        class="bg-twafieldsprimary-500 text-[10px] px-3 py-1 text-white rounded-md">Apply
                    </button>
                </div>
            </div>
        </div>
        <div class="filter-box">
            @foreach ($filters as $filter)
          
                @php
                    $initSelected = $this->filter[$filter['name']]['enabled'] ?? false ? true : false;
                @endphp
                <div>
                    <div class="filter-dropdown-menu-item" x-data="initFilterItem({{ $initSelected }})"
                        x-on:clear-filters.window="openDropdown = false">
                        <label class="filter-dropdown-menu-link gap-3">
                            <input  type="checkbox"
                            class="checkbox checkbox-sm"
                            {{-- class="w-4 h-4 text-blue-600  border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" type="checkbox" --}}
                               
                                wire:model="filter.{{ $filter['name'] }}.enabled"  
                                x-on:change="openDropdown = !openDropdown">
                            <div class="filter-dropdown-menu-title cursor-pointer">
                                {{ $filter['label'] }}
                            </div>
                        </label>

                        <div x-show="openDropdown" x-cloak
                            x-transition:enter="transition ease-out origin-top-left duration-500"
                            x-transition:enter-start="opacity-0 transform scale-90"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition origin-top-left ease-in duration-400"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-90" class=" px-3 py-2 rounded bg-white">
                            <div>
                                <div class="flex flex-col gap-2">
                                    @if ($filter['operand'])
                                        <div class="flex flex-col gap-2">
                                            {!! field($filter['operand']) !!}
                                        </div>
                                    @endif
                                    <div>
                                        {!! field($filter['field1']) !!}
                                    </div>
                                    <div x-show="hideField2">
                                        {!! field($filter['field2']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
