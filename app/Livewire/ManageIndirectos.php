<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PackagingReference;

class ManageIndirectos extends Component
{
    // List state
    public $search = '';

    // Form state
    public $isFormOpen = false;
    public $editId = null;
    
    // Model fields
    public $code = '';
    public $type = '';
    public $dimensions = '';

    protected function rules()
    {
        return [
            'code'       => 'required|string|max:50|unique:packaging_references,code' . ($this->editId ? ',' . $this->editId : ''),
            'type'       => 'required|string|max:50',
            'dimensions' => 'nullable|string|max:100',
        ];
    }

    protected $messages = [
        'code.required' => 'El código es obligatorio.',
        'code.unique'   => 'Este código ya existe en el catálogo.',
        'type.required' => 'El tipo de empaque es obligatorio.',
    ];

    public function create()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isFormOpen = true;
    }

    public function edit($id)
    {
        $this->resetValidation();
        $ref = PackagingReference::findOrFail($id);
        
        $this->editId = $ref->id;
        $this->code = $ref->code;
        $this->type = $ref->type;
        $this->dimensions = $ref->dimensions;
        
        $this->isFormOpen = true;
    }

    public function save()
    {
        $this->validate();

        // Uppercase standard for consistency
        $codeUpper = strtoupper(trim($this->code));
        $typeUpper = strtoupper(trim($this->type));
        $dimClean = trim($this->dimensions);

        if ($this->editId) {
            $ref = PackagingReference::findOrFail($this->editId);
            $ref->update([
                'code'       => $codeUpper,
                'type'       => $typeUpper,
                'dimensions' => $dimClean,
            ]);
            session()->flash('message', 'Indirecto actualizado exitosamente.');
        } else {
            PackagingReference::create([
                'code'       => $codeUpper,
                'type'       => $typeUpper,
                'dimensions' => $dimClean,
            ]);
            session()->flash('message', 'Indirecto creado exitosamente.');
        }

        $this->closeForm();
    }

    public function delete($id)
    {
        $ref = PackagingReference::findOrFail($id);
        
        // Prevent deletion if used in requests (basic safety logic, optional but good practice)
        $inUse = \App\Models\ChangeRequestItem::where('indirecto_code', $ref->code)->exists();
        
        if ($inUse) {
            session()->flash('error', "No se puede eliminar '{$ref->code}' porque ya está asignado a un requerimiento.");
            return;
        }

        $ref->delete();
        session()->flash('message', 'Indirecto eliminado del catálogo.');
    }

    public function resetForm()
    {
        $this->editId = null;
        $this->code = '';
        $this->type = '';
        $this->dimensions = '';
    }

    public function closeForm()
    {
        $this->isFormOpen = false;
        $this->resetForm();
    }

    public function render()
    {
        $query = PackagingReference::query();

        if (trim($this->search) !== '') {
            $term = '%' . trim($this->search) . '%';
            $query->where('code', 'like', $term)
                  ->orWhere('type', 'like', $term)
                  ->orWhere('dimensions', 'like', $term);
        }

        // We paginate or just list all, as it's a catalog we show all ordered by type and code
        $indirectos = $query->orderBy('type')->orderBy('code')->get();

        return view('livewire.manage-indirectos', [
            'indirectos' => $indirectos
        ])->layout('layouts.app');
    }
}
