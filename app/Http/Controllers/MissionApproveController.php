<?php

namespace App\Http\Controllers;

use App\Models\MissionApprove;
use App\Models\MissionOrder;
use App\Models\user;
use App\Notifications\MemoireMissionOrderApproveNotification;
use App\Notifications\MemoireMissionOrderLevelNotification;
use App\Notifications\MissionOrderApproveNotification;
use App\Notifications\MissionOrderLevelNotification;
use Illuminate\Http\Request;

class MissionApproveController extends Controller
{
    public function approve(Request $request, MissionOrder $missionOrder)
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
                switch ($missionOrder->status) {
                    case 'sup_approve':
                        $newStatus = 'hr_approve';
                        break;
                    case 'hr_approve':
                        $newStatus = 'sg_approve';
                        break;
                    case 'sg_approve':
                        $newStatus = 'approved';
                        break;
                }
                break;
        }
        $missionApprove = MissionApprove::create([
            'mission_order_id' => $missionOrder->id,
            'approval_id' => auth()->user()->employee->id,
            'approval_role' => implode(',',auth()->user()->employee->roles) ,
            'comment' => $request->input('comment'),
            'status' => $newStatus,
        ]);
        $missionOrder->status = $newStatus;
        $missionOrder->save();
        $notification = new MissionOrderApproveNotification(
            $missionOrder,
            $missionApprove,
        );
        $missionOrder->employee->user->notify($notification);

        $notification = new MissionOrderLevelNotification($missionOrder);
        switch ($missionOrder->status) {
            case 'sup_approve':
                $missionOrder->employee->department->manager->user->notify($notification);
                break;
            case 'hr_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->whereJsonContains('roles', 'hr');
                })->get();
                foreach ($users as $user) {
                    $user->notify($notification);
                }
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
        return redirect()->route('mission_orders.index');
    }
    public function m_approve(Request $request, MissionOrder $missionOrder)
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
                switch ($missionOrder->memor_status) {
                    case 'sup_approve':
                        $newStatus = 'hr_approve';
                        break;
                    case 'hr_approve':
                        $newStatus = 'sg_approve';
                        break;
                    case 'sg_approve':
                        $newStatus = 'approved';
                        break;
                }
                break;
        }
        $missionApprove = MissionApprove::create([
            'mission_order_id' => $missionOrder->id,
            'approval_id' => auth()->user()->employee->id,
            'approval_role' => implode(',',auth()->user()->employee->roles),
            'comment' => $request->input('comment'),
            'memor_status' => $newStatus,
        ]);

        $missionOrder->memor_status = $newStatus;
        $missionOrder->save();

        $notification = new MemoireMissionOrderApproveNotification(
            $missionOrder,
            $missionApprove,
        );
        $missionOrder->employee->user->notify($notification);

        $notification = new MemoireMissionOrderLevelNotification($missionOrder);
        switch ($missionOrder->memor_status) {
            case 'sup_approve':
                $missionOrder->employee->department->manager->user->notify($notification);
                break;
            case 'hr_approve':
                $users = User::whereHas('employee', function ($query) {
                    $query->whereJsonContains('roles', 'hr');
                })->get();
                foreach ($users as $user) {
                    $user->notify($notification);
                }
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

        return redirect()->route('mission_orders.m_index');
    }
}
