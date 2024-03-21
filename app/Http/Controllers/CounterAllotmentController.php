<?php

namespace App\Http\Controllers;

use App\Models\AllotCounter;
use App\Models\Counter;
use App\Models\Service;
use App\Models\User;
use Hamcrest\Core\AllOf;
use Illuminate\Http\Request;

class CounterAllotmentController extends Controller
{
    //
    public function index()
    {
        $allot_counter=AllotCounter::all();
        return view('allot-counter.index',compact('allot_counter'));
    }
    public function create()
    {
        $counters=Counter::all();
        $users=User::where('id','!=',1)->get();
        $services=Service::all();
        return view('allot-counter.create',compact('counters','users','services'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_name' =>'required',
            'counter' =>'required',
            'service' =>'required',
        ]);
        try{
            $checkUser=AllotCounter::where('user_id',$request->user_name)->first();
            if($checkUser)
            {
                $request->session()->flash('error', 'This user have already a counter.');
                return redirect()->route('allotment.create');
            }
            $allotCounter=new AllotCounter();
            $allotCounter->user_id=$request->user_name;
            $allotCounter->counter_id=$request->counter;
            $allotCounter->service_id=$request->service;
            $allotCounter->save();
            if($allotCounter)
            {
                $request->session()->flash('success', 'Succesfully inserted the record');
                return redirect()->route('allotment.index');
            }
            else{
                $request->session()->flash('error', 'Failed to insert the record');
                return redirect()->route('allotment.create');
            }
        }catch(\Exception $e)
        {
            // return redirect()->back()->with('error',$e->getMessage());
            $request->session()->flash('error', 'Something went wrong!');
            return redirect()->route('allotment.create');
        }
    }
    public function edit($id)
    {
        $counters=Counter::all();
        $users=User::where('id','!=',1)->get();
        $services=Service::all();
        $allot_counter=AllotCounter::find($id);
        return view('allot-counter.edit',compact('allot_counter','counters','users','services'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'user_name' =>'required',
            'counter' =>'required',
            'service' =>'required',
        ]);
        try{
            $checkUser=AllotCounter::where('id','!=',$request->allot_id)->where('user_id',$request->user_name)->first();
            if($checkUser)
            {
                $request->session()->flash('error', 'This user have already a counter.');
                return redirect()->route('allotment.index');
            }
            $allotCounter=AllotCounter::where('id',$request->allot_id)->first();
            $allotCounter->user_id=$request->user_name;
            $allotCounter->counter_id=$request->counter;
            $allotCounter->service_id=$request->service;
            $allotCounter->save();
            if($allotCounter)
            {
                $request->session()->flash('success', 'Succesfully updated the record');
                return redirect()->route('allotment.index');
            }
            else{
                $request->session()->flash('error', 'Failed to update the record');
                return redirect()->route('allotment.index');
            }
        }catch(\Exception $e)
        {
            return redirect()->back()->with('error',$e->getMessage());
            // $request->session()->flash('error', 'Something went wrong!');
            // return redirect()->route('allotment.index');
        }
    }
    
    public function destroy($id)
    {
        try {
            $allot_counter = AllotCounter::find($id);
            $allot_counter->delete();
        } catch (\Exception $e) {
            return redirect()->route('allotment.index')->with('error','Something Went Wrong');
        }
        return redirect()->route('allotment.index')->with('success','Succesfully deleted the record');
    }

}
