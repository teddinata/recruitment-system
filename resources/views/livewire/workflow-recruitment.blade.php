<div>
    <div class="wrapper w-full mx-auto">
        <div class="flex flex-col gap-y-4" x-data="diagram">
            <div class="h-96 border border-gray-400 rounded dark:border-gray-700 mb-4">
                <div id="bpmn-container" style="width: 100%; height: 100%;"></div>
            </div>
        </div>
        <div id="table-container">
            {{ $this->table }}
        </div>
    </div>
</div>
