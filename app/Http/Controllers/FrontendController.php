<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

class FrontendController extends Controller
{
    public function home()
    {
        $data['count_report'] = Report::count();
        return view('frontend.report.index', $data);
    }

    public function add_report()
    {
        return view('frontend.report.add');
    }

    public function save_report(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'status_karyawan' => 'required',
            'departemen' => 'required',
            'kategori_bahaya' => 'required',
            'contents_of_the_report' => 'required|min:2',
            'lokasi_kejadian' => 'required|min:2',
            'photo' => 'required',
        ]);

        $report = new Report;
        $report->contents_of_the_report = $request->contents_of_the_report;
        $report->name = $request->name;
        $report->status_karyawan = $request->status_karyawan;
        $report->departemen = $request->departemen;
        $report->kategori_bahaya = $request->kategori_bahaya;
        $report->lokasi_kejadian = $request->lokasi_kejadian;

        $photo = $request->file('photo');
        $tujuan_upload = 'avatar_report';
        $photo_name = time() . "_" . $photo->getClientOriginalName();
        $photo->move($tujuan_upload, $photo_name);
        $report->photo = $photo_name;
        
        $report->status = '0';
        $report->date_report = Date::now()->format('Y-m-d');
        
        $report->save();

        $response = new Response;
        $response->report_id = $report->id;
        $response->save();

        return redirect()->back()->with(['success' => 'Report has been saved !']);
    }

    public function report()
    {
        $data['report'] = Report::all();
        return view('frontend.report.index1', $data);
    }

    public function detail_report($id)
    {
        $data['report'] = Report::findOrFail($id);
        return view('frontend.report.detail', $data);
    }
}