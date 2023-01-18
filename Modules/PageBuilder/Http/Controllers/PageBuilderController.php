<?php

namespace Modules\PageBuilder\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\PageBuilder\Http\Requests\CustomPageRequest;
use Modules\PageBuilder\Repositories\PageBuilderRepository;
use Exception;
use Illuminate\Support\Facades\Http;

class PageBuilderController extends Controller
{
    protected $pageBuilderRepo;

    public function __construct(PageBuilderRepository $pageBuilderRepository)
    {
        $this->middleware(['auth'])->except('show');
        $this->pageBuilderRepo = $pageBuilderRepository;
        $this->middleware('prohibited_demo_mode')->only(['store','update']);
    }

    public function index()
    {
        try{
            $data['data'] = $this->pageBuilderRepo->all();
            return view('pagebuilder::pages.index',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }

    }


    public function store(CustomPageRequest $request)
    {
        try{
            $this->pageBuilderRepo->create($request->validated());
            return $this->reloadWithData();
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function design($id)
    {
        try{
            $data['row'] = $this->pageBuilderRepo->find($id);
            return view('pagebuilder::pages.design',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }



    public function designUpdate(Request $request ,$id){
        try{
            $this->pageBuilderRepo->designUpdate($request->all(),$id);
            return response()->json(['status'=>200]);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }


    }

    public function show($id)
    {
        try{
            $data['row'] = $this->pageBuilderRepo->find($id);
            // $html = $data['row']->description;
            // $dom = new DOMDocument();
            // $dom->loadHTML($html);
            // // dd($html,$dom);
            // $xpath = new DOMXPath($dom);

            // $nodes = $xpath->query('//div/@data-dynamic-href');
            // foreach ($nodes as $node) {
            //     $url = $node->value;
            //     $des = Http::get($url)->body();
            //     // $dom2 = new DOMDocument();
            //     // dd($des);
            //     // $dom2->loadHTML($des);
            //     // dd($dom2);
            //     // $xpath = new DOMXPath($dom);
            //     // dd($dom);
            //     // dd($node);
            //     $node->appendChild($des);
            // }
            // $dom->saveHTML();
            // dd($xpath);

            return view('pagebuilder::pages.show',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }

    }

    public function edit($id)
    {
        try{
            $data['row'] = $this->pageBuilderRepo->find($id);
            return view('pagebuilder::pages.edit',$data);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function update(CustomPageRequest $request, $id)
    {
        try{
            $this->pageBuilderRepo->update($request->validated(),$id);
            return $this->reloadWithData();
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }

    public function destroy(Request $request)
    {
        try{
            $this->pageBuilderRepo->delete($request->id);;
            return $this->reloadWithData();
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }
    public function status(Request $request){
        try{
            $this->pageBuilderRepo->status($request->except('_token'));
            return true;
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }


    private function reloadWithData(){
        try{
            $data = $this->pageBuilderRepo->all();
            return response()->json([
                'TableData' =>  (string)view('pagebuilder::pages.list',['data'=>$data]),
            ],200);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json([
                'error' => $e->getMessage()
            ],503);
        }
    }

    public function snippet()
    {
        try{
            return view('pagebuilder::snippet.index');
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }

    }

    public function slugGenerate(Request $request)
    {
        try{
            $slug = Str::slug($request->title,'-');
            return response()->json(['slug'=>$slug]);
        }catch(Exception $e){
            Toastr::error($e->getMessage(), 'Error!!');
            return response()->json(['error' => $e->getMessage()],503);
        }
    }
}
