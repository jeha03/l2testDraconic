<?php

namespace App\Http\Controllers\Lineage2\PersonalArea\Auth\Ajax\Admin\Statistics;

 use App\Http\Controllers\Controller;
 use App\Service\PersonalArea\AdminStatistics\IAdminStatistics;
 use App\Service\Utils\FunctionPaginate;
 use Config;
 use Illuminate\Http\Request;
 use Lang;
 use Response;

 class AdminStatisticsPaginatorVisit extends Controller
{

    private $count;
    private $admin_statistics;


    public function __construct(IAdminStatistics $admin_statistics)
    {
        $this->admin_statistics = $admin_statistics;
        $this->count = Config::get('lineage2.statistics.top_statistics');
    }

    //adminStatistics/visit?page=2 как пример
    public function page(Request $request)
    {
        $this->validate($request, [
            'page' => 'required|integer|max:1000',
        ]);


        $resultArrayOnlyIp = $this->admin_statistics->getDataOnlyAllIp();
        $data_pages = FunctionPaginate::paginate($resultArrayOnlyIp , $this->count);
        $data_pages->withPath('/adminStatistics/visit_filter');
        $data_result = FunctionPaginate::unlockedData($data_pages);

        try
        {
            if(is_array($resultArrayOnlyIp) and count($resultArrayOnlyIp) > 0){
                return Response::json(['success'=>Lang::get('messages.lk_admin_panel_windows_success') , 'data_result'=>$data_result]);
            }

            return Response::json(['success'=>Lang::get('messages.lk_admin_panel_windows_success') , 'data_result'=>'']);

        }
         catch (ModelNotFoundException $exception) {
            return Response::json(['error'=>$exception->getMessage() , 'result'=>'']);
        }
    }



}
