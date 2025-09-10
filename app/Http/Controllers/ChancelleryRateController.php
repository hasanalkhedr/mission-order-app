<?php

namespace App\Http\Controllers;

use App\Models\ChancelleryRate;
use App\Models\User;
use App\Notifications\ChancelleryRateApproveNotification;
use App\Notifications\ChancelleryRateNotification;
use Illuminate\Http\Request;

class ChancelleryRateController extends Controller
{
    public function index()
    {
        $chancelleryRates = ChancelleryRate::orderBy('id', 'desc')->paginate(20);
        return view('chancelleryRates.index', compact('chancelleryRates', ));
    }
    public function store(Request $request)
    {
        $request->validate([
            'eur_rate' => 'required|numeric|min:0',
            'usd_rate' => 'required|numeric|min:0',
            'month_year' => 'required|date'
        ]);

        $rate = ChancelleryRate::create([
            'eur_rate' => $request->eur_rate,
            'usd_rate' => $request->usd_rate,
            'month_year' => $request->month_year,
            'status' => 'draft',
        ]);

        if(auth()->user()->employee->hasRole('sg')) {
            $this->approveChancelleryRate($request, $rate);
        }
        return redirect()->route('chancelleryRates.index')
            ->with('success', 'Conversion rates added successfully.');
    }

    public function update(Request $request, ChancelleryRate $chancelleryRate)
    {
        $request->validate([
            'eur_rate' => 'required|numeric|min:0',
            'usd_rate' => 'required|numeric|min:0'
        ]);

        $chancelleryRate->update([
            'eur_rate' => $request->eur_rate,
            'usd_rate' => $request->usd_rate,
        ]);
        if(auth()->user()->employee->hasRole('sg')) {
            $this->approveChancelleryRate($request, $chancelleryRate);
        }
        return redirect()->route('chancelleryRates.index')
            ->with('success', 'Conversion rates updated successfully.');
    }

    public function destroy(ChancelleryRate $chancelleryRate)
    {
        $chancelleryRate->delete();
        return redirect()->route('chancelleryRates.index')
            ->with('success', 'ChancelleryRate deleted successfully!');
    }
    public function approveChancelleryRate(Request $request, ChancelleryRate $chancelleryRate)
    {
        $chancelleryRate->update(['status' => 'approved']);
        $notification = new ChancelleryRateApproveNotification($chancelleryRate);
        foreach (User::has('employee')->with('employee')->get() as $user) {
            $user->notify($notification);
        }
        return redirect()->route('chancelleryRates.index')->with('success', 'Currency rate approved for this month.');
    }
}
