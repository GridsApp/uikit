<div>
    <label class="twa-form-label">
        {{ $info['label'] }}
    </label>
    <div class="twa-form-input-container">
        <div class="twa-form-input-ring  @isset($info['prefix']) has-prefix @endisset">

            @isset($info['prefix'])
                <span
                    class="placeholder-class ml-2 mr-1  flex items-center ">{{ $info['prefix'] }}</span>
            @endisset
            <input wire:model="value" type="number" class="twa-form-input ">
        </div>
    </div>

   

    @error(get_field_modal($info) ?? 'value')
        <span class="form-error-message">
            {{ $message }}
        </span>
    @enderror
</div>
