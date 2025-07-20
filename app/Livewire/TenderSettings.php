<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\QutoationSetting;
use App\Models\GredLevel;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] 
class TenderSettings extends Component
{
    // QuotationSettings data
    public $g_level = [];
    public $g_level_old = [];
    public $specialization = [];
    
    public function mount(){
        $this->g_level = auth()->user()->gredLevels;
        $this->g_level_old = $this->g_level;
        // dd($this->g_level);
        
        $this->specialization = auth()->user()->specializations;
        // dd($this->specialization);

    }
    
    public function render()
    {
        return view('livewire.tender-settings');
    }

    public function saveSettings(){
        // Validate g_level and specialization
        // $this->validate([
        //     'g_level' => 'required',
        //     'specialization' => 'required',
        // ]);
        
        // Update or create any changes to user's gred level
        for ($i = 0; $i < count($this->g_level); $i++) {
            $g_val = $this->g_level[$i];
            // dd($this->g_level, $g_val['g_level']);
            GredLevel::updateOrCreate(
                [
                    'g_level' => $this->g_level_old[$i]['g_level'],
                ],
                [
                'g_level' => $this->g_level[$i]['g_level'],
                'user_id' => auth()->user()->id,
                'updated_at' => now(),
            ]);
        }
        // Update any changes to user's specialization
        // foreach($this->specialization as $specialization_val){
        //     Specialization::updateOrCreate([
        //         'specialization' => $specialization_val,
        //         'user_id' => auth()->user()->id,
        //     ]);
        // }
        $this->dispatch('alert', type: 'success', message: 'Settings saved successfully');
    }
}
