<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\TourneeApprove;
use App\Models\Tournee;
use App\Models\User;
use App\Notifications\MemoireTourneeAccountantNotification;
use App\Notifications\MemoireTourneeApproveNotification;
use App\Notifications\MemoireTourneeLevelNotification;
use App\Notifications\TourneeApproveNotification;
use App\Notifications\TourneeLevelNotification;
use Illuminate\Http\Request;

class TourneeApproveController extends Controller
{
    public function approve(Request $request, Tournee $tournee)
    {
        $action = $request->input('action');
        $newStatus = '';
        switch ($action) {
            case 'review':
                $newStatus = 'draft';
                break;
            case 'reject':
                $newStatus = 'rejected';
                break;
            case 'approve':
                switch ($tournee->status) {
                    case 'sup_approve':
                        $newStatus = 'sg_approve';
                        break;
                    case 'sg_approve':
                        $newStatus = 'approved';
                        break;
                }
                break;
        }
        $tourneeApprove = TourneeApprove::create([
            'tournee_id' => $tournee->id,
            'approval_id' => auth()->user()->employee->id,
            'approval_role' => implode(',',auth()->user()->employee->getRoles()) ,
            'comment' => $request->input('comment'),
            'status' => $newStatus,
        ]);
        $tournee->status = $newStatus;
        $tournee->save();

        $notification = new TourneeApproveNotification(
            $tournee,
            $tourneeApprove,
        );
        $tournee->employee->user->notify($notification);

        $notification = new TourneeLevelNotification($tournee);
        switch ($tournee->status) {
            case 'sup_approve':
                $tournee->employee->department->manager->user->notify($notification);
                break;
            case 'sg_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->whereJsonContains('roles', 'sg');
                })->get();
                foreach ($users as $user) {
                    $user->notify($notification);
                }
                break;
        }
        return redirect()->route('tournees.index');
    }
    public function m_approve(Request $request, Tournee $tournee)
    {
        $action = $request->input('action');
        $newStatus = '';
        switch ($action) {
            case 'review':
                $newStatus = 'draft';
                break;
            case 'reject':
                $newStatus = 'rejected';
                break;
            case 'approve':
                switch ($tournee->memor_status) {
                    case 'controller_approve':
                        $newStatus = 'sg_approve';
                        break;
                    case 'sg_approve':
                        $this->notifyAccountant($request->accountant, $tournee);
                        $newStatus = 'approved';
                        break;
                }
                break;
        }
        $tourneeApprove = TourneeApprove::create([
            'tournee_id' => $tournee->id,
            'approval_id' => auth()->user()->employee->id,
            'approval_role' => implode(',',auth()->user()->employee->getRoles()) ,
            'comment' => $request->input('comment'),
            'memor_status' => $newStatus,
        ]);

        $tournee->memor_status = $newStatus;
        $tournee->save();

        $notification = new MemoireTourneeApproveNotification(
            $tournee,
            $tourneeApprove,
        );
        $tournee->employee->user->notify($notification);

        $notification = new MemoireTourneeLevelNotification($tournee);
        if ($tournee->memor_status == 'sg_approve') {
            $notification = new MemoireTourneeLevelNotification($tournee);
            $users = User::whereHas('employee', function ($query) {
                $query->whereJsonContains('roles', 'sg');
            })->get();
            foreach ($users as $user) {
               if($user->employee->id != $tournee->employee_id) {
                    $user->notify($notification);
                }
            }
        }
        return redirect()->route('tournees.m_index');
    }


    private function notifyAccountant(int $accountant_id, Tournee $tournee) {
        $tournee->update([
            'accountant_id' => $accountant_id,
        ]);
        $accountant = Employee::find($accountant_id);
        $notification = new MemoireTourneeAccountantNotification($tournee);
        $accountant->user->notify($notification);
    }
}
