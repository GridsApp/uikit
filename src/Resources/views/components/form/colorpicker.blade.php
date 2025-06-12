<div>

    <label class="twa-form-label">

        {{ $info['label'] }}

        </label>

    <div class="twa-form-input-container">

        <div class="twa-form-input-ring">

            <div                class="twa-form-input-color flex items-center space-x-1"
                x-data="{
                
                    // Initialize hexOnly by removing # from Livewire value or default
                
                    hexOnly: (@entangle('value').defer || '#4F46E5').replace('#', '').toUpperCase(),
                
                
                
                    // Computed color with # prefix for color picker
                
                    get color() {
                
                        return '#' + this.hexOnly;
                
                    },
                
                    set color(val) {
                
                        this.hexOnly = val.replace('#', '').toUpperCase();
                
                        this.updateLivewire();
                
                    },
                
                
                
                    // Update Livewire value with # prefix whenever hexOnly changes
                
                    updateLivewire() {
                
                        $dispatch('input', this.color);
                
                        @this.set('value', this.color);
                
                    },
                
                
                
                    // Called when typing in text input
                
                    onTextInput(e) {
                
                        let val = e.target.value.toUpperCase();
                
                        if (val.startsWith('#')) {
                
                            val = val.substring(1);
                
                        }
                
                        // Only allow hex characters and max length 6
                
                        val = val.replace(/[^0-9A-F]/g, '').substring(0, 6);
                
                        this.hexOnly = val;
                
                        this.updateLivewire();
                
                    }
                
                }"           >

                <span class="text-color-hash">#</span>

                <input                    type="text"                    :value="hexOnly"
                    maxlength="6"                    class="text-color w-[100px]"
                    @input="onTextInput($event)"                />



                <input                    type="color"                    :value="color"
                    @input="color = $event.target.value"                    class="picker-color"
                    />

                </div>

            </div>

        </div>



    @error(get_field_modal($info) ?? 'value')
        <span class="form-error-message">

            {{ $message }}

            </span>
    @enderror

</div>
