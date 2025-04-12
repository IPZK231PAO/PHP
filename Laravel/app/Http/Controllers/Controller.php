<?php

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    private static array $items = []; 

    #[Route('/items', name: 'get_items', methods: [Request::METHOD_GET])]
    public function getItems(): JsonResponse
    {
        return new JsonResponse(['data' => self::$items], JsonResponse::HTTP_OK);
    }

    #[Route('/items/{id}', name: 'get_item', methods: [Request::METHOD_GET])]
    public function getItem(string $id): JsonResponse
    {
        $item = $this->findItemById($id);
        if (!$item) {
            return $this->json(['error' => "Item not found with id $id"], JsonResponse::HTTP_NOT_FOUND);
        }
        return $this->json(['data' => $item], JsonResponse::HTTP_OK);
    }

    #[Route('/items', name: 'create_item', methods: [Request::METHOD_POST])]
    public function createItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['title']) || !isset($data['category']) || !isset($data['value'])) {
            return $this->json(['error' => 'Missing required fields'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $newItem = [
            'id' => uniqid(),
            'title' => $data['title'],
            'category' => $data['category'],
            'value' => $data['value']
        ];
        
        self::$items[] = $newItem;
        
        return $this->json(['data' => $newItem], JsonResponse::HTTP_CREATED);
    }

    #[Route('/items/{id}', name: 'update_item', methods: [Request::METHOD_PUT])]
    public function updateItem(Request $request, string $id): JsonResponse
    {
        $item = $this->findItemById($id);
        if (!$item) {
            return $this->json(['error' => "Item not found with id $id"], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $item['title'] = $data['title'];
        }
        if (isset($data['category'])) {
            $item['category'] = $data['category'];
        }
        if (isset($data['value'])) {
            $item['value'] = $data['value'];
        }

        foreach (self::$items as &$storedItem) {
            if ($storedItem['id'] === $id) {
                $storedItem = $item;
                break;
            }
        }

        return $this->json(['data' => $item], JsonResponse::HTTP_OK);
    }

    #[Route('/items/{id}', name: 'delete_item', methods: [Request::METHOD_DELETE])]
    public function deleteItem(string $id): JsonResponse
    {
        $found = false;
        foreach (self::$items as $key => $item) {
            if ($item['id'] === $id) {
                unset(self::$items[$key]);
                $found = true;
                break;
            }
        }

        if (!$found) {
            return $this->json(['error' => "Item not found with id $id"], JsonResponse::HTTP_NOT_FOUND);
        }

        self::$items = array_values(self::$items);
        
        return $this->json(null, JsonResponse::HTTP_NO_CONTENT);
    }

    private function findItemById(string $id): ?array
    {
        foreach (self::$items as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }
}