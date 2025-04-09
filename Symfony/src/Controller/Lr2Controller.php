<?php
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    private const ITEMS = []; // Приклад даних

    #[Route('/items', name: 'get_items', methods: [Request::METHOD_GET])]
    public function getItems(): JsonResponse
    {
        return new JsonResponse(['data' => self::ITEMS], JsonResponse::HTTP_OK);
    }

    #[Route('/items/{id}', name: 'get_item', methods: [Request::METHOD_GET])]
    public function getItem(string $id): JsonResponse
    {
        $item = $this->getItemById($id);
        if (!$item) {
            return new JsonResponse(
                ['error' => "Item not found with id $id"], 
                JsonResponse::HTTP_NOT_FOUND
            );
        }
        return new JsonResponse(['data' => $item], JsonResponse::HTTP_OK);
    }

    #[Route('/items', name: 'create_item', methods: [Request::METHOD_POST])]
    public function createItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $newItem = [
            'id' => random_int(1, 100),
            'title' => $data['title'],
            'category' => $data['category'],
            'value' => $data['value']
        ];
        return new JsonResponse(['data' => $newItem], JsonResponse::HTTP_CREATED);
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
