<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\QutoationSetting;
use App\Models\GredLevel;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] 
class TenderSettings extends Component
{
    // Greds and specilizations available data
    public $gredLevelsAll = ['G1', 'G2', 'G3', 'G4', 'G5', 'G6', 'G7']; 
    public $specializationsBuildingsAll = [
        'B01' => 'Prefab concrete system',
        'B02' => 'Steel frame system',
        'B03' => 'Restoration and conversation',
        'B04' => 'Building general works',
        'B05' => 'Piling works',
        'B06' => 'Concrete repair work',
        'B07' => 'Interior decoration',
        'B08' => 'Building general works',
        'B09' => 'Landscaping',
        'B10' => 'Internal Plumbing Installation',
        'B11' => 'Signage Installation',
        'B12' => 'Aluminium/Steel and Glass Works',
        'B13' => 'Tile Installation and Plastering Works',
        'B14' => 'Paint Works',
        'B15' => 'Roof Installation and Metal Cladding',
        'B16' => 'Swimming Pool Installation Works',
        'B17' => 'Pre-Stressing and Post-Tensioning Works',
        'B18' => 'Metal Works',
        'B19' => 'Formwork System',
        'B20' => 'Indoor Gas Pipeline Installation',
        'B21' => 'Scaffolding Installation',
        'B22' => 'Block System',
        'B23' => 'Wood Frame System',
        'B24' => 'Building Maintenance Works',
        'B25' => 'Private Pipe Connection Sewerage',
        'B26' => 'Demolition Works',
        'B27' => 'Water Supply & Sewerage Maintenance',
        'B28' => 'Miscellaneous Works'
    ];
    public $specializationsCivilEngineeringAll = [
        'CE01' => 'Road & Pavement Construction',
        'CE02' => 'Bridge Construction',
        'CE03' => 'Marine Structures',
        'CE04' => 'Dams',
        'CE05' => 'Piling works',
        'CE06' => 'Flood Control System',
        'CE07' => 'Railway Tracks',
        'CE08' => 'Slope Protection System',
        'CE09' => 'Oil or Gas Pipelines',
        'CE10' => 'Piling Works',
        'CE11' => 'Concrete Repair Works',
        'CE12' => 'Soil Investigation',
        'CE13' => 'Signage Installation',
        'CE14' => 'Landscaping',
        'CE15' => 'Offshore Works',
        'CE16' => 'Underwater Construction and Maintenance',
        'CE17' => 'Airports',
        'CE18' => 'Reclamation Works',
        'CE19' => 'Sewerage System',
        'CE20' => 'Water Supply System',
        'CE21' => 'General Civil Engineering Works',
        'CE22' => 'Synthetic Game Field Tracks',
        'CE23' => 'Pre-Stressing and Post-Tensioning Works',
        'CE24' => 'Civil Engineering Structures',
        'CE25' => 'Rock Blasting Works',
        'CE26' => 'Sculptured Structures',
        'CE27' => 'Heat Insulation/Refractory Works',
        'CE28' => 'Special Cast System',
        'CE29' => 'Scaffolding Installation',
        'CE30' => 'Soil Stabilisation, Subterranean Drainage',
        'CE31' => 'Telecommunication Civil Engineering Works',
        'CE32' => 'Civil Engineering Maintenance Works',
        'CE33' => 'Drilling for Underground Water',
        'CE34' => 'Pre-Cast Concrete Installation Work',
        'CE35' => 'Concrete Test',
        'CE36' => 'Earthworks',
        'CE37' => 'Power Station Funnel Work',
        'CE38' => 'Sewerage System Maintenance',
        'CE39' => 'Water Supply System Maintenance'
    ];
    public $specializationsMechanicalAll = [
        'M01' => 'Air-Conditioning System',
        'M02' => 'Fire Prevention and Protection System',
        'M03' => 'Lifts and Escalators',
        'M04' => 'Building Automation System',
        'M05' => 'System for Workshop, Plant, etc.',
        'M06' => 'Medical Equipment',
        'M07' => 'Kitchen Appliances',
        'M08' => 'Heat Restoration System',
        'M09' => 'Mechanical-Based Compression and Generation',
        'M10' => 'Coolant for Power Generation',
        'M11' => 'Construction and Special Treatment',
        'M12' => 'Special Plant',
        'M13' => 'Drill Maintenance',
        'M14' => 'Pollution Control System',
        'M15' => 'Miscellaneous Mechanical',
        'M16' => 'Tower Crane',
        'M17' => 'Laundry Equipment',
        'M18' => 'Hot Water System',
        'M19' => 'Plant Equipment Installation',
        'M20' => 'General Mechanical Maintenance'
    ];
    public $specializationsElectricalAll = [
        'E01' => 'Sound System',
        'E02' => 'Monitoring and Security System',
        'E03' => 'Building Automation System',
        'E04' => 'Low Voltage Installation',
        'E05' => 'High Voltage Installation',
        'E06' => 'Special Lighting System',
        'E07' => 'Internal Telecommunications',
        'E08' => 'External Telecommunications',
        'E09' => 'Various Special Equipment',
        'E10' => 'Special Control Panel',
        'E11' => 'General Electrical Works',
        'E12' => 'Electric Signboards',
        'E13' => 'Train Telecommunications System',
        'E14' => 'Computer Network Cable'
    ];
    
    // QuotationSettings data
    public $selectedGLevels = [];
    public $selectedSpecializations = [];
    
    public function mount(){
        // Extract currently logged in user data from models
        $this->selectedGLevels = auth()->user()->gredLevels->pluck('g_level')->toArray();
        $this->selectedSpecializations = auth()->user()->specializations->pluck('specialization')->toArray();
    }
    
    public function render()
    {
        return view('livewire.tender-settings');
    }

    public function saveSettings()
    {
        // 
        // Update G Levels
        // 
        // Get the authenticated user
        $user = auth()->user();
        
        // Delete existing gred levels
        $user->gredLevels()->delete();
        
        // Create new records for selected G levels
        foreach ($this->selectedGLevels as $gLevel) {
            $user->gredLevels()->create([
                'g_level' => $gLevel
            ]);
        }
        
        // 
        // Update Specialization
        // 
        // Delete existing specializations
        $user->specializations()->delete();
        
        // Create new records for selected specializations
        // dd($this->selectedSpecializations);
        foreach ($this->selectedSpecializations as $specialization) {
            $user->specializations()->create([
                'specialization' => $specialization
            ]);
        }
        
        // Show success message
        session()->flash('message', 'Settings saved successfully!');
        
        // Optional: Refresh the component to show updated data
        $this->mount();
    }    

    public function addGredLevelInput()
    {
        $this->g_levels_inputs[] = ['g_level' => ''];
    }
    
    public function removeGredLevelInput($index)
    {
        unset($this->g_levels_inputs[$index]);
        $this->g_levels_inputs = array_values($this->g_levels_inputs); // Re-index array
    }
}