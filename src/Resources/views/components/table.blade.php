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
                <div class="flex justify-center p-7.5 py-9">
                    <img alt="image" class=" max-h-[230px]" src="/images/empty.svg">
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
