<?php

namespace App\Http\Controllers\Lineage2\PersonalArea\Auth\Ajax\Admin\Payments;

 use App\Http\Controllers\Controller;
 use App\Service\Payments\Admin\Filters\PaymentsFiltersService;
 use App\Service\Utils\DataPaginate;
 use App\Service\Utils\FunctionPaginate;
 use App\Service\Utils\FunctionSupport;
 use Config;
 use Illuminate\Http\Request;
 use Lang;
 use Response;

 class AdminPaymentsFilters extends Controller
{
    private $tables_db_payments;
    private $payments_admin_service;
    private $count;
    private $filter_items;


    public function __construct()
    {
        $this->tables_db_payments = Config::get('lineage2.server.support_payments_tables');
        $this->count = Config::get('lineage2.server.top_count_payments');
        $this->payments_filters= new PaymentsFiltersService();
        $this->filter_items = Config::get('lineage2.server.support_payments_filters');
    }

    ///adminPayments/filter?arrayfil[]=val1&foo[]=val2&foo[]=val3
    public function filter(Request $request)
    {
        $validator = $request->validate([
            'arrayfil' => 'required|array',
            'arrayfil.*' => 'integer',
            'text' => 'required|string|max:50',
        ]);


        $filterArrayId = FunctionSupport::getDataVariable("arrayfil" , $validator);
        $filterText = FunctionSupport::getDataVariable("text" , $validator);

        $filterArrayOrders = $this->payments_filters->getDataByFilters($filterArrayId , $filterText , $this->tables_db_payments);



        $data_pages = FunctionPaginate::paginate($filterArrayOrders , $this->count);
        $data_pages->withPath('/adminPayments/filters_pag/');
        $data_result = FunctionPaginate::unlockedData($data_pages);

        try
        {
            if(isset($filterArrayOrders)){
                return Response::json(['success'=>Lang::get('messages.lk_admin_panel_windows_success') , 'data_result'=>$data_result]);
            }

            return Response::json(['success'=>Lang::get('messages.lk_admin_panel_windows_success') , 'data_result'=>[]]);

        }
         catch (ModelNotFoundException $exception) {
            return Response::json(['error'=>$exception->getMessage() , 'result'=>'']);
        }
    }



}
