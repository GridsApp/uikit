@php
    $showHandler = $showHandler ?? "isDrawerOpen";
    $closeHandler = $closeHandler ?? "closeDrawer";
@endphp

<template x-teleport="body">
    <div x-show="{{$showHandler}}" @keydown.window.escape="{{$closeHandler}}" class="relative z-[99]">
        <div x-show="{{$showHandler}}" x-transition.opacity.duration.600ms x-on:click="{{$closeHandler}}"
            class="fixed inset-0 bg-twafieldsblack bg-opacity-10"></div>
        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="{{$showHandler}}" @click.away="{{$closeHandler}}"
                        x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                        x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                        class="w-screen max-w-md">
                        <div
                            class="flex flex-col h-full py-5 overflow-y-scroll bg-white border-l shadow-lg border-neutral-100/70">
                            <div class="px-4">
                                <div class="flex items-start justify-between pb-1">
                                    <h2 class="text-base font-semibold leading-6 text-twafieldsgray-900" id="slide-over-title">
                                        {{ $title ?? '' }}
                                    </h2>
                                    <div class="flex items-center h-auto ml-3">
                                        <button x-on:click.stop="{{$closeHandler}}"
                                            class="absolute top-0 right-0 z-30 flex items-center justify-center px-3 py-2 mt-4 mr-5 space-x-1 text-xs font-medium uppercase  rounded-md  text-neutral-600 hover:bg-neutral-100">
                                            <i class="fa-regular fa-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 pt-4">
                                {!! $slot !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
