<?php

namespace App\Http\Controllers;

use App\Models\Date;
use App\Models\Department;
use App\Models\Doctordept;
use App\Models\Slot;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function createSlot()
    {
        $round = 1;
        $slots = [
            "9:00 - 10:30",
            "10:30 - 12:0",
            "13:30 - 15:00",
            "15:00 - 16:30",
        ];

        $dateStart = date_create('2024-09-23');
        $dateEnd = date_create('2024-09-27');
        $diff = date_diff($dateStart, $dateEnd);
        for ($i = 1; $i <= $diff->days + 1; $i++) {
            $date = date_format($dateStart, "Y-m-d");
            foreach ($slots as $slot) {
                $new_date = new Date;
                $new_date->date = $date;
                $new_date->date_string = strtotime($date);
                $new_date->time = $slot;
                $new_date->round = $round;
                $new_date->save();
                $round = $round + 1;
            }
            $dateStart = date_add($dateStart, date_interval_create_from_date_string("1 days"));
        }
    }
    public function main()
    {
        $depts = Department::orderBy('name', 'asc')->get();
        $doctors = Doctordept::orderBy('name', 'asc')->get();
        $dates_data = Date::get();
        $data = [];
        foreach ($dates_data as $item) {
            $data[$item->date]['day'] = date('D', $item->date_string, );
            $data[$item->date]['date'] = date('d M', $item->date_string);
            $data[$item->date]['slot'][] = [
                'time' => $item->time,
                'date' => date('D d M', $item->date_string),
                'round' => $item->round,
                'slot' => $item->slot,
            ];
        }
        return view('index')->with(compact('depts', 'doctors', 'data'));
    }
    public function saveSlot(Request $request)
    {
        $oldSlot = Slot::where('userid', $request->userid)->where('name', $request->name)->where('delete', 0)->first();
        if ($oldSlot !== null) {
            $oldDate = Date::where('round', $oldSlot->round)->first();
            $oldDate->slot = $oldDate->slot + 1;
            switch ($oldSlot->level) {
                case 1:
                    $oldDate->level1 = $oldDate->level1 + 1;
                    break;
                case 2:
                    $oldDate->level2 = $oldDate->level2 + 1;
                    break;
                case 3:
                    $oldDate->level3 = $oldDate->level3 + 1;
                    break;
                case 4:
                    $oldDate->level4 = $oldDate->level4 + 1;
                    break;
                case 5:
                    $oldDate->level5 = $oldDate->level5 + 1;
                    break;
                case 6:
                    $oldDate->level6 = $oldDate->level6 + 1;
                    break;
            }
            $oldDate->save();
            $oldSlot->delete = 1;
            $oldSlot->save();
        }
        $Date_Slot = explode('_', $request->round);
        $findSlot = Date::where('round', $Date_Slot[1])->first();
        if ($findSlot->slot <= 0) {

            return response()->json(['status' => 2, 'description' => 'รอบที่เลือกเต็มแล้ว'], 200);
        }
        switch ($findSlot) {
            case $findSlot->level1 > 0:
                $level = 1;
                $findSlot->level1 = $findSlot->level1 - 1;
                break;
            case $findSlot->level2 > 0:
                $level = 2;
                $findSlot->level2 = $findSlot->level2 - 1;
                break;
            case $findSlot->level3 > 0:
                $level = 3;
                $findSlot->level3 = $findSlot->level3 - 1;
                break;
            case $findSlot->level4 > 0:
                $level = 4;
                $findSlot->level4 = $findSlot->level4 - 1;
                break;
            case $findSlot->level5 > 0:
                $level = 5;
                $findSlot->level5 = $findSlot->level5 - 1;
                break;
            case $findSlot->level6 > 0:
                $level = 6;
                $findSlot->level6 = $findSlot->level6 - 1;
                break;

        }
        $findSlot->slot = $findSlot->slot - 1;
        $new = new Slot;
        $new->round = $Date_Slot[1];
        $new->level = $level;
        $new->type = $request->type;
        $new->userid = $request->userid;
        $new->name = $request->name;
        if ($request->dept_doctor !== null) {
            $position = $request->dept_doctor;
        } else if ($request->dept_user !== null) {
            $position = $request->dept_user;
        } else {
            $position = $request->dept_outsource;
        }
        $new->position = $position;
        $new->save();
        $findSlot->save();

        return response()->json(['status' => 1, 'description' => 'ลงทะเบียนสำเร็จ'], 200);
    }
    public function check()
    {
        return view('check');
    }
    public function search(Request $request)
    {
        $data = Slot::where('userid', $request->userid)
            ->where('name', $request->name)
            ->where('delete', 0)
            ->join('dates', 'slots.round', 'dates.round')
            ->first();
        if ($data == null) {
            return response()->json(['status' => 2, 'descriptaion' => 'ไม่พบข้อมูลการลงทะเบียน'], 200);
        }
        $data->date = date('d M Y', $data->date_string) . ' ( ' . $data->time . ' น. )';
        switch ($data->level) {
            case 1:
                $level = '1. QPS Program 2024 / IPSG';
                break;
            case 2:
                $level = '2. Workplace violence /Safety Program second victims';
                break;
            case 3;
                $level = '3. Fire safety / FMS Program';
                break;
            case 4:
                $level = '4. Patient right ';
                break;
            case 5:
                $level = '5. Global health impact';
                break;
            case 6:
                $level = '6. Emerging trends in infection prevention ';
                break;
        }

        return response()->json(['status' => 1, 'descriptaion' => 'พบข้อมูลการลงทะเบียน', 'name' => $data->name, 'dept' => $data->position, 'date' => $data->date, 'level' => $level], 200);
    }
    public function admin()
    {
        $datas = Slot::join('dates', 'slots.round', 'dates.round')
            ->where('slots.delete', 0)
            ->orderBy('dates.date', 'asc')
            ->orderBy('dates.round', 'asc')
            ->get();
        $data = [];
        foreach ($datas as $item) {
            switch ($item->level) {
                case 1:
                    $level = '1. QPS Program 2024 / IPSG';
                    break;
                case 2:
                    $level = '2. Workplace violence /Safety Program second victims';
                    break;
                case 3;
                    $level = '3. Fire safety / FMS Program';
                    break;
                case 4:
                    $level = '4. Patient right ';
                    break;
                case 5:
                    $level = '5. Global health impact';
                    break;
                case 6:
                    $level = '6. Emerging trends in infection prevention ';
                    break;
            }

            $data[date('d M Y', $item->date_string)][$item->time][$level][] = [
                'userid' => $item->userid,
                'name' => $item->name,
                'position' => $item->position,
            ];
        }

        return view('admin')->with(compact('data'));
    }
}
