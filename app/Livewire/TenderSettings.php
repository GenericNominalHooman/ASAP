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
    public $QuotationSettings;
    public $setting_ids = [];
    public $g_level = [];
    public $specialization = [];
    public $specialization2 = [];
    
    public function mount(){
        // Extract QuotationSettings data
        $this->QuotationSettings = auth()->user()->quotationSettings;

        // Message QuotationSettings data
        $this->QuotationSettings = $this->QuotationSettings->keyBy('id');
        // dd($this->QuotationSettings);

        $this->g_level = auth()->user()->gredLevels;
        // dd($this->g_level);
        
        $this->specialization = auth()->user()->specializations;
        // dd($this->specialization);

        // $this->specialization = $this->QuotationSettings->pluck('specialization')->toArray();
        $this->setting_ids = $this->QuotationSettings->pluck('id')->toArray();
    }
    
    public function render()
    {
        // dd($this->QuotationSettings);
        return view('livewire.tender-settings', [
            'QuotationSettings' => $this->QuotationSettings
        ]);
    }

    public function saveSettings(){
        // dd($this->g_level, $this->specialization);
        foreach($this->g_level as $g_val){
            foreach($this->specialization as $specialization_val){
                auth()->user()->quotationSettings()->updateOrCreate([
                    'g_level' => $g_val,
                    'specialization' => $specialization_val,
                ]);
            }
        }
        $this->dispatch('alert', type: 'success', message: 'Settings saved successfully');
    }
}
