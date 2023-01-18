<?php

namespace Modules\Appearance\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Appearance\Services\HeaderService;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Product\Repositories\ProductRepository;
use Modules\Product\Services\CategoryService;
use Modules\Setup\Repositories\TagRepository;
use Modules\UserActivityLog\Traits\LogActivity;

class HeaderController extends Controller
{
    protected $headerService;
    public function __construct(HeaderService $headerService)
    {
        $this->headerService = $headerService;
        $this->middleware('maintenance_mode');
    }

    public function index()
    {
        try{
            $data['headers'] = $this->headerService->getHeaders();
            return view('appearance::header.index',$data);
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return $e->getMessage();
        }
    }

    public function setup($id){

        try{
            $data['header'] = $this->headerService->getById($id);
            $data['ZoneLists'] = $this->headerService->getAllZones();
            return view('appearance::header.components.setup',$data);
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return $e->getMessage();
        }
    }

    public function update(Request $request){

        try{
            $update = $this->headerService->update($request->except('_token'));
            LogActivity::successLog('header update successful.');
            return $update;
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return $e->getMessage();
        }

    }

    public function addElement(Request $request){

        try{
            $this->headerService->addElement($request->except('_token'));
            $data['header'] = $this->headerService->getById($request->id);
            LogActivity::successLog('element added successful.');
            return view('appearance::header.components.element_list',$data);
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return response()->json([
                'status' => 302
            ]);
        }

    }

    public function updateElement(Request $request){
        
        try{
            $this->headerService->updateElement($request->except('_token'));
            $data['header'] = $this->headerService->getById($request->header_id);
            LogActivity::successLog('element updated successful.');
            if($request->header_id == 4){
                return 1;
            }
            else{
                return view('appearance::header.components.element_list',$data);
            }
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return $e->getMessage();
        }
    }

    public function deleteElement(Request $request){
        try{
            $this->headerService->deleteElement($request->except('_token'));

            $data['header'] = $this->headerService->getById($request->header_id);
            LogActivity::successLog('element deleted successful.');
            return view('appearance::header.components.element_list',$data);
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return $e->getMessage();
        }
    }

    public function sortElement(Request $request){
        try{
            $sort = $this->headerService->sortElement($request->except('_token'));
            LogActivity::successLog('element sort successful.');
            return $sort;
        }catch(Exception $e){
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return $e->getMessage();
        }
    }

    public function update_status(Request $request)
    {
        try {
            $this->headerService->updateEnableStatus($request->except('_token'));
            LogActivity::successLog('element status updated successful.');
            return 1;
        } catch (\Exception $e) {
            LogActivity::errorLog($e->getMessage());
            Toastr::error(__('common.operation_failed'));
            return 0;
        }

    }

    public function getSliderTypeData(Request $request, CategoryService $categoryService){

        $data['data_type'] = $request->data_type;
        $productRepo = new ProductRepository();
        $data['first_product'] = $productRepo->firstSellerProduct();
        $data['first_category'] = $categoryService->firstCategory();
        $data['first_brand'] = $productRepo->firstBrand();
        $tagRepo = new TagRepository();
        $data['first_tag'] = $tagRepo->firstTag();
        return view('appearance::header.components.slider_for_data',$data);
    }
}
