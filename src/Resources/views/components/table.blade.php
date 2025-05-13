<div class="twa-table-section " x-data="window.Functions.initTable()">
    <div class="twa-table-card ">
        @if ($title)
            <div class="twa-card-header ">
                <h3 class="twa-card-title">
                    {{ $title }}
                </h3>
                <div class="flex gap-3 items-center">
                    <div>
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
                                    @if ($column['type'] !== twa\uikit\Classes\ColumnTypes\Hidden::class)
                                        <th class="cursor-pointer">
                                            {{ $column['label'] }}
                                        </th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($rows as $tableIndex => $row)
                                <tr>
                                    @foreach ($columns as $column)
                                        @if ($column['type'] !== twa\uikit\Classes\ColumnTypes\Hidden::class)
                                            <td>
                                                {!! (new ($column['type'])($row->{$column['alias']}, $row))->html($column['parameters'] ?? []) !!}
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
            <div class="flex flex-col h-full items-center justify-center gap-5 p-7.5 py-9">
 
                <div>
                    <img alt="image" class=" max-h-[100px]" src="/images/empty.png">
                </div>
                <div >
                    <p class="text-md text-center font-medium">No data available</p>
                    <p class="text-sm text-center pt-2">There are currently no records to display. Please check back later or adjust
                        your filters.</p>

                </div>
            </div>

            @endif
        </div>
        <div class="card-footer py-5 container-fixed">
            <div>

            </div>

            {{-- <div>
                {{ $rows->onEachSide(2)->links("vendor.pagination.tailwind") }}
            </div> --}}


        </div>
    </div>
    <br>
</div>
