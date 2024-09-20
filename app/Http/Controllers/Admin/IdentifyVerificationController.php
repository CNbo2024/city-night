<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\Notifications;
use Illuminate\Http\Request;
use DB;

class IdentifyVerificationController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index()
    {
        $identities = DB::table('sv_doc_verification')->get();
        return view('identify-verification.index', compact('identities'));
    }

    public function status($status, $id, $message = '')
    {
        if ($message) {
            DB::table('sv_doc_verification')
                ->where('id', $id)
                ->update([
                    'status' => $status,
                    'comments' => $message,
                ]);    

        } else {
            DB::table('sv_doc_verification')
                ->where('id', $id)
                ->update(['status' => $status]);            
        }

        $doc = DB::table('sv_doc_verification')->where('id', $id)->first();

        $message = ($status == 'accept') ? 'Documento aceptado' : 'Documento rechazado';

        Notifications::create([
            'user_id' => $doc->user_id,
            'message' => $message,
            'status' => 'unread',
            'redirect' => '/documentVerification',
            'admin' => '0',
        ]);

        return redirect('/admin/identify-verification');
    }
}
