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
        return view('chancelleryRates.index', compact('chancelleryRates',));
    }
    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0.0001',
        ]);

        $chancelleryRate = ChancelleryRate::create([
            'rate' => $request->rate,
            'month_year' => now()->format('Y-m-01'), // Always first day of month
            'status' => 'draft',
        ]);
        $notification = new ChancelleryRateNotification($chancelleryRate);
        $users = User::whereHas('employee', function ($query) {
            $query->whereJsonContains('roles', 'director');
        })->get();
        foreach ($users as $user) {
            $user->notify($notification);
        }
        return redirect()->route('chancelleryRates.index')
            ->with('success', 'Currency rate updated for this month.');
    }
    public function update(Request $request, ChancelleryRate $chancelleryRate)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0.0001',
        ]);

        $chancelleryRate->update([
            'rate' => $request->rate,
            'month_year' => now()->format('Y-m-01'), // Always first day of month
            'status' => 'draft',
        ]);
        $notification = new ChancelleryRateNotification($chancelleryRate);
        $users = User::whereHas('employee', function ($query) {
            $query->whereJsonContains('roles', 'director');
        })->get();
        foreach ($users as $user) {
            $user->notify($notification);
        }
        return redirect()->route('chancelleryRates.index')
            ->with('success', 'Currency rate updated for this month.');
    }
    public function destroy(ChancelleryRate $chancelleryRate)
    {
        $chancelleryRate->delete();
        return redirect()->route('chancelleryRates.index')
                           ->with('success', 'ChancelleryRate deleted successfully!');
    }
    public function approveChancelleryRate(Request $request, ChancelleryRate $chancelleryRate) {
        $chancelleryRate->update(['status'=>'approved']);
        $notification = new ChancelleryRateApproveNotification($chancelleryRate);
        foreach (User::has('employee')->with('employee')->get() as $user) {
            $user->notify($notification);
        }
        return redirect()->route('chancelleryRates.index')->with('success', 'Currency rate approved for this month.');
    }
}
