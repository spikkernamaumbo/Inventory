<?php

namespace App\Http\Controllers;

use App\item;
use http\Env\Response;
use http\Exception\BadHeaderException;
use http\Exception\BadMessageException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class itemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
      return response()->json(["items"=>item::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $New_Item = new item;
        $New_Item->name = $request->name;
        $New_Item->quantity = $request->quantity;
        $New_Item->price = $request->price;
        $New_Item-> color= $request->color;
        $New_Item-> vat= $request->vat;
        $New_Item->  description= $request-> description;
        $New_Item->stockable= $request->  stockable;
        $New_Item->brand_id= $request-> brand_id;
        $New_Item->category_id= $request-> category_id;
        $New_Item->supplier_id= $request-> supplier_id;
// checking if the product is in the database
        $Available  = item::where('name',"=", $request->input('name'))->first();
        if($Available){
          return  response()->json([
                "message"=>"already in the database",
                "status"=>"401"
            ],401);
        }
else {
    try {
        //saving to the database
        if ($New_Item->save()) {
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
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $wantedItem = item::find($id);
        if(($wantedItem)){
            return response()->json(["item"=>$wantedItem]);

        }
        else{

            return response()->json(["message"=>"item not found"],401);
        }

    }
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        //entering the fields which needs to be edited
        $requestedItem = item::find($id);
        if(!$requestedItem){
            return  response()->json(["message"=>" item not found"],401);
        }
        else{

            $requestedItem->name = $request->name;
            $requestedItem->quantity = $request->quantity;
            $requestedItem->quantity = $request->quantity;
            $requestedItem->price = $request->price;
            $requestedItem-> color= $request->color;
            $requestedItem-> vat= $request->vat;
            $requestedItem->  description= $request-> description;
            $requestedItem->stockable= $request-> stockable;
            $requestedItem->brand_id= $request-> brand_id;
            $requestedItem->category_id= $request-> category_id;
            $requestedItem->supplier_id= $request-> supplier_id;
        }
            try {
                if ($requestedItem->save()){
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
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $unwantedItem = item::find($id);
        if(is_null($unwantedItem)){
          return  response()->json(["message"=>"not found"],401);
        }
        if(!$unwantedItem){
            return response()->json(["message" => "no such item in the database"],401);
        }
        else{
            $unwantedItem->delete();
            return response()->json(["message"=>"items deleted successfully"],201);
        }

    }
}
