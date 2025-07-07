<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Quotation/Tender Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">

                        <form class="max-w-sm mx-auto">
                            <h2 class="text-xl font-bold">G levels:</h2>
                            <div class="flex items-center mb-4">
                                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">G1</label>
                            </div>
                            <div class="flex items-center mb-4">
                                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">G2</label>
                            </div>
                            <div class="flex items-center mb-4">
                                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">G3</label>
                            </div>
                            <div class="flex items-center mb-4">
                                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">G4</label>
                            </div>
                            <div class="flex items-center mb-4">
                                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">G5</label>
                            </div>
                            <div class="flex items-center mb-4">
                                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">G6</label>
                            </div>
                            <div class="flex items-center mb-4">
                                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">G7</label>
                            </div>
                            <h2 class="text-xl font-bold">Specialization:</h2>
                            <h2 class="text-lg font-bold">Buildings(B):</h2>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B01: Prefab concrete system </label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B02: Steel frame system</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B03: Restoration andconversation </label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B04: Building general works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B05: Piling works </label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B06: Concrete repair work </label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B07: Interior decoration </label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B09: Landscaping </label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B10: Internal Plumbing Installation</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B11: Signage Installation</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B12: Aluminium/Steel and Glass Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B13: Tile Installation and Plastering Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B14: Paint Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B15: Roof Installation and Metal Cladding</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B16: Swimming Pool Installation Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B17: Pre-Stressing and Post-Tensioning Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B18: Metal Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B19: Formwork System</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B20: Indoor Gas Pipeline Installation</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B21: Scaffolding Installation</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B22: Block System</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B23: Wood Frame System</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B24: Building Maintenance Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B25: Private Pipe Connection Sewerage</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B26: Demolition Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B27: Water Supply & Sewerage Maintenance</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">B28: Miscellaneous Works</label>
                            </div>
                            <h2 class="text-lg font-bold">Civil Engineering(CE):</h2>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE01: Road & Pavement Construction </label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE02: Bridge Construction</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE03: Marine Structures</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE04: Dams</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE05: Piling works </label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE06: Flood Control System</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE07: Railway Tracks </label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE08: Slope Protection System</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE09: Oil or Gas Pipelines</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE10: Piling Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE11: Concrete Repair Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE12: Soil Investigation</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE13: Signage Installation</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE14: Landscaping</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE15: Offshore Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE16: Underwater Construction and Maintenance</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE17: Airports</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE18: Reclamation Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE19: Sewerage System</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE20: Water Supply System</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE21: General Civil Engineering Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE22: Synthetic Game Field Tracks</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE23: Pre-Stressing and Post-Tensioning Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE24: Civil Engineering Structures</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE25: Rock Blasting Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE26: Sculptured Structures</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE27: Heat Insulation/Refractory Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE28: Special Cast System</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE29: Scaffolding Installation</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE30: Soil Stabilisation, Subterranean Drainage</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE31: Telecommunication Civil Engineering Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE32: Civil Engineering Maintenance Works</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE33: Drilling for Underground Water</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE34: Pre-Cast Concrete Installation Work</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE35: Concrete Test</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE36: Earthworks</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE37: Power Station Funnel Work</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE38: Sewerage System Maintenance</label>
                            </div>
                            <div class="flex items-center">
                                <input checked id="checked-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="checked-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">CE39: Water Supply System Maintenance</label>
                            </div>
                            <h2 class="text-lg font-bold mb-4">Mechanical Engineering (M):</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <input id="m01" type="checkbox" value="M01" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m01" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M01: Air-Conditioning System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m02" type="checkbox" value="M02" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m02" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M02: Fire Prevention and Protection System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m03" type="checkbox" value="M03" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m03" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M03: Lifts and Escalators</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m04" type="checkbox" value="M04" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m04" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M04: Building Automation System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m05" type="checkbox" value="M05" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m05" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M05: System for Workshop, Plant, etc.</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m06" type="checkbox" value="M06" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m06" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M06: Medical Equipment</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m07" type="checkbox" value="M07" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m07" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M07: Kitchen Appliances</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m08" type="checkbox" value="M08" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m08" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M08: Heat Restoration System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m09" type="checkbox" value="M09" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m09" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M09: Mechanical-Based Compression and Generation</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m10" type="checkbox" value="M10" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m10" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M10: Coolant for Power Generation</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m11" type="checkbox" value="M11" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m11" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M11: Construction and Special Treatment</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m12" type="checkbox" value="M12" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m12" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M12: Special Plant</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m13" type="checkbox" value="M13" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m13" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M13: Drill Maintenance</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m14" type="checkbox" value="M14" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m14" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M14: Pollution Control System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m15" type="checkbox" value="M15" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m15" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M15: Miscellaneous Mechanical</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m16" type="checkbox" value="M16" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m16" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M16: Tower Crane</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m17" type="checkbox" value="M17" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m17" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M17: Laundry Equipment</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m18" type="checkbox" value="M18" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m18" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M18: Hot Water System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m19" type="checkbox" value="M19" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m19" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M19: Plant Equipment Installation</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="m20" type="checkbox" value="M20" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="m20" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">M20: General Mechanical Maintenance</label>
                                </div>
                            </div>
                            <h2 class="text-lg font-bold mb-4">Electrical Engineering (E):</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <input id="e01" type="checkbox" value="E01" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e01" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E01: Sound System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e02" type="checkbox" value="E02" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e02" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E02: Monitoring and Security System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e03" type="checkbox" value="E03" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e03" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E03: Building Automation System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e04" type="checkbox" value="E04" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e04" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E04: Low Voltage Installation</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e05" type="checkbox" value="E05" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e05" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E05: High Voltage Installation</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e06" type="checkbox" value="E06" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e06" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E06: Special Lighting System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e07" type="checkbox" value="E07" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e07" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E07: Internal Telecommunications</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e08" type="checkbox" value="E08" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e08" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E08: External Telecommunications</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e09" type="checkbox" value="E09" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e09" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E09: Various Special Equipment</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e10" type="checkbox" value="E10" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e10" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E10: Special Control Panel</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e11" type="checkbox" value="E11" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e11" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E11: General Electrical Works</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e12" type="checkbox" value="E12" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e12" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E12: Electric Signboards</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e13" type="checkbox" value="E13" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e13" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E13: Train Telecommunications System</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="e14" type="checkbox" value="E14" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="e14" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">E14: Computer Network Cable</label>
                                </div>
                            </div>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
