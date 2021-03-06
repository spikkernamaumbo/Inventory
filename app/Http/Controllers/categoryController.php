<?php

namespace App\Http\Controllers;

use http\Client\Response;
use http\Exception\BadHeaderException;
use http\Exception\BadMessageException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\category;



class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
       return response()->json(["category" => category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $New_Category = new category;
        $New_Category->categoryName = $request->get("categoryName");
        $New_Category->description = $request->get("description");

        $Available = category::where('categoryName', "=", $request->input('categoryName'))->first();
        if ($Available) {
            return response()->json([
                "message" => "already in the database",
                "status" => "401"
            ], 401);
        }
        else {
            try {
               ///// saving to the database
              if($New_Category->save())  {
                    return response()->json([
                        "message" => "success",
                        "status" => "ok"
                    ], 201);
                }
            } catch (BadMessageException $ex) {
            };
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $categoryName
     * @return JsonResponse
     */
    public function show($categoryName): JsonResponse
    {
        $wantedCategory = category::find($categoryName);
        if(($wantedCategory)){
            return response()->json(["item"=>$wantedCategory]);

        }
        else{

            return response()->json(["message"=>"item not found"],401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $categoryName
     * @return JsonResponse
     */
    public function update(Request $request, $categoryName): JsonResponse
    {
        $New_Category = category::find($categoryName);
        if(!$New_Category){
            return  response()->json(["message"=>" item not found"],401);
        }
        else{
           $New_Category->categoryName = $request->get("categoryName");
          $New_Category->description = $request->get("description");

        }
        try {
            if ($New_Category->save()){
                return response()->json([
                    "message" => "change done",
                    "status" => "complete"
                ],201);
            }
        }catch(BadHeaderException $evt){};

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $categoryName
     * @return JsonResponse
     */
    public function destroy($categoryName): JsonResponse
    {
        $unwantedBrand = category::find($categoryName);
        if(is_null($unwantedBrand)){
            return  response()->json(["message"=>"not found"],401);
        }
        if(!$unwantedBrand){
            return response()->json(["message" => "no such item in the database"],401);
        }
        else{
            $unwantedBrand->delete();
            return response()->json(["message"=>"items deleted successfully"],201);
        }

    }

}
