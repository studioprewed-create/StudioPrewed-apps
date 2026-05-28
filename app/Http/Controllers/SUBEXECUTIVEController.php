<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SUBEXECUTIVEController extends Controller
{
    public function profile(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Profile');}
    public function appearance(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Appearance');}
    public function notification(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Notification');}
    public function activity(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Activity');}

    private function subLoadPage(Request $request, $page, $subpage)
        {
            if ($request->ajax()) {
                return $this->subLoadContent($request, $page, $subpage);
            }
            return view('OPERATIONALPAGES.PAGE.EXECUTIVE', ['page' => $page, 'subpage' => $subpage]);
        }
    public function subLoadContent(Request $request, $page, $subpage)
        {

            if (view()->exists("OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage")) {
                return view("OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage");
            }

            return "<div class='alert alert-warning'>Halaman <b>$page</b> belum dibuat.</div>";
        }
    public function subLoadDirect(Request $request, $page, $subpage)
        {
            if (view()->exists("OPERATIONALPAGES.FITUR.MAINCONTENT.$subpage")) {
                return view('OPERATIONALPAGES.PAGE.EXECUTIVE', ['page' => $page, 'subpage' => $subpage]);
            }
            abort(404, "Halaman $page tidak ditemukan");
        }
}