<?php

namespace App\Http\Controllers;

use App\Classes\dt;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\ImagesRecord;
use DateTime;
use File;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\PosController;
use App\Models\EmployeeAttandenceSetting;
use App\Models\EmployeeAttandence;
use App\Models\EmployeeSellarie;
use Carbon\Carbon;

use Auth;

// Hamza  j
class AttendanceController extends Controller
{
    public function BtnHandle(){

        $record   = EmployeeAttandence::where(['user_id' => Auth::id() , 'user_active' => 1])->exists();
        $Sellarie = $this->getSellary();
        return response()->json(['data' => $record , 'Sellary' => 'Total Sellery = '.$Sellarie->cash_paid_added ?? 0 .'+'.($Sellarie->current_cash_remaining - $Sellarie->cash_paid_added)]);
    }
     public function getSellary(){
        return EmployeeSellarie::where('user_id' , Auth::id())->latest()->first();
    }
    public function HandleForceCheckout(Request $request){
        $response = $this->ForceCheckout($request);
        return response()->json(['message' => $response]);
    }

    public function HandleSellary($cash_paid_added , $comments , $id){

        $query = EmployeeSellarie::where('user_id' , $id);
        if (($query->pluck('id')->count() != 0)){
            $record = $query->latest()->first();
            $old_cash_remaining = $record->current_cash_remaining;
            $current_remaining  = $record->current_cash_remaining + $cash_paid_added;
        }else{
            $old_cash_remaining = 0;
            $current_remaining  = $cash_paid_added;
        }
        $StoreRecord                        = new EmployeeSellarie();
        $StoreRecord->user_id               = $id;
        $StoreRecord->old_cash_remaining    = $old_cash_remaining;
        $StoreRecord->current_cash_remaining= $current_remaining;
        $StoreRecord->cash_paid_added       = $cash_paid_added;
        $StoreRecord->comments              = $comments;

        $StoreRecord->save();        
    }
    public function ForceCheckout($request){

        $OldRecordQuery        = EmployeeAttandence::where(['user_id' => Auth::id() , 'user_active' => 1])->latest();
        $record                = $OldRecordQuery->first();
        if(!empty($record)){
        $distance              = $this->CalculateDistance($request);
        $getUsersettingdata    = $this->EmployeeAttandenceSettingdata();
        $time                  = Carbon::parse($record->start_time);
        $start_time            = $time->addMinutes(-2);
        $start_time            = $start_time->format('Y-m-d H:i:s');
        $ShiftEnded            = $this->calculateshiftstart($start_time , $record->shift_will_end);
        $emp                   = EmployeeAttandenceSetting::where('user_id' , Auth::id())->first();
        if($getUsersettingdata->distance_measure > $distance['distance'] && $ShiftEnded['shift'] == true){
            $this->UpdateCheckOutTime($record); 
            $this->checkout($record);
            return  "Thanx For Your Time!";  
        }elseif(!empty($record)){
            $this->checkout($record);
            return  "You Are Not At Your Location! Either you did not logged out at you time!";
        }
        }
    }



    public function UpdateCheckOutTime($record){

        $record->end_time = date('Y-m-d H:i:s');
        $record->save();

    }

    public function CheckIn(Request $request){

        $oldCheckIn = $this->CheckOldCheckInActive();

        $this->ForceCheckout($request);
        if (!empty($oldCheckIn['status'])){
            $this->checkout($oldCheckIn['record']);
        }
        $distance              = $this->CalculateDistance($request);
        if(!empty($distance['distance'])){
            $distance_in_meters    = $distance['distance'] * 1000;
        }
        $getUsersettingdata    = $this->EmployeeAttandenceSettingdata();
        $getUserattendencestatus = $this->EmployeeAttandencestatus();
        $start_time            = $getUsersettingdata->over_time_start;
        $end_time              = $getUsersettingdata->over_time_end;
        $user_active           = $getUsersettingdata->user_active;
        $distance_measure      = $getUsersettingdata->distance_measure;
        $shift_check           = $this->calculateshiftstart($start_time , $end_time);
        if ($getUserattendencestatus == true){
            $this->storeimagename();
            return "Already Checked In! Pass check out";
        }
        if ($user_active == 1 && $getUserattendencestatus == false && $shift_check['shift'] == true && !empty($distance['distance'])
        ){
            $this->StoreNewEmployeeAttandence($distance_measure , $distance_in_meters , $shift_check , $request);
            $this->storeimagename();
        }else{
            $this->StoreNewEmployeeAttandence($distance_measure , $distance_in_meters , $shift_check , $request);
            $this->storeimagename();     
        }

        if(!empty($distance['distance']) && $distance_measure < ($distance['distance'] * 1000)){
            $location_check['location_check'] = true;
        }else if(empty($distance['distance'])){
            $location_check['location_check'] = true;
        }
        else{
            $location_check['location_check'] = false;
        }
        //dd($shift_check);
        return ['shift' => $shift_check['shift'] , 'location_check' => $location_check['location_check']];

    }

    public function againCheckout($id){

        if(Auth::user()->role < 3 || Auth::user()->role == 4){
            $record = EmployeeAttandence::where('id' , $id)->first();
            $record->end_time = $record->updated_at;
            $record->save();
            $this->checkout($record);
        }
    }

    public function checkout($record){

        $emp = EmployeeAttandenceSetting::where('user_id' , $record->user_id)->first();
        //$emp_timing = $this->CheckEmploeeShiftGoNextDay($emp->start_time , $emp->end_time);
        $shift                                = $this->calculateshiftminutes($record , $table = "EmployeeAttandence");
        $record->minutes_served               =  $shift['total_minutes'];
        $record->per_minute_sellary           =  $emp->per_minute_sellary;
        $getShiftminutes                      =  $this->calculateshiftminutes($emp , $table = "EmployeeAttandenceSettign");
        $record->over_time_served             =  $shift['total_minutes'] - $getShiftminutes['total_minutes'];
        if($record->over_time_served < 0){
            $record->over_time_served = 0;
        }
        $record->over_time_per_minute_sellary =  $emp->over_time_per_minute_sellary;
        $record->user_active = 2;

        $cash_paid_added = (($record->minutes_served - $record->over_time_served) * $record->per_minute_sellary) + ($record->over_time_served * $record->over_time_per_minute_sellary);
        if ($cash_paid_added > 0){
            $this->HandleSellary($cash_paid_added , $comments = "Sellary Added!" , $record->user_id);    
        }
        $record->save();
    }

    public function calculateshiftminutes($record , $table){

      //  dd($record['start_time'] , $record['end_time'] , $record);

        if ($table == "EmployeeAttandenceSettign"){
            $emp_shift  = $this->CheckEmploeeShiftGoNextDay($record['over_time_start'] , $record['over_time_end']);
        }else if ($table == "EmployeeAttandence"){
            $emp_shift  = $this->CheckEmploeeShiftGoNextDay($record['start_time'] , $record['end_time']);
        }
        if ($emp_shift == true){
            $time_segment           = "23:59:59";   
            $assign_time            = new DateTime($record['start_time']);
            $start                  = $assign_time->format('H:i:s');
            $time1                  = $this->convTimeToMinutes($start , $time_segment);
            $time_segment           = "00:00:00";   
            $assign_time            = new DateTime($record['end_time']);
            $start                  = $assign_time->format('H:i:s');
            $time2                  = $this->convTimeToMinutes($start , $time_segment);
            $data['total_minutes']  = $time1[0] + $time2[0];
            $data['total_hours']    = $time1[1] + $time2[1];
        }elseif ($emp_shift == false){

            $assign_time    = new DateTime($record['start_time']);
            $start          = $assign_time->format('H:i:s');
            $end_time       = new DateTime($record['end_time']);
            $end            = $end_time->format('H:i:s');

            $time           = $this->convTimeToMinutes($start , $end);
            
            $data['total_minutes'] = $time[0];
            $data['total_hours']   = $time[1];
        }         
            return $data;

    }

    public function CheckEmploeeShiftGoNextDay($start_time , $end_time){

        $start_am_pm_time = date('h:i a', strtotime($start_time));
        $end_am_pm_time   = date('h:i a', strtotime($end_time));
        $start_time       = DateTime::createFromFormat('h:i a', $start_am_pm_time);
        $end_time         = DateTime::createFromFormat('h:i a', $end_am_pm_time);
       
        if ($start_time > $end_time){
            $data = true;
        }else{
            $data = false;
        }
            return $data;
    }

    public function CheckOldCheckInActive(){

        $OldRecordQuery = EmployeeAttandence::where(['user_id' => Auth::id() , 'user_active' => 1])->where( 'created_at', '<', Carbon::today() )->latest();
        $data['status']     = $OldRecordQuery->exists();
        $data['record']     = $OldRecordQuery->first();
        
        return $data;

    }

    public function StoreNewEmployeeAttandence($distance_measure ,$distance , $shift_check , $request){

        $todayQuery = EmployeeAttandence::where('user_id' , Auth::id())->whereDate('created_at', Carbon::today());
        $emp = EmployeeAttandenceSetting::where('user_id' , Auth::id())->first();

      //  dd($distance_measure ,$distance , $shift_check);
        if($shift_check['shift'] == true && $distance_measure >= $distance){

            $store = new EmployeeAttandence();
            $store->user_id = Auth::id();
            $store->user_active    = 1;    
        }else if ($todayQuery->whereNotNull('failed_attemptes')->pluck('id')->count() >= 1){
            $store = $todayQuery->whereNotNull('failed_attemptes')->first();
            $store->user_active      = 0;
            $store->failed_attemptes = $store->failed_attemptes + 1;   
        }else{
            $store = new EmployeeAttandence();
            $store->user_id = Auth::id();
            $store->user_active      = 0;
            $store->failed_attemptes = 1;
        }
        $cords                     = $request->lat.','.$request->lon;
        $store->cords              = $cords;
        $store->start_time         = date('Y-m-d H:i:s'); 
        $store->end_time           = date("Y-m-d H:i:s");
        $store->distance_measure   = $distance;
        $store->shift_will_end     = $this->ShiftWillEnd($emp);

        $store->save();        
    }
    public function ShiftWillEnd($emp){

        $assign_time  = new DateTime($emp->over_time_end);
        $dbdate       = $assign_time->format('Y-m-d');
        $dbtime       = $assign_time->format('H:i:s');

        $assign_time  = new DateTime();
        $current_time = $assign_time->format('H:i:s');

        $record['over_time_end']    = $emp->over_time_end;
        $record['over_time_start']  = $emp->over_time_start;
        $record['end_time']         = $dbdate.' '.$dbtime;
        $record['start_time']       = $dbdate.' '.$current_time;

        $time       = Carbon::parse(date("Y-m-d H:i:s"));
        $minutes    = $this->calculateshiftminutes($record , $table = "EmployeeAttandenceSettign");
        $endTime    = $time->addMinutes($minutes['total_minutes']);
        return $endTime;
    }

    public function storeimagename(){

        $imagquery = ImagesRecord::where('user_id' , Auth::id())->first();
        $employeeattandencequery = EmployeeAttandence::where('user_id' , Auth::id())->latest()->first();
        if (empty($employeeattandencequery->images) && !empty($imagquery)){
            $employeeattandencequery->images = $imagquery->image_no;
        }else if(!empty($imagquery)){
            $images = explode('|' , $employeeattandencequery->images);
            array_push($images , $imagquery->image_no);
            $images = implode('|', $images);
            $employeeattandencequery->images = $images;
        }
        $employeeattandencequery->save();

    }
    public function calculatetimedifference($start_time , $end_time){
            $assign_time  = new DateTime($start_time);
            $time_start   = $assign_time->format('H:i:s');
            $current_time = new DateTime();
            $checkin_time = $current_time->format('H:i:s');

            $time = $this->convTimeToMinutes($time_start , $checkin_time);
            
            $data['minutes'] = $time[0];
            $data['hours']   = $time[1];
            return $data;
    }
    public function calculateshiftstart($start_time , $end_time){

            $current_time     = new DateTime();
            $checkin_time     = $current_time->format('H:i:s');
            $current_time     = date('h:i a', strtotime($checkin_time));
            $start_am_pm_time = date('h:i a', strtotime($start_time));
            $end_am_pm_time   = date('h:i a', strtotime($end_time));
            $current_time     = DateTime::createFromFormat('h:i a', $current_time);
            $start_time       = DateTime::createFromFormat('h:i a', $start_am_pm_time);
            $end_time         = DateTime::createFromFormat('h:i a', $end_am_pm_time);

            //dd($start_time , $end_time , $current_time);
    
           
            if ($start_time > $end_time && $current_time > $start_time && $current_time > $end_time)
            {
               $data['shift'] = true;
            }elseif($start_time > $end_time && $current_time < $start_time && $current_time < $end_time){
                $data['shift'] = true;
            }elseif($start_time < $end_time && $current_time < $end_time && $current_time > $start_time){
               $data['shift'] = true;
            }
            else{
               $data['shift'] = false;
            }

            return $data;
    }

    public function convTimeToMinutes($intime, $outtime){
            $start   = Carbon::parse($intime);
            $end     = Carbon::parse($outtime);
            $minutes = $end->diffInMinutes($start); // 226
            return [$minutes , $this->convertMinuteToHours($minutes)];  
    } 

    public function convertMinuteToHours($minutes){
        return $minutes / 60;
    }


    public function EmployeeAttandenceSettingdata(){

        $Settingquery = EmployeeAttandenceSetting::where('user_id' , Auth::id());
        $user         = $Settingquery->pluck('id')->count();
        if ($user != 0){
            return $Settingquery->first();
        }else{
            return false;
        }
    }

    public function EmployeeAttandencestatus(){
        $Attendencequery     = EmployeeAttandence::where('user_id' , Auth::id())->whereDate('created_at', Carbon::today());
        $Attendencequeryuser = $Attendencequery->pluck('id')->count();
        if ($Attendencequeryuser != 0 && $Attendencequery->where('user_active' , 1)->pluck('id')->count()){
            return true;
        }else{
            return false;
        }
    }

    public function CalculateDistance($request){

       $PosController       = new PosController();
       $customer_id         = $PosController->GetCustomerId();
       $user_cords = $this->EmployeeAttandenceSettingdata()->location_cords;
       if($user_cords != false){
            $customerLocation    = explode(',', $user_cords);
       }else{
            $customer            = Customer::where('id' , $customer_id)->first();
            $customerLocation    = explode(',', $customer->location_url);
       }
       $result['distance']  = $this->distance($request->lat, $request->lon, $customerLocation[0], $customerLocation[1], 'K'); 

       if (empty($request->lat) && empty($request->lon)){
            $result = false;
       }
       return $result;
    }
    private function distance($lat1, $lon1, $lat2, $lon2, $unit = "K") {

        if (empty($lat1) || empty($lon1) || empty($lat2) || empty($lon2)) {
            return 0;
        }
        //dd($lat1, $lon1, $lat2, $lon2, $unit = "K");        
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $R = 6371; // km
            $dLat = $this->toRad($lat2-$lat1);
            $dLon = $this->toRad($lon2-$lon1);
            $lat1 = $this->toRad($lat1);
            $lat2 = $this->toRad($lat2);
    
            $a = sin($dLat/2) * sin($dLat/2) +sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2); 
            $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
            $d = ($R * $c) * 1.37;// here 1.37 is adjustment
            return $d;
            
        }
    }
    public function toRad($Value){
        return $Value * pi() / 180;
    }
    public function SellerImage(Request $request){

         if ($request->image) {
            $image = $request->file('image');
            $fileExtension   = "png";
             $ImagesRecord = ImagesRecord::pluck('id')->count();
            if ($ImagesRecord == 0){
                $newImagesRecord = new ImagesRecord();
                $newImagesRecord->user_id = Auth::id();
                $newImagesRecord->image_no = 1;

            }else{
                 $newImagesRecord = ImagesRecord::first();
                 $newImagesRecord->user_id = Auth::id();
                 $newImagesRecord->image_no = $newImagesRecord->image_no + 1;
            }
            $newImagesRecord->save();
            $PosController       = new PosController();
            $customer_id         = $PosController->GetCustomerId();
            $path = public_path().'/pointss/';
            $newpath = public_path().'/pointss/'.$customer_id.'/';
            //$file_name       = date('Y-m-d H:i:s').'|'.$newImagesRecord->image_no.'.'.$fileExtension;
            $file_name      = $newImagesRecord->image_no.'.'.$fileExtension;
            if (!File::exists($path)) {
                 File::makeDirectory($path, 0755, true);
            }
            if (!File::exists($newpath)) {
                 File::makeDirectory(public_path().'/pointss/'.$customer_id.'/', 0755, true , true);
            }
            $destinationPath = $newpath;
                $img = Image::make($image->getRealPath());
                $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.$file_name);
            $this->storeimagename();
            return ["Saveed Successfully" , $request->all() , $file_name];
        }
    }

    public function GetAttendenceRecord($user_id){

        $query = EmployeeAttandence::where('user_id' , $user_id)->get();
        return view('employeeAttandence.EmployeeAttandenceRecord' , compact('query'));

    }

    public function getAttanceDetail($id){

        $query = EmployeeAttandence::where('id' , $id)->first();
        $images= explode('|' , $query->images);
        $point = User::where('id' , $query->user_id)->pluck('customer_id')->first();
        $point = Customer::where('user_id' , $point)->pluck('id')->first();
        return view('ajax.attandence_detail' , compact('query' , 'point' , 'images'));
    }
    public function updateAttendenceRecord(Request $request){

        if(Auth::user()->role < 3 || Auth::user()->role == 4){
        $query = EmployeeAttandence::where('id' , $request->id)->first();
        $dateTime = $request->date.' '.$request->time;
        //dd($dateTime);
        $query->updated_at = $dateTime;
        $query->save();
        return redirect()->back()->with('success', 'Updated');
    }
    }
}