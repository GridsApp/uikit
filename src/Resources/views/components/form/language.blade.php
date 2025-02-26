@php
    $id = uniqid();
@endphp

<div x-on:keydown="keyDown" class="twa-language-element language-element" id="{{ $id }}"
    x-on:language-changed-{{ $id }}.window="handleChanged" x-data="Functions.initTranslatable()">

    <div class="relative">
        <div class="twa-language-element-position">
            <select tabindex="999" class="lang-picker" x-model="active" x-on:change="console.log('changed')">
                @foreach (config('languages') as $index => $language)
                    <option value="{{ $index }}"> {{ $language['prefix'] }}</option>
                @endforeach
            </select>
        </div>
        <div  x-ref="listelements">
            @foreach (config('languages') as $index => $language)
                @php
                    $field = [...$info];

                    if ($field['livewire']['wire:model'] ?? null) {
                        $field['livewire']['wire:model'] = $field['livewire']['wire:model'] . '_' . $language['prefix'];
                    }

                    if ($field['livewire']['wire:model.live'] ?? null) {
                        $field['livewire']['wire:model.live'] =
                            $field['livewire']['wire:model.live'] . '_' . $language['prefix'];
                    }

                    $field['translatable'] = false;
                    $field['name'] = $field['name'] . '_' . $language['prefix'];
                    $field['label'] = $field['label'] . ' [' . $language['language'] . ']';
                    $field['lang'] = $language['prefix'];

                    if ($index == 0) {
                        $validation_error = $field['livewire']['wire:model'];
                    } else {
                        $validation_error = null;
                    }

                    if ($index > 0) {
                        // $field['index'] = 999;
                    }

                @endphp
                <div x-cloak x-show= "active == '{{ $index }}'" class="toggle-active-{{ $index }}">
                    {!! field($field) !!}
                </div>

                @php
                    $r = collect($errors->getMessages())
                        ->map(function ($err, $index) {
                            return [
                                'group' => str($index)->beforeLast('_')->toString(),
                                'message' => $err,
                            ];
                        })
                        ->values()
                        ->groupBy('group');

                @endphp
            @endforeach
            @if ($r[$info['livewire']['wire:model']] ?? null)
                <span class="form-error-message">
                    {!! collect($r[$info['livewire']['wire:model']][0]['message'])->implode(' <br> ') !!}
                </span>
            @endif

        </div>
    </div>
</div>
