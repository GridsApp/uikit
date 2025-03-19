<div :style="actionsActive == '{{ $index }}' ? 'position:absolute; right: ' + coordinates[{{ $index }}]?.x +
    'px;top:' + coordinates[
        {{ $index }}]
    ?.y + 'px' : 'display:none'"
    x-cloak @click.away= "handleClickAway" class="dropdown-content ">

    @foreach ($table['row_operations'] as $row_operation)
        {{-- @dd( $row_operation['link']); --}}
        <div x-cloak 
        {{-- x-show="actions.allowEdit"  --}}
        class="dropdown-menu-item">
            <a :href="'{{ $row_operation['link'] }}'.replace('{id}', actionsActive)" class="dropdown-menu-link">
                <span class="dropdown-menu-icon">{!! $row_operation['icon'] !!}</span>
                <span class="dropdown-menu-title">{{ $row_operation['label'] }}</span>
            </a>
        </div>
    @endforeach
    <div x-cloak x-show="actions.allowDelete" class="dropdown-menu-item" x-data="{ showModal: false, handleOpen() { this.showModal = true } }"
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
                        'handler' => 'handleDelete([' . $index . '])',
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
