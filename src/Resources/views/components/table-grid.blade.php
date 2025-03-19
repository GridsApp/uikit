<div class="twa-table-section " x-data="window.Functions.initTable()">
    <div class="twa-table-card ">
        <div class="twa-card-header ">
            <h3 class="twa-card-title">


                {{ $table["title"] }}
            </h3>
            <div class="flex gap-3 items-center">

                @if ($sorting_column || $sorting_direction)
                    <div>
                        <button wire:click="clearSorting" {{-- class="text-[12px] h-[34px] focus:shadow-outline group inline-flex items-center justify-center gap-x-2 border outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:border-transparent focus:ring-2   px-4 py-2 text-white ring-primary-500 bg-gray-400 focus:bg-primary-600 hover:bg-primary-600 border-transparent focus:ring-offset-2  rounded-md "> --}}
                            class="border text-[12px] font-bold flex h-[34px] items-center gap-2 px-5 py-2 rounded-md text-gray-600">

                            Clear Sorting
                        </button>
                    </div>
                @endif
                <div>
                    @include('UIKitView::components.table-filters')
                </div>
                <template x-if="selected.length > 0">
                    <div class="flex gap-5 items-center">


                        <div x-data="{ showModal: false, handleOpen() { this.showModal = true } }">
                            {!! button("'Delete ('+ selected.length + ')'", 'danger', null, 'button', null, 'handleOpen') !!}

                            @component('UIKitView::components.modal', [
                                'title' => 'Delete',
                                'variable' => 'showModal',
                                'action' => [
                                    'label' => "'Delete'",
                                    'type' => 'danger',
                                    'handler' => 'handleDeleteAll',
                                ],
                            ])
                                <div class="text-[13px] font-medium text-left text-gray-800 p-5">
                                    Are you sure you want to delete records?
                                </div>
                            @endcomponent
                        </div>

                    </div>
                </template>
                @foreach ($table_operations as $index => $table_operation)
                    @if ($index == 0)
                        <template x-if="selected.length == 0">
                            <div>
                                <a href="{{ $table_operation['link'] }}"
                                    class="text-[12px] h-[34px] focus:ring-offset-white focus:shadow-outline group inline-flex items-center justify-center gap-x-2 border outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 px-4 py-2 text-primary-50 ring-primary-500 bg-primary-500 focus:bg-primary-600 hover:bg-primary-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-dark-900 dark:focus:ring-primary-600 dark:bg-primary-700 dark:hover:bg-primary-600 dark:hover:ring-primary-600 rounded-md ">
                                    @if (isset($table_operation['icon']))
                                        <span>{!! $table_operation['icon'] ?? '' !!}</span>
                                    @endif {{ $table_operation['label'] ?? '' }}
                                </a>
                            </div>
                        </template>
                    @endif
                @endforeach

         
                @if (count($table_operations) > 1)
             
                    <div class="options-dropdown-container">
                        <div class="options-dropdown-container-header">
                            <button class="options-dropdown-button" @click="openDropdownOptions">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                        <div x-cloak x-show="openOptionsDropdown" @click.away="openOptionsDropdown = false"
                            class="options-dropdown-content absolute top-[45px]">
                            <div class="options-box">
                                @foreach ($table_operations as $index => $table_operation)
                                    @if ($index > 0)
                                        <div class="options-dropdown-menu-item">
                                            <div class="options-dropdown-menu-link">

                                                {!! $table_operation['icon'] ?? '' !!}
                                                <div>
                                                    <a href="{{ $table_operation['link'] }}"
                                                        class="options-dropdown-menu-title cursor-pointer">
                                                        {{ $table_operation['label'] ?? '' }}
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="twa-card-body">
            @if (count($rows) > 0)
                <div class="">
                    <table class="twa-table table-auto">
                        <thead>
                            <tr>
                                @foreach ($columns as $column)
                                    {{-- @dd($column) --}}
                                    @if ($column['type'] == twa\uikit\Classes\ColumnTypes\IdType::class)
                                        <th class="w-[60px]">
                                            <input x-model="selectedAll" class="checkbox checkbox-sm"
                                                @change="handleSelectAll" type="checkbox">
                                        </th>
                                    @endif
                                    @if ($column['type'] !== twa\uikit\Classes\ColumnTypes\Hidden::class)
                                        <th x-on:dblclick="$wire.clearSorting()"
                                            wire:click="setSorting('{{ $column['alias'] }}')" class="cursor-pointer">
                                            {{ $column['label'] }}
                                            @if ($sorting_column === $column['alias'])
                                                @if ($sorting_direction === 'asc')
                                                    ↑
                                                @else
                                                    ↓
                                                @endif
                                            @endif
                                        </th>
                                    @endif
                                @endforeach

                                @if ($rows[0]->id ?? null)
                                    <th class="w-[60px] actions">
                                        Actions
                                    </th>
                                @endif

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rows as $tableIndex => $row)
                        
                                @php
                    
                                    if ($row->id ?? null) {
                                        $i = $row->id;
                                    } else {
                                        $i = $tableIndex + 1;
                                    }

                                  
                                @endphp

                                <tr wire:key="key_{{ $i }}" data-id="{{ $i }}">



                                   

                                    @foreach ($columns as $column)
                                        {{-- @dd($column); --}}

                                       

                                        @if ($column['type'] == twa\uikit\Classes\ColumnTypes\IdType::class)
                                            <td>
                                                <input class="checkbox checkbox-row" x-model="selected" type="checkbox"
                                                    value="{{ $i }}" @change="handleSelect">
                                            </td>
                                        @endif
                                        @if ($column['type'] !== twa\uikit\Classes\ColumnTypes\Hidden::class)
                                        <td>


                                        
                                         


                                            {!! (new ($column['type'])($row->{$column['alias']} , $row))->html($column['parameters'] ?? []) !!}

                         
                                        </td>
                                        @endif
                                    @endforeach


                                    @if ($row->id ?? null)
                                        <td class="td-actions"
                                            :class="checkTDActionsDisabled('{{ $i }}') ? 'disabled' : ''"
                                            id="td-actions-{{ $i }}" data-target="{{ $i }}">

                                            <a :disabled="checkTDActionsDisabled('{{ $i }}')"
                                                href="javascript:;" class="icon"
                                                @click="handleBox(event , '{{ $i }}')">
                                                <i class="fa-regular fa-ellipsis-vertical"></i>
                                            </a>


                                            {{-- @include('UIKitView::components.row-operations' , ['index' => $i]) --}}


                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex justify-center p-7.5 py-9">
                    <img alt="image" class=" max-h-[230px]" src="/images/empty.svg">

                </div>
                @if ($entity['slug'] ?? null)
                    <div class="flex flex-col gap-5 lg:gap-7.5">
                        <div class="flex flex-col gap-3 text-center">
                            <h2 class="text-1.5xl font-semibold text-gray-900">
                                New Member Onboarding and Registration
                            </h2>
                            <p class="text-sm text-gray-800">
                                A streamlined process to welcome and integrate new members into the team,
                                <br>
                                ensuring a smooth and efficient start.
                            </p>
                        </div>
                        <div class="flex justify-center mb-5">
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div class="card-footer py-5 container-fixed">
            <div>
                {{ $rows->onEachSide(2)->links(data: ['scrollTo' => false]) }}
            </div>

            {{-- <div>
                {{ $rows->onEachSide(2)->links("vendor.pagination.tailwind") }}
            </div> --}}


        </div>
    </div>


    {{-- <input type="text" x-model="actionsActive" /> --}}

    @foreach($rows as $i => $row)


    @if ($row->id ?? null)
        @include('UIKitView::components.row-operations' , ['index' => $row->id ?? ($i + 1)])
    @endif

   
    @endforeach



    <br>
</div>
