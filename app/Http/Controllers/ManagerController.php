<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Electric;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Validator;

class ManagerController extends Controller
{

    public function getTableElectrics()
    {
        return view('manager.tables.electrics');
    }

    public function loadDataTableElectrics()
    {
        $today = Carbon::now();
        $last_month = $today->year . '-' . ((($today->month - 1) > 9) ? ($today->month - 1) : ("0" . ($today->month - 1)));
        $electrics = Electric::select('electrics.id', 'room_id', 'time', 'old_number', 'new_number', 'price', 'status', 'rooms.name as room_name', 'electrics.deleted')->join('rooms', 'electrics.room_id', 'rooms.id')->where('time', $last_month)->get();
        $data = "";
        foreach ($electrics as $electric) {

            // DÙNG ĐỂ HIỂN THỊ RA HTML
            $status = ($electric->status == 1) ? "Đã nộp" : "Chưa nộp";
            $is_active = ($electric->deleted == 0) ? "False" : "True";
            $isDeleted = ($electric->deleted == 1) ? "background: #f44242; color: #FFFFFF;" : "";
            $isPaied = ($electric->status == 0) ? "background: #f49d41; color: #FFFFFF;" : "";
            //
            $data .= "
            <tr style='" . $isPaied . '' . $isDeleted . "'>
                <td class='hidden'>" . $electric->id . "</td>
                <td>" . $electric->room_name . "</td>
                <td>" . $electric->time . "</td>
                <td>" . $electric->old_number . "</td>
                <td>" . $electric->new_number . "</td>
                <td>" . $electric->price . "</td>
                <td>" . $status . "</td>
                <td>" . $is_active . "</td>
            </tr>
            ";
        }
        return response()->json($data);
    }

    public function CRUDTableElectrics()
    {
        header('Content-Type: application/json');

        $input = filter_input_array(INPUT_POST);

        if ($input['action'] == 'edit') {
            $electric = Electric::where('id', $input['id'])->first();
            $electric->old_number = $input['old_number'];
            $electric->new_number = $input['new_number'];
            $electric->price = $input['price'];
            $electric->status = $input['status'];
            $electric->deleted = $input['deleted'];
            $electric->save();
        } else if ($input['action'] == 'delete') {
            $electric = Electric::where('id', $input['id'])->first();
            $electric->deleted = 1;
            $electric->save();
        }
        echo json_encode($input);
    }
}
