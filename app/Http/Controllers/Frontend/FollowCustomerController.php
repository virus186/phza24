<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\MultiVendor\Entities\FollowSeller;
use Modules\MultiVendor\Repositories\FollowRepository;

class FollowCustomerController extends Controller
{
    protected $followRepo;

    public function __construct(FollowRepository $followRepo)
    {
        $this->followRepo = $followRepo;
    }

    public function follow_customer()
    {
        if(!isModuleActive('MultiVendor')){
            return abort(404);
        }
        $followSellers = FollowSeller::with('seller')->where('customer_id',auth()->id())->paginate(10);
        return view('frontend.amazy.pages.follow.index',compact('followSellers')); 
        
    }

    public function store(Request $request)
    { 
        try{

           $result = $this->followRepo->saveFollow($request->except("_token"));
           if($result){
            return response()->json([
                'status'    =>  true,
                'message'   =>  'success'
            ],201);       
           }
           return response()->json([
            'message'   =>  'Allredi Exist'
        ],403);   
        }catch(Exception $e){
            return response()->json([
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ],500);       
         }

    }

    public function unfollow(Request $request){
        try{

        $result = $this->followRepo->unFollow($request->except("_token"));
        if($result){
            return response()->json([
                'status'    =>  true,
                'message'   =>  'success'
            ],201);       
        }
        return response()->json([
            'message'   =>  'invalid'
        ],404);   
        }catch(Exception $e){
            return response()->json([
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ],500);       
        }
    }
   
}
