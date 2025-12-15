<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    /**
     * Handle the model "creating" event.
     */
    public function creating(Model $model): void
    {
        if (Auth::check() && $model->hasAttribute('created_by')) {
            $model->created_by = Auth::id();
        }
    }

    /**
     * Handle the model "updating" event.
     */
    public function updating(Model $model): void
    {
        if (Auth::check() && $model->hasAttribute('updated_by')) {
            $model->updated_by = Auth::id();
        }
    }
}
