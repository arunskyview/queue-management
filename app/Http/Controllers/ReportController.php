<?php

namespace App\Http\Controllers;

use App\Models\CallStatus;
use App\Models\Counter;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Models\AllotCounter;
use App\Repositories\ReportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected $reportRepository;
    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }
    public function showUserReport(Request $request)
    {
        $report = null;
        $selected_user_id = null;
        $selected_date = null;
        if ($request->date) {
            $selected_user_id = $request->user_id;
            $selected_date = $request->date;
            $report = $this->reportRepository->getUserReport($request->user_id, $request->date);
        }
        $user_id=Auth::user()->id;
        if($user_id ==1)
        {
            $users=User::get();
        }
        else{
            $users=User::where('id',$user_id)->get();
        }
        return view('reports.user_report', ['users' => $users, 'reports' => $report, 'selected_user_id' => $selected_user_id, 'selected_date' => $selected_date]);
    }

    public function showQueueListReport(Request $request)
    {
        $report = null;
        $selected_starting_date = null;
        $selected_ending_date = null;
        if ($request->starting_date && $request->ending_date) {
            $selected_starting_date = $request->starting_date;
            $selected_ending_date = $request->ending_date;
            $report = $this->reportRepository->getQueueListReport($request->starting_date, $request->ending_date);
        }
        return view('reports.queue_list_report', ['reports' => $report, 'selected_starting_date' => $selected_starting_date, 'selected_ending_date' => $selected_ending_date,'timezone' => Setting::first()->timezone]);
    }

    public function showSatiticalReport()
    {
        $users = User::get();
        $services = Service::get();
        $counters = Counter::get();
        return view('reports.statitical_report', ['users' => $users, 'services' => $services, 'counters' => $counters]);
    }

    public function showMonthlyReport(Request $request)
    {
        $statuses = CallStatus::get();
        $reports = null;
        $starting_date = null;
        $ending_date = null;
        $service = null;
        $counter = null;
        $user = null;
        $status = null;
        $count = null;

        $user_id=Auth::user()->id;
        if($user_id !=1)
        {
            $alloted=AllotCounter::where('user_id',$user_id)->first();
            $users = User::where('id',$user_id)->get();
            $services = Service::where('id',$alloted->service_id)->get();
            $counters = Counter::where('id',$alloted->counter_id)->get();
            $request['service_id']= $alloted->service_id;
            $request['counter_id']= $alloted->counter_id;
            $request['user_id']= $user_id;
            $service = $alloted->service_id;
            $counter = $alloted->counter_id;
            $user = $user_id;
        }
        else{
            $users = User::get();
            $services = Service::get();
            $counters = Counter::get();
        }

        if ($request->starting_date && $request->ending_date) {
            $starting_date = $request->starting_date;
            $ending_date = $request->ending_date;
            if (isset($request->service_id)) $service = $request->service_id;
            if (isset($request->counter_id)) $counter = $request->counter_id;
            if (isset($request->user_id)) $user = $request->user_id;
            if (isset($request->call_status)) $status = $request->call_status;

            $reports = $this->reportRepository->getMonthlyReport($request);
            $count = $this->reportRepository->getTokenCounts($request->starting_date, $request->ending_date,$user,$service);
        }


        return view('reports.monthly_report', ['token_count' => $count, 'users' => $users, 'services' => $services, 'counters' => $counters, 'statuses' => $statuses, 'reports' => $reports, 'timezone' => Setting::first()->timezone, 'selected' => ['starting_date' => $starting_date, 'ending_date' => $ending_date, 'counter' => $counter, 'service' => $service, 'user' => $user, 'status' => $status]]);
    }
}
