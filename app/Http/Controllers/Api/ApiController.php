<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Reference;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ApiController extends Controller
{
    /**
     * Edit Settings
     * @OA\Patch (
     *     path="/api/settings",
     *     tags={"Kledo Berhati Nyaman"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="key",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="value",
     *                          type="integer"
     *                      )
     *                 ),
     *                 example={
     *                     "key":"overtime_methods",
     *                     "valye":"1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="key", type="string", example="overrtime_methods"),
     *              @OA\Property(property="value", type="integer", example="1"), 
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="failed"),
     *          )
     *      )
     * )
     */
    public function patch_settings(Request $request)
    { 
        try {
            $request->validate([
                'key' => 'required',
                'value' => 'required',
            ]);
            $value = $request->value;
            $key = $request->key;
            if($key != 'overtime_method'){
                return ApiFormatter::createApi(400, 'Key Harus overtime_method');
            }
            $references = Reference::where('code','overtime_method')->get();
            foreach($references as $r){
                if($value == $r->id){
                    $setting = Setting::find(1);
                    $setting->key = $key;
                    $setting->value = $value;
                    $setting->save();
                    
                    $data = Setting::where('id', '=', $setting->id)->get();
        
                    if ($data) {
                        return ApiFormatter::createApi(200, 'Success', $data);
                    } else {
                        return ApiFormatter::createApi(400, 'Failed');
                    }
                }
            }
           
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, $error);
        }
    }
 /**
     * Store Employee
     * @OA\POST (
     *     path="/api/employees",
     *     tags={"Kledo Berhati Nyaman"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="nama",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="salary",
     *                          type="integer"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"Saleh",
     *                     "salary":"5000000"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="nama", type="string", example="Saleh"),
     *              @OA\Property(property="salary", type="integer", example="1"), 
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="failed"),
     *          )
     *      )
     * )
     */
    public function store_employees(Request $request){
        {
            try {
                $validator = Validator::make($request->all(), [
                    'name' => 'unique:employees|min:2|string', 
                    'salary' => 'min:2000000|max:10000000|numeric', 
                ], [
                    'name.unique:employees' => 'Nama harus Unik, dan Minimal 2 Karakter',
                    'name.min' => 'Nama Minimal 2 Karakter',
                    'salary.min' => 'Salary Minimal 2.000.000', 
                    'salary.max' => 'Salary Minimal 10.000.000', 
                ]);
                if ($validator->fails()) {

                    $msg = "";
                    foreach ($validator->messages()->all() as $message) {
                        $msg .= $message . ". ";
                    }
                    return ApiFormatter::createApi(400, $msg);
                } else {
                    $employee = new Employee();
                    $employee->name= $request->name;
                    $employee->salary = $request->salary;
                    $employee->save();
        
                    $data = Employee::where('id', '=', $employee->id)->get();
        
                    if ($data) {
                        return ApiFormatter::createApi(200, 'Success', 'data berhasil ditambahkan');
                    } else {
                        return ApiFormatter::createApi(400, 'Failed');
                    }
                }
            } catch (Exception $error) {
                return ApiFormatter::createApi(400, $error);
            }
        }
    }

 /**
     * Store Overtimes
     * @OA\POST (
     *     path="/api/overtimes",
     *     tags={"Kledo Berhati Nyaman"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="employee_id",
     *                          type="integer"
     *                      ),
     *                      @OA\Property(
     *                          property="date",
     *                          type="date"
     *                      ),
     *                      @OA\Property(
     *                          property="time_started",
     *                          type="time"
     *                      ),
     *                      @OA\Property(
     *                          property="time_ended",
     *                          type="time"
     *                      )
     * 
     *                 ),
     *                 example={
     *                     "employee_id":"1",
     *                     "date":"2022-02-02",
     *                     "time_started":"10:10:00",
     *                     "time_ended":"20:10:00",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="employee_id", type="integer", example="1"),
     *              @OA\Property(property="date", type="date", example="1"), 
     *              @OA\Property(property="time_started", type="time", example="10:10:00"),
     *              @OA\Property(property="time_ended", type="time", example="20:10:00"), 
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="failed"),
     *          )
     *      )
     * )
     */
    public function overtimes(Request $request){
        try {
            $validator = Validator::make($request->all(), [ 
                'employee_id'=>'numeric',
                'date' => ['required', 'date',Rule::unique('overtimes')->where(function ($query) use ($request) {
                    return $query->where('date', $request->date);
                })],
                'time_started'=>'date_format:H:i',
                'time_ended'=>'date_format:H:i|after:time_started',
            ], [
                'date' => 'Format Harus Benar',
                'time_start.date_format' => 'Format Time Start Harus Benar', 
                'time_ended.date_format' => 'Format Harus Benar', 
                'time_ended.after' => 'Time Ended Harus Lebih Besar dari Time Started', 
            ]);
            if ($validator->fails()) {

                $msg = "";
                foreach ($validator->messages()->all() as $message) {
                    $msg .= $message . ". ";
                }
                return ApiFormatter::createApi(400, $msg);
            } else {
                $employee = Employee::find($request->employee_id);
                if(!$employee){
                    return ApiFormatter::createApi(400, 'Data Employee tidak ada');
                }else{
                    $overtime = new Overtime();
                    $overtime->employee_id= $request->employee_id;
                    $overtime->date= $request->date;
                    $overtime->time_started= $request->time_started;
                    $overtime->time_ended = $request->time_ended;
                    $overtime->save();
        
                    $data = Overtime::where('id', '=', $overtime->id)->get();
        
                    if ($data) {
                        return ApiFormatter::createApi(200, 'Success', 'data berhasil ditambahkan');
                    } else {
                        return ApiFormatter::createApi(400, 'Failed');
                    }
                }
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, $error);
        }
        
    }

  /**
     * Overtimes Pay Calculate
     * @OA\GET (
     *     path="/api/overtime-pays/calculate",
     *     tags={"Kledo Berhati Nyaman"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="date",
     *                          type="string"
     *                      )
     * 
     *                 ),
     *                 example={
     *                     "date":"2022-02",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="date", type="string", example="2022-02"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="failed"),
     *          )
     *      )
     * )
     */
    public function overtimes_calculate(Request $request){

            $validator = Validator::make($request->all(), [ 
                'date'=>'date_format:Y-m|required',
            ], [
                'date' => 'Format Harus Benar',
                'date.required' => 'Data Tanggal harus diisi',
            ]);
            if ($validator->fails()) {

                $msg = "";
                foreach ($validator->messages()->all() as $message) {
                    $msg .= $message . ". ";
                }
                return ApiFormatter::createApi(400, $msg);
            } else {
                $date = $request->date; 
                $amount = 0;
                $month = date('m', strtotime($date));
                $year = date('Y', strtotime($date));
                $overtime_duration_total=0;
                $overtime_data = Overtime::whereYear('date',$year)->whereMonth('date',$month)->first();
                $overtime = DB::table('overtimes')    
                ->selectRaw('*,TIME_FORMAT((time_ended - time_started), "%H") as overtime')
                ->get();

                $overtime_akumulasi = Overtime::whereYear('date',$year)->whereMonth('date',$month)->get();
                foreach($overtime_akumulasi as $r){
                    $time_started = new DateTime($r->time_started);
                    $time_ended = new DateTime($r->time_ended);
                    $interval = $time_started->diff($time_ended);
                    $overtime_duration_total+=$interval->format("%H");
                }

                $settings = Setting::first();
                if($settings->value == 1){
                    $amount = ($overtime_data->employee->salary / 173)* $overtime_duration_total;
                }else if($settings->value == 2){
                    $amount = 10000* $overtime_duration_total;
                }
                 
                $data = [
                    'id'=>$overtime_data->employee->id,
                    'name'=>$overtime_data->employee->name,
                    'salary'=>$overtime_data->employee->salary,
                    'overtime_duration'=>$overtime,
                    'overtime_duration_total'=>$overtime_duration_total,
                    'amount'=>$amount,
                ];

                return ApiFormatter::createApi(200,'Berhasil', $data);

                
                $employee = Employee::find($request->employee_id);
                if(!$employee){
                    return ApiFormatter::createApi(400, 'Data Employee tidak ada');
                }else{
                    $overtime = new Overtime();
                    $overtime->employee_id= $request->employee_id;
                    $overtime->date= $request->date;
                    $overtime->time_started= $request->time_started;
                    $overtime->time_ended = $request->time_ended;
                    $overtime->save();
        
                    $data = Overtime::where('id', '=', $overtime->id)->get();
        
                    if ($data) {
                        return ApiFormatter::createApi(200, 'Success', 'data berhasil ditambahkan');
                    } else {
                        return ApiFormatter::createApi(400, 'Failed');
                    }
                }
            }
        
    }

}