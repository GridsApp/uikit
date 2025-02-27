<div class="twa-table-section " x-data="window.Functions.initTable()">
    <div class="twa-table-card ">
        <div class="twa-card-header ">
            <h3 class="twa-card-title">
                {{$title}}
            </h3>
            <div class="flex gap-5 items-center">

                <div>
                    {{-- <livewire:entity-components.table-filters :slug="$slug" /> --}}

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
                @if ($slug ?? null)
                    <template x-if="selected.length == 0">
                        {{-- {!! link_button('Add new Record', route('entity.create', ['slug' => $slug]), 'primary', 'text-[12px]') !!} --}}
                    </template>
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
                                    {{-- @dd($column['type']); --}}
                                    @if ($column['type'] == twa\uikit\Classes\ColumnTypes\IdType::class)
                                        <th class="w-[60px]">
                                            <input x-model="selectedAll" class="checkbox checkbox-sm"
                                                @change="handleSelectAll" type="checkbox">
                                        </th>
                                    @endif
                                    <th>
                                        {{ $column['label'] }}
                                    </th>
                                @endforeach

                                @if (in_array(twa\uikit\Classes\ColumnTypes\IdType::class, array_column($columns, 'type')))
                                    <th class="w-[60px] actions">
                                        Actions
                                    </th>
                                @endif

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rows as $row)
                                {{-- @dd($row); --}}
                                @php

                                    $i = $row->id;
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

                                        <td>



                                            {!! (new ($column['type'])($row->{$column['alias']}))->html() !!}


                                            {{--                 
                                            @if (is_array($row->{$column['alias']}))
                                            <div class="flex gap-1 items-center">
                                                @foreach ($row->{$column['alias']} as $item)


                                                <div class="twa-table-td-select"><span>  {{$item}}</span> </div>

                                                @endforeach
                                            </div>

                                            @else

                                            {{ $row->{$column['alias']} }}

                                            @endif --}}



                                        </td>
                                    @endforeach


                                    @if (in_array(twa\uikit\Classes\ColumnTypes\IdType::class, array_column($columns, 'type')))
                                        <td class="td-actions"
                                            :class="checkTDActionsDisabled('{{ $i }}') ? 'disabled' : ''"
                                            id="td-actions-{{ $i }}" data-target="{{ $i }}">

                                            <a :disabled="checkTDActionsDisabled('{{ $i }}')"
                                                href="javascript:;" class="icon"
                                                @click="handleBox(event , '{{ $i }}')">
                                                <i class="fa-regular fa-ellipsis-vertical"></i>
                                            </a>
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
        </div>
    </div>

    <div :style="actionsActive ? 'position:absolute; right: ' + coordinates[actionsActive]?.x + 'px;top:' + coordinates[
            actionsActive]
        ?.y + 'px' : ''"
        x-show="actionsActive != null" @click.away= "handleClickAway" class="dropdown-content">
        <div x-show="actions.allowEdit" class="dropdown-menu-item">
            <a :href="'/cms/{{ $slug }}/update/' + actionsActive" class="dropdown-menu-link">
                <span class="dropdown-menu-icon"><i class="fa-light fa-pen-to-square"></i></span>
                <span class="dropdown-menu-title">Edit Record</span>
            </a>
        </div>
        <div x-show="actions.allowDelete" class="dropdown-menu-item" x-data="{ showModal: false, handleOpen() { this.showModal = true } }"
            @click.away="showModal = false" @click="handleOpen">
            <div class="dropdown-menu-link">
                <span class="dropdown-menu-icon"> <i class="fa-solid fa-trash-can"></i></span>

                <div>

                    <span class="dropdown-menu-title">Delete Record</span>

                    @component('UIKitView::components.modal', [
                        'title' => 'Delete',
                        'variable' => 'showModal',
                        'action' => [
                            'label' => "'Delete'",
                            'type' => 'danger',
                            'handler' => 'handleDelete',
                        ],
                    ])
                        <div class="text-[13px] font-medium text-left text-gray-800 p-5">
                            Are you sure you want to delete records?
                        </div>
                    @endcomponent
                </div>
            </div>
        </div>
    </div>


    <br>
</div>
