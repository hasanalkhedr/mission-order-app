<?php

namespace App\View\Components;

use App\Models\Bareme;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectCurrency extends Component
{
    /**
     * Create a new component instance.
     */
    public $currencies;
    public $selectedCurrency;
    public function __construct($selectedCurrency = null)
    {
        $this->currencies = collect(['EURO', 'Roupie indienne']); //Bareme::select('currency')->distinct()->pluck('currency');
        $this->selectedCurrency = $selectedCurrency;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-currency');
    }
}
