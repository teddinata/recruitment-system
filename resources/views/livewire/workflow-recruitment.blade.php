<div class="wrapper w-full mx-auto">
    <div class="flex flex-col gap-y-4" x-data="diagram">
        <div class="h-96 border border-gray-400 rounded dark:border-gray-700 mb-4 flex justify-between" wire:ignore>
            <div id="bpmn-container" class="w-[900px]"></div>
            <div class="h-full flex flex-col items-center justify-center mr-3 space-y-2">
                <button type="button" @click="reset" class="bg-primary-600 rounded-lg px-3 py-2 text-sm font-semibold items-center transition duration-75 justify-center outline-none focus-visible:ring-2 inline-grid shadow-sm text-white hover:bg-primary-500 focus-visible:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus-visible:ring-primary-400/50 ">Reset</button>
                {{-- <button type="button" @click="zoomIn" class="bg-primary-600 rounded-lg px-3 py-2 text-sm font-semibold items-center transition duration-75 justify-center outline-none focus-visible:ring-2 inline-grid shadow-sm text-white hover:bg-primary-500 focus-visible:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus-visible:ring-primary-400/50 ">+</button>
                <button type="button" @click="zoomOut" class="bg-primary-600 rounded-lg px-3 py-2 text-sm font-semibold items-center transition duration-75 justify-center outline-none focus-visible:ring-2 inline-grid shadow-sm text-white hover:bg-primary-500 focus-visible:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400 dark:focus-visible:ring-primary-400/50 ">-</button> --}}
            </div>
        </div>
    </div>
    
    <div id="table-container">
        {{ $this->table }}
    </div>
</div>