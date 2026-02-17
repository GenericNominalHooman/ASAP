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

                    <form class="max-w-sm mx-auto" wire:submit.prevent="saveSettings">
                        @csrf
                        <h2 class="text-xl font-bold mb-2">Company SSM Number: </h2>
                        <div class="mb-4">
                            <input 
                                type="text" 
                                wire:model="ssmNumber"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                placeholder="e.g. IP0302888-W"
                            >
                        </div>
                        
                        <!-- Display current user's CIDB gred levels -->
                        <h2 class="text-xl font-bold">Gred:</h2>
                        @foreach($gredLevelsAll as $level)
                            <div class="flex items-center mb-4">
                                <input 
                                    type="checkbox" 
                                    id="g_level_{{ $level }}"
                                    wire:model="selectedGLevels"
                                    value="{{ $level }}"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                >
                                <label for="g_level_{{ $level }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    {{ $level }}
                                </label>
                            </div>
                        @endforeach
                        
                        <h2 class="text-xl font-bold">Specialization:</h2>
                            <h2 class="text-lg font-bold">Buildings(B):</h2>
                                @foreach($specializationsBuildingsAll as $specializationBuildingCode => $specializationBuildingDesc)
                                    <div class="flex items-center mb-4">
                                        <input 
                                            type="checkbox" 
                                            id="g_level_{{ $specializationBuildingCode }}"
                                            wire:model="selectedSpecializations"
                                            value="{{ $specializationBuildingCode }}"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        >
                                        <label for="g_level_{{ $specializationBuildingCode }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ $specializationBuildingCode . ": " . $specializationBuildingDesc }}
                                        </label>
                                    </div>
                                @endforeach
                            <h2 class="text-lg font-bold">Civil Engineering(CE):</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($specializationsCivilEngineeringAll as $specializationCivilEngineeringCode => $specializationCivilEngineeringDesc)
                                        <div class="flex items-center mb-4">
                                            <input 
                                                type="checkbox" 
                                                id="g_level_{{ $specializationCivilEngineeringCode }}"
                                                wire:model="selectedSpecializations"
                                                value="{{ $specializationCivilEngineeringCode }}"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            >
                                            <label for="g_level_{{ $specializationCivilEngineeringCode }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                {{ $specializationCivilEngineeringCode . ": " . $specializationCivilEngineeringDesc }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            <h2 class="text-lg font-bold mb-4">Mechanical Engineering (M):</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($specializationsMechanicalAll as $specializationMechanicalCode => $specializationMechanicalDesc)
                                        <div class="flex items-center mb-4">
                                            <input 
                                                type="checkbox" 
                                                id="g_level_{{ $specializationMechanicalCode }}"
                                                wire:model="selectedSpecializations"
                                                value="{{ $specializationMechanicalCode }}"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            >
                                            <label for="g_level_{{ $specializationMechanicalCode }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                {{ $specializationMechanicalCode . ": " . $specializationMechanicalDesc }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            <h2 class="text-lg font-bold mb-4">Electrical Engineering (E):</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($specializationsElectricalAll as $specializationElectricalCode => $specializationElectricalDesc)
                                        <div class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                id="g_level_{{ $specializationElectricalCode }}"
                                                wire:model="selectedSpecializations"
                                                value="{{ $specializationElectricalCode }}"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            >
                                            <label for="g_level_{{ $specializationElectricalCode }}" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                {{ $specializationElectricalCode . ": " . $specializationElectricalDesc }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Save</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
