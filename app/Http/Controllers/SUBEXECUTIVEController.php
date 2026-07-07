<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SubBackPage\SubloadpageTrait;
use App\Http\Controllers\Traits\SubBackPage\SubloadcontentTrait;
use App\Http\Controllers\Traits\SubBackPage\SubloaddirectTrait;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use App\Helpers\SlotHelper;

class SUBEXECUTIVEController extends Controller
{
    public function profile(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Profile');}
    public function appearance(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Appearance');}
    public function notification(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Notification');}
    public function activity(Request $request){return $this->subLoadPage($request,'Settings','SUBCONTENT.Activity');}

    public function statistiksurvey(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikSurvey'); }
    public function statistikreview(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikReview'); }
    public function statistikpengeluaran(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikPengeluaran'); }
    public function statistikpendapatan(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikPendapatan'); }
    public function statistikkinerja(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikKinerja'); }
    public function statistikkatalog(Request $request){ return $this->subLoadPage($request, 'Statistik', 'StatistikContent.StatistikKatalog'); }

    public function dataakun(Request $request){ return $this->subLoadPage($request, 'Management', 'Management.DataAkun'); }
    public function dataPartnership(Request $request){ return $this->subLoadPage($request, 'Management', 'Management.Partnership'); }
    public function kategoriPartnership(Request $request){ return $this->subLoadPage($request, 'Management', 'Management.KPartnership'); }

    use SubloadpageTrait;
    use SubloadcontentTrait;
    use SubloaddirectTrait;
}