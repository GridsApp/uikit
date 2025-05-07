<div>
    <label class="twa-form-label">
        {{ $info['label'] }}
    </label>
    <div class="twa-form-input-container">
        <div class="twa-form-input-ring">
            <div class="twa-form-input-color" x-data="{
                color: '#4F46E5',
                hexOnly: '4F46E5',
            
                syncToPicker() {
                    if (/^[0-9a-fA-F]{6}$/.test(this.hexOnly)) {
                        this.color = '#' + this.hexOnly;
                    }
                },
            
                syncToText(e) {
                    const val = e.target.value.replace('#', '').toUpperCase();
                    this.hexOnly = val;
                    this.color = '#' + val;
                }
            }" class="flex items-center space-x-1">
                <span class="text-color-hash">#</span>
                <input type="text" x-model="hexOnly" maxlength="6" pattern="[0-9a-fA-F]{6}" @input="syncToPicker"
                    class="text-color w-[100px]" />
 
                <input type="color" :value="color" @input="syncToText($event)" class="picker-color" />
            </div>
        </div>
 
    </div>
 
    @error(get_field_modal($info) ?? 'value')
        <span class="form-error-message">
            {{ $message }}
        </span>
    @enderror
 
</div>
 
 