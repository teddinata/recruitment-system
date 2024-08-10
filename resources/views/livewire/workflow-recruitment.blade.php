<div class="wrapper w-full mx-auto">
    <div class="flex flex-col gap-y-4" x-data="diagram">
        <div class="lg:h-[286px] xl:h-[425px] 2xl:h-[560px] 3xl:h-[580px] border border-gray-400 rounded dark:border-gray-700 mb-4 flex justify-between"
            wire:ignore>
            <div id="bpmn-container" class="w-full h-full object-cover"></div>
            <div class="h-full w-[34px] flex flex-col items-center justify-center mx-3 space-y-2">
                <button type="button" @click="fitToViewport"
                    class="bg-primary-600 rounded-lg px-3 py-2 w-10 h-10font-semibold items-center transition duration-75 justify-center outline-none focus-visible:ring-2 inline-grid shadow-sm text-white hover:bg-primary-500 focus-visible:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus-visible:ring-primary-400/50 "><x-css-screen-shot /></button>
                <button type="button" @click="zoomIn"
                    class="bg-primary-600 rounded-lg px-3 py-2 w-10 h-10 font-semibold items-center transition duration-75 justify-center outline-none focus-visible:ring-2 inline-grid shadow-sm text-white hover:bg-primary-500 focus-visible:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus-visible:ring-primary-400/50"><x-css-zoom-in /></button>
                <button type="button" @click="zoomOut"
                    class="bg-primary-600 rounded-lg px-3 py-2 w-10 h-10 font-semibold items-center transition duration-75 justify-center outline-none focus-visible:ring-2 inline-grid shadow-sm text-white hover:bg-primary-500 focus-visible:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus-visible:ring-primary-400/50"><x-css-zoom-out /></button>
                <button type="button" @click="reset"
                    class="bg-primary-600 rounded-lg px-3 py-2 w-10 h-10 font-semibold items-center transition duration-75 justify-center outline-none focus-visible:ring-2 inline-grid shadow-sm text-white hover:bg-primary-500 focus-visible:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus-visible:ring-primary-400/50 "><x-css-repeat class="w-14"/></button>
            </div>
        </div>
        <div id="table-container">
            {{ $this->table }}
        </div>
        {{-- <div class="relative w-full h-full rounded dark:border-gray-700 mb-4 grid grid-cols-1"
            wire:ignore>
            <div id="bpmn-container" class="w-full h-screen object-cover"></div>
            <div id="table-container">
                {{ $this->table }}
            </div>
        </div> --}}
    </div>
</div>
