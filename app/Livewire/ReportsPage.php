<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChangeRequest;
use Livewire\Attributes\On;

class ReportsPage extends Component
{
    public string $filterStatus = 'all';   // all | pendiente | completado
    public string $filterDate   = '';      // YYYY-MM-DD or empty

    // Stats computed on render
    public int $totalRequests  = 0;
    public int $totalPending   = 0;
    public int $totalCompleted = 0;
    public int $totalBoxes     = 0;

    #[On('echo:change-requests,ChangeRequestCreated')]
    public function refresh(): void { /* re-renders automatically */ }

    public function render()
    {
        $query = ChangeRequest::with('items')->orderBy('created_at', 'desc');

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterDate !== '') {
            $query->whereDate('created_at', $this->filterDate);
        }

        $requests = $query->paginate(30);

        // Stats (always over the full table, not filtered)
        $this->totalRequests  = ChangeRequest::count();
        $this->totalPending   = ChangeRequest::where('status', 'pendiente')->count();
        $this->totalCompleted = ChangeRequest::where('status', 'completado')->count();
        $this->totalBoxes     = \App\Models\ChangeRequestItem::count();

        return view('livewire.reports-page', compact('requests'));
    }
}
