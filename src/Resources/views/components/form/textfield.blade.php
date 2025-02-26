
 <div x-data="Functions.initTextField('{{ $info['channel'] ?? '' }}')" @if (isset($info['channel_type']) && $info['channel_type'] == 'receiver') x-on:{{ $info['channel'] }}.window="handleSlug" @endif>
 
 {{-- @dd(!(isset($info['translatable']) && $info['translatable']) && get_field_modal($info) ?? 'value') --}}
    <label class="twa-form-label">
        {{ $info['label'] }}
    </label>
    <div class="twa-form-input-container">
        <div
            class=" twa-form-input-ring">
            <input
                @if (isset($info['lang']) && $info['lang']) @if (isset($info['channel_language']) &&
                        $info['channel_language'] &&
                        isset($info['lang']) &&
                        $info['lang'] == $info['channel_language'] &&
                        isset($info['channel_type']) &&
                        $info['channel_type'] == 'sender')
                    x-on:input="handleInput" @endif
            @else @if (isset($info['channel_type']) && $info['channel_type'] == 'sender') x-on:input="handleInput" @endif @endif

            tabindex="{{ $info['index'] }}" wire:model="value" type="text"
            class="twa-form-input  ">
        </div>
    </div>


    {{-- @dd(!(isset($info['translatable']) && $info['translatable'])); --}}

    {{-- !(isset($info['translatable']) && $info['translatable']) && --}}



    @if(!(isset($info['translatable']) && $info['translatable']))
    @error(get_field_modal($info) ?? 'value')
        <span class="form-error-message">
            {{ $message }}
        </span>
    @enderror
    @endif
   
</div>
