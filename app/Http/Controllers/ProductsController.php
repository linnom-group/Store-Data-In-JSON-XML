<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class ProductsController extends Controller
{
    public function store(Request $request)
{

    $validatedData = $request->validate([
        'name' => 'required|string',
        'quantity' => 'required|integer|min:0',
        'price' => 'required|numeric|min:0',
    ]);

    $data = $validatedData;
    $data['id'] = uniqid();
    $data['created_at'] = Carbon::now()->toDateTimeString();

    $this->storeInJson('products.json', $data);
    $this->storeInXml('products.xml', $data);
    return response()->json(['success' => true, 'message' => 'Data saved successfully!']);
}

private function storeInJson($filePath, $data)
{

    $existingData = json_decode(Storage::disk('local')->exists($filePath)
        ? Storage::disk('local')->get($filePath)
        : '[]', true);

    $existingData[] = $data;
    Storage::disk('local')->put($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
}

private function storeInXml($filePath, $data)
{
    $xmlData = new SimpleXMLElement(
        Storage::disk('local')->exists($filePath)
            ? Storage::disk('local')->get($filePath)
            : '<products/>'
    );

    $product = $xmlData->addChild('product');
    foreach ($data as $key => $value) {
        $product->addChild($key, htmlspecialchars($value));
    }

    Storage::disk('local')->put($filePath, $xmlData->asXML());
}

    public function index()
    {
        $jsonContent = Storage::get('products.json');
        $data = json_decode($jsonContent, true);
        return response()->json($data);
     
    }
}