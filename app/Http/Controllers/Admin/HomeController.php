<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Visitor;
use App\User;
use App\Page;

class HomeController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
        $d = intval($request->input('d', 30));
        if($d > 360){
            $d = 360;
        }
        
        // Contagem de visitantes
        $dateVisitsLimit = date('Y-m-d H:i:s', strtotime('-'.$d.' days'));
        $visitsList = Visitor::select('ip')->where('date_access', '>=', $dateVisitsLimit)->get();
        $visitsCount = count($visitsList);

        // Contagem de usuários online
        $dateLimit = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        $onlineList = Visitor::select('ip')->where('date_access', '>=', $dateLimit)->groupBy('ip')->get();
        $onlineCount = count($onlineList);

        // Contagem de páginas criadas
        $pageCount = Page::count();

        // Contagem de usuários
        $userCount = User::count();

        // Dados para o gráfico
        $dateVisitsAllLimit = date('Y-m-d H:i:s', strtotime('-'.$d.' days'));
        $visitsAll = Visitor::selectRaw('page, count(page) as c')->where('date_access', '>=', $dateVisitsAllLimit)->groupBy('page')->get();
        foreach ($visitsAll as $visit) {
            $pagePie[$visit['page']] = intval($visit['c']);
        }

        $pageLabels = json_encode(array_keys($pagePie));
        $pageValues = json_encode(array_values($pagePie));

        return view('admin.home', [
            'visitsCount' => $visitsCount,
            'onlineCount' => $onlineCount,
            'pageCount' => $pageCount,
            'userCount' => $userCount,
            'pageLabels' => $pageLabels,
            'pageValues' => $pageValues,
            'dateInterval' => $d
        ]);
    }
}
