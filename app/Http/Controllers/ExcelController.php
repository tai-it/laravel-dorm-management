<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;

class ExcelController extends Controller
{
    function importElectrics(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required|mimes:xls,xlsx'
        ]);

        $path = $request->file('file')->getRealPath();

        $data = Excel::load($path)->get();

        if ($data->count() > 0) {
            foreach ($data->toArray() as $row) {
                $insert_data[] = array(
                    'room_id'  => $row['roomid'],
                    'time'   => $row['time'],
                    'old_number'   => $row['oldnumber'],
                    'new_number'    => $row['newnumber'],
                    'price'  => $row['price'],
                    'status'   => $row['status']
                );
            }
            if (!empty($insert_data)) {
                DB::table('electrics')->insert($insert_data);
            }
        }

        return redirect()->back()->with('success', 'Import dữ liệu thành công!');
    }

    function importWaters(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required|mimes:xls,xlsx'
        ]);

        $path = $request->file('file')->getRealPath();

        $data = Excel::load($path)->get();

        if ($data->count() > 0) {
            foreach ($data->toArray() as $row) {
                $insert_data[] = array(
                    'room_id'  => $row['roomid'],
                    'time'   => $row['time'],
                    'old_number'   => $row['oldnumber'],
                    'new_number'    => $row['newnumber'],
                    'price'  => $row['price'],
                    'status'   => $row['status']
                );
            }
            if (!empty($insert_data)) {
                DB::table('waters')->insert($insert_data);
            }
        }

        return redirect()->back()->with('success', 'Import dữ liệu thành công!');
    }

    function importStudents(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required|mimes:xls,xlsx'
        ]);

        $path = $request->file('file')->getRealPath();

        $data = Excel::load($path)->get();

        if ($data->count() > 0) {
            foreach ($data->toArray() as $row) {
                $insert_data[] = array(
                    'name'  => "'" . $row['name'] . "'",
                    'email'   => "'" . $row['email'] . "'",
                    'birthday'   => date("Y-m-d", strtotime($row['birthday'])),
                    'gender'    => "'" . $row['gender'] . "'",
                    'address'  => "'" . $row['address'] . "'",
                    'phone'   => "'" . $row['phone'] . "'",
                    'room_id'   => $row['room_id'],
                    'class_id'   => $row['class_id'],
                );
            }
            if (!empty($insert_data)) {
                DB::table('students')->insert($insert_data);
            }
        }

        return redirect()->back()->with('success', 'Import dữ liệu thành công!');
    }
}
