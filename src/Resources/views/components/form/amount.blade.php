<div x-data="amountInput()" x-init="init('{{ old(get_field_modal($info) ?? 'value', $info['default'] ?? '') }}')">
    <label class="twa-form-label">
        {{ $info['label'] }}
    </label>

    <div class="twa-form-input-container">
        <div class="twa-form-input-ring @isset($info['suffix']) has-suffix @endisset">

            <input @if(isset($info['readonly']) && $info['readonly']) readonly @endif type="text" :value="formatted"
                @input="format($event.target.value)" placeholder="{{ $info['placeholder'] }}" class="twa-form-input">
            <input type="hidden" wire:model="value" name="{{ $info['name'] }}" :value="raw">

            @isset($info['suffix'])
                <span class="placeholder-class mr-4 flex items-center">
                    {{ $info['suffix'] }}
                </span>
            @endisset
        </div>
    </div>


    @if (!(isset($info['translatable']) && $info['translatable']))
        @error(get_field_modal($info) ?? 'value')
            <span class="form-error-message">
                {{ $message }}
            </span>
        @enderror
    @endif
</div>

<script>
    function amountInput() {
        return {
            raw: '',
            formatted: '',

            init() {



                let initial = this.$wire.value;
                this.raw = (initial || '').toString().replace(/,/g, '');
                this.formatted = this.formatNumber(this.raw);
            },

            formatNumber(value) {
                if (!value) return '';
                return new Intl.NumberFormat('en-US').format(value);
            },

            format(value) {
                const numericValue = value.replace(/[^0-9.]/g, '');
                this.raw = numericValue;
                this.formatted = this.formatNumber(numericValue);

                const hiddenInput = document.querySelector('input[name="{{ $info['name'] }}"]');
                if (hiddenInput) {
                    hiddenInput.value = numericValue;
                    hiddenInput.dispatchEvent(new Event('input'));
                }
            }

        }
    }
</script>
