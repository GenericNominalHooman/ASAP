<?php

namespace App\Livewire;

use Livewire\Component;

class FlashMessage extends Component
{
    public $message = null;
    public $type = 'success';

    public function mount()
    {
        if (session()->has('message')) {
            $this->message = session('message');
            $this->type = 'success';
        } elseif (session()->has('error')) {
            $this->message = session('error');
            $this->type = 'error';
        }
    }

    #[\Livewire\Attributes\On('flash-message')]
    public function handleFlashMessage($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function clearMessage()
    {
        $this->message = null;
    }

    public function render()
    {
        return view('livewire.flash-message');
    }
}
