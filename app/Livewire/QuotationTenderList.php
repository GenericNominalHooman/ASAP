<?php

namespace App\Livewire;

use App\Models\quotation_application;
use App\Models\Quotation;
use Carbon\Carbon;
use App\Services\ScraperService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class QuotationTenderList extends Component
{
    use WithPagination;

    public $status = '';
    public $searchApplied = '';
    public $searchToday = '';
    public $userSSMNumber = '';
    public $userJabatan = [2, 3, 4]; // NOTE: REPLACE THIS WITH A WORKING USER'S JABATAN ID SETTINGS OPTION, AND USE ASSOCIATIVE ARRAY INSTEAD
    public $userTimers = [];

    public function mount()
    {
        $this->userSSMNumber = auth()->user()->ssm_number;
        $this->userTimers = auth()->user()->quotationApplicationTimers()->where('enabled', true)->pluck('timing')->toArray();
    }



    public function updatingSearchApplied()
    {
        $this->resetPage('pageHistory');
    }

    public function updatingSearchToday()
    {
        $this->resetPage('pageToday');
    }

    public function scrape(ScraperService $scraperService)
    {
        logger()->info('Manual scrape method called by user: ' . auth()->id());

        try {
            $scraperService->scrapeForUser(auth()->user());
            $this->status = 'Scraping completed successfully!';
            session()->flash('message', 'Scraping completed successfully!');
        } catch (\Exception $e) {
            $this->status = 'Error: ' . $e->getMessage();
            logger()->error($this->status);
            session()->flash('error', 'Scraping failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.quotation-tender-list', [
            'QuotationApplicationModelToday' => quotation_application::where('user_id', auth()->id())
                ->whereDate('created_at', now()->today())
                ->when($this->searchToday, function ($query) {
                    $query->where(function ($q) {
                        $q->where('file_name', 'like', '%' . $this->searchToday . '%')
                            ->orWhere('title', 'like', '%' . $this->searchToday . '%')
                            ->orWhere('owner', 'like', '%' . $this->searchToday . '%')
                            ->orWhere('organization', 'like', '%' . $this->searchToday . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'pageToday'),
            'QuotationApplicationModel' => quotation_application::where('user_id', auth()->id())
                ->when($this->searchApplied, function ($query) {
                    $query->where(function ($q) {
                        $q->where('file_name', 'like', '%' . $this->searchApplied . '%')
                            ->orWhere('title', 'like', '%' . $this->searchApplied . '%')
                            ->orWhere('owner', 'like', '%' . $this->searchApplied . '%')
                            ->orWhere('organization', 'like', '%' . $this->searchApplied . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'pageHistory'),
        ]);
    }
}