<div>
    <label class="twa-form-label">
        {{ $info['label'] }}
    </label>
    <div class="twa-form-input-container">
        <div
            class=" twa-form-input-ring">
            <input wire:model="value" type="date"
                class="twa-form-input">
        </div>
    </div>
    @error(get_field_modal($info) ?? 'value')
        <span class="form-error-message">
            {{ $message }}
        </span>
    @enderror
</div>
