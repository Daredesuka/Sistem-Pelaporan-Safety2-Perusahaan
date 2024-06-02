<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class FrontendController extends Controller
{
    public function home()
    {
        $data['count_complaint'] = Complaint::count();
        return view('frontend.complaint.index', $data);
    }

    public function add_complaint()
    {
        return view('frontend.complaint.add');
    }

    public function save_complaint(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:2',
            'contents_of_the_report' => 'required|min:2',
            'photo' => 'required',
        ]);

        $complaint = new Complaint;
        $complaint->contents_of_the_report = $request->contents_of_the_report;
        $complaint->name = $request->name;
        $complaint->status_karyawan = $request->status_karyawan;
        $complaint->departemen = $request->departemen;
        $complaint->kategori_bahaya = $request->kategori_bahaya;
        $photo = $request->file('photo');
        $tujuan_upload = 'avatar_complaint';
        $photo_name = time() . "_" . $photo->getClientOriginalName();
        $photo->move($tujuan_upload, $photo_name);
        $complaint->photo = $photo_name;
        $complaint->status = '0';
        $complaint->date_complaint = Date::now()->format('Y-m-d');
        
        // Hapus assignment society_id
        $complaint->nik = 'default_nik';
        
        $complaint->save();

        $response = new Response;
        $response->complaint_id = $complaint->id;
        $response->save();

        return redirect()->back()->with(['success' => 'Complaint has been saved !']);
    }

    public function complaint()
    {
        $data['complaint'] = Complaint::all();
        return view('frontend.complaint.index1', $data);
    }

    public function detail_complaint($id)
    {
        $data['complaint'] = Complaint::findOrFail($id);
        return view('frontend.complaint.detail', $data);
    }
}