<div x-data="Functions.fileUploader({{ $info['aspect_ratio'] }}, '{{ $info['multiple'] }}')" x-init="init()">
    <label class="twa-form-label">
        {{ $info['label'] }}
    </label>
    <div class="relative" @click.away = "show = false">
        <div>
            <div class="twa-form-input-container">
                <div class="twa-form-input-ring">
                    <div class="twa-form-input"
                        x-on:click="show = !show" x-text="'Choose images (' + uploaded.length + ' uploaded) '">
                    </div>
                    <span
                        class="ml-1 absolute top-0 bottom-0 right-[16px] flex pointer-events-none items-center z-10">
                        <button type="button">
                            <i class="fa-regular fa-upload"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div x-show="show" x-cloak class="absolute top100pers z-40 rounded-lg border bg-white p-3 w-full">
                <div class="flex flex-col w-full items-center justify-center">
                    <div
                        class="relative flex h-20 w-full cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-twafieldsgray-300 bg-twafieldsgray-50 transition">
                        <div class="inline-flex items-center justify-center space-x-2">
                            <i class="fa-sharp fa-solid fa-cloud-arrow-up"></i>
                            <p class="text-sm dark:text-twafieldsdark-300 font-bold text-twafieldsgray-600">Click here to upload</p>
                        </div>
                        <input @input="handleFileUpload" @if ($info['multiple']) multiple @endif
                            type="file" class="absolute inset-0 h-full w-full cursor-pointer opacity-0">

                    </div>
                </div>
                <div class="soft-scrollbar mt-4 max-h-64 w-full overflow-auto px-2" x-ref="items">
                    <ul role="list" class="dark:divide-twafieldsdark-700 divide-y divide-twafieldsgray-100">
                        <template x-for="(image_uploaded , index_uploaded) in uploaded">

                            <li class="li flex justify-between gap-x-6 py-2" x-key="image_uploaded.uploaded">
                                <div class="flex min-w-0 gap-x-4">

                                    <div class="cursor-pointer" @click="showImagePreview(image_uploaded.url)">

                                        <img :src="image_uploaded?.url"
                                            class="h-12 w-12 flex-none rounded-full bg-twafieldsgray-50 cursor-pointer" />

                                    </div>


                                    <div class="min-w-0 flex-auto">
                                        <p x-text="image_uploaded?.name"
                                            class="dark:text-twafieldsdark-300 truncate text-sm font-semibold leading-6 text-twafieldsgray-900">
                                        </p>
                                        <p class="dark:text-twafieldsdark-300 mt-1 text-xs leading-5 text-twafieldsgray-500">
                                            <span x-text="image_uploaded?.status"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex shrink-0 flex-col items-end">
                                    <button type="button" @click="removeImage(index_uploaded)">
                                        <i class="fa-solid text-red-500 fa-trash-can"></i>
                                    </button>
                                </div>
                            </li>
                        </template>

                        <template x-for="(image,index) in previews">
                            <template x-if="image.progress < 101">
                                <li class="li flex justify-between gap-x-6 py-2" x-key="index">
                                    <div class="flex min-w-0 gap-x-4">
                                        <div class="twa-circular-progress cursor-pointer"
                                            @click="showImagePreview(image.url)">
                                            <div class="twa-circular-progress-percent">
                                                <svg>
                                                    <circle class="first-circle" cx="25" cy="25" r="23.5">
                                                    </circle>
                                                    <circle
                                                        :class="image?.progress == 100 ? (image?.status != 'uploaded' ?
                                                                'completed-finish' : 'completed-progress') :
                                                            'second-circle'"
                                                        cx="25" cy="25" r="23.5"
                                                        :style="'--percent:' + image?.progress"></circle>
                                                </svg>

                                                <img :src="image.url"
                                                    class="flex-none rounded-full bg-twafieldsgray-50 cursor-pointer" />
                                            </div>
                                        </div>


                                        <div class="min-w-0 flex-auto">
                                            <p x-text="image.name"
                                                class="dark:text-twafieldsdark-300 truncate text-sm font-semibold leading-6 text-twafieldsgray-900">
                                            </p>
                                            <p class="dark:text-twafieldsdark-300 mt-1 text-xs leading-5 text-twafieldsgray-500">
                                                <span>Size: </span>
                                                <span x-text="image.size"></span>
                                                <span x-text="image.status"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex shrink-0 flex-col items-end justify-center">
                                        <button x-show="image.status != 'cancelled'" x-on:click="image.cancleHandler"
                                            type="button">

                                            <i class="fa-regular fa-x"></i>

                                        </button>
                                    </div>
                                </li>
                            </template>
                        </template>
                    </ul>
                </div>
                <div x-show="showImageModal" class="fixed inset-0 z-50  bg-twafieldsgray-800 bg-opacity-75" x-cloak
                    @click="showImageModal = false">
                    <div class=" twa-modal-container rounded-lg ">
                        <div class="twa-modal-image-container">

                            <button class="twa-modal-close-btn" type="button" @click="closeModal">
                                <i class="fa-regular  fa-xmark"></i>
                            </button>
                            <img @click.stop :src="modalImageUrl" class="max-w-full object-contain max-h-full rounded"
                                alt="Image Preview">

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div x-show="showPreview" x-cloak class="twa-upload-preview">
            <div class="twa-upload-preview-tools">
                <div class="twa-upload-preview-container">
                    <div class="twa-upload-preview-tools-inner">
                        <div class="flex-1 flex gap-3">
                            <button x-show="cropper === null" type="button" @click="closePreview">
                                <i class="fa-regular hover:text-[#6d6c6c] fa-xmark"></i>
                            </button>
                            <button x-show="cropper === null && enable_crop" type="button" @click="initCrop">
                                <i class="fa-regular hover:text-[#6d6c6c] fa-crop-simple"></i>
                            </button>
                        </div>
                        <div class="flex-1 flex items-center justify-end">
                            <button @click="upload" x-show="cropper === null"
                                x-text="'Upload (' + previews.length + ')'" role="button"
                                class="focus:ring-offset-white focus:shadow-outline group inline-flex items-center justify-center gap-x-2 border outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-xs px-4 py-2 text-twafieldsprimary-50 ring-twafieldsprimary-500 bg-twafieldsprimary-500 focus:bg-twafieldsprimary-600 hover:bg-twafieldsprimary-600 border-transparent focus:ring-offset-2 dark:focus:ring-offset-twafieldsdark-900 dark:focus:ring-twafieldsprimary-600 dark:bg-twafieldsprimary-700 dark:hover:bg-twafieldsprimary-600 dark:hover:ring-twafieldsprimary-600 rounded-md"
                                type="button">
                            </button>
                            <div x-show="cropper != null" class="flex gap-1">
                                <button role="button"
                                    class="focus:ring-offset-white focus:shadow-outline group inline-flex items-center justify-center gap-x-2  outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-xs px-4 py-2 focus:ring-offset-background-white text-stone-600 hover:bg-opacity-25 dark:hover:bg-opacity-15 hover:text-stone-700 hover:bg-stone-400 dark:hover:text-stone-500 dark:hover:bg-stone-600 focus:bg-opacity-25 dark:focus:bg-opacity-15 focus:ring-offset-0 focus:text-stone-700 focus:bg-stone-400 focus:ring-stone-600 dark:focus:text-stone-500 dark:focus:bg-stone-600 dark:focus:ring-stone-700 rounded-md"
                                    type="button" @click="doneCropping">
                                    Done
                                </button>
                                <button role="button"
                                    class="focus:ring-offset-white focus:shadow-outline group inline-flex items-center justify-center gap-x-2  outline-none transition-all duration-200 ease-in-out hover:shadow-sm focus:-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-xs px-4 py-2 focus:ring-offset-background-white text-red-600 hover:bg-opacity-25 dark:hover:bg-opacity-15 hover:text-red-700 hover:bg-red-400 dark:hover:text-red-500 dark:hover:bg-red-600 focus:bg-opacity-25 dark:focus:bg-opacity-15 focus:ring-offset-0 focus:text-red-700 focus:bg-red-400 focus:ring-red-600 dark:focus:text-red-500 dark:focus:bg-red-600 dark:focus:ring-red-700 rounded-md"
                                    type="button" @click="cancelCropping">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="twa-upload-preview-image">
                <div class="twa-upload-preview-container">
                    <div class="twa-upload-preview-image-inner">
                        <img :src="previews[previewSelectedIndex]?.url" x-ref="previewimage" alt="">
                    </div>
                </div>
            </div>



            <div class="twa-upload-preview-thumbnails">
                <div class="twa-upload-preview-container ">
                    <div x-data="Functions.initCarousel($el)" class="overflow-x-auto scrollbar-hide relative py-2"
                        style="overflow-y: hidden;">
                        <div class="twa-upload-preview-thumbnail-inner " style="width: max-content;">
                            <template x-for="(previewImage, index) in previews" :key="index">
                                <div @click="handleImageChange(index)"
                                    :class="index == previewSelectedIndex ? 'twa-upload-preview-thumbnail-active' : ''"
                                    class="flex-none snap-center relative cursor-pointer min-w-[60px] min-h-[60px] max-w-[60px] max-h-[60px] twa-upload-preview-thumbnail">

                                    <img :src="previewImage.url"
                                        class="absolute top-0 right-0 bottom-0 w-full h-full left-0 z-9 object-contain">
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @error(get_field_modal($info) ?? 'value')
        <span class="form-error-message">
            {{ $message }}
        </span>
    @enderror

</div>
