<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ItemController extends Controller
{
    private const ITEMS = []; // Приклад даних

    public function index()
    {
        return response()->json(self::ITEMS, Response::HTTP_OK);
    }

    public function show(string $id)
    {
        $item = $this->getItemById($id);
        if (!$item) {
            return response()->json(
                ['error' => "Item not found with id $id"], 
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json(['data' => $item], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $newItem = [
            'id' => rand(1, 100),
            'title' => $data['title'],
            'category' => $data['category'],
            'value' => $data['value']
        ];
        // TODO: Зберегти в базу даних
        return response()->json(['data' => $newItem], Response::HTTP_CREATED);
    }

    private function getItemById(string $id): ?array
    {
        foreach (self::ITEMS as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        return null;
    }
}