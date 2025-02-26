<div>
    <label class="twa-form-label">
        {{ $info['label'] }}
    </label>
    <div class="relative mt-1 flex items-center form-input-element">
        <label class="relative inline-flex cursor-pointer items-center h-[36px]">
            <div class="flex items-center">
                <div class="relative flex items-center justify-end">
                    <input  wire:model="value" type="checkbox"
                        class="peer absolute inset-y-0 left-0.5 translate-x-0 my-0.5 transform cursor-pointer appearance-none rounded-full border-0 bg-white shadow transition duration-200 ease-in-out checked:bg-none checked:text-white focus:outline-none focus:ring-0 focus:ring-offset-0 h-4 w-4 checked:translate-x-4">
                    <div
                        class="bg-twafieldssecondary-200  block cursor-pointer rounded-full transition duration-100 ease-in-out group-focus:ring-2 group-focus:ring-offset-2 peer-focus:ring-2 peer-focus:ring-offset-2 h-5 w-9 peer-checked:bg-twafieldsprimary-500 peer-focus:ring-twafieldsprimary-500 group-focus:ring-twafieldsprimary-500 ">
                    </div>
                </div>
            </div>
        </label>
    </div>
    @error('value')

        <span class="form-error-message">
            The field is required.
        </span>
    @enderror

</div>
