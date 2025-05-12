<div class="twa-table-section " x-data="window.Functions.initTable()">
    <div class="twa-table-card ">
        @if($title)
        <div class="twa-card-header ">
            <h3 class="twa-card-title">
                {{ $title }}
            </h3>
            <div class="flex gap-3 items-center">
                
                <div>
                    {{-- @include('UIKitView::components.table-filters') --}}
                </div>
        
             
              
             
         
            </div>
        </div>
        @endif
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
                                            {{-- @if ($sorting_column === $column['alias'])
                                                @if ($sorting_direction === 'asc')
                                                    ↑
                                                @else
                                                    ↓
                                                @endif
                                            @endif --}}
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
                {{-- {{ $rows->onEachSide(2)->links(data: ['scrollTo' => false]) }} --}}
            </div>
 
            {{-- <div>
                {{ $rows->onEachSide(2)->links("vendor.pagination.tailwind") }}
            </div> --}}
 
 
        </div>
    </div>
 
 
    {{-- <input type="text" x-model="actionsActive" /> --}}
 
    {{-- @foreach($rows as $i => $row)
 
 
    @if ($row->id ?? null)
        @include('UIKitView::components.row-operations' , ['index' => $row->id ?? ($i + 1) , 'row' => $row])
    @endif
 
   
    @endforeach --}}
 
 
 
    <br>
</div>
 
 