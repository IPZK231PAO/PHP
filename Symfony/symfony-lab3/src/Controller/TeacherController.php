<?php
namespace App\Controller;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teacher')]
class TeacherController extends AbstractController
{
    #[Route('/', name: 'teacher_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $teachers = $em->getRepository(Teacher::class)->findAll();
        return $this->json($teachers);
    }

    #[Route('/', name: 'teacher_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $teacher = new Teacher();
        $teacher->setName($data['name']);
        $teacher->setDepartment($data['department']);
        $em->persist($teacher);
        $em->flush();
        return $this->json($teacher, 201);
    }

    #[Route('/{id}', name: 'teacher_update', methods: ['PUT'])]
    public function update(Request $request, Teacher $teacher, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $teacher->setName($data['name'] ?? $teacher->getName());
        $teacher->setDepartment($data['department'] ?? $teacher->getDepartment());
        $em->flush();
        return $this->json($teacher);
    }

    #[Route('/{id}', name: 'teacher_delete', methods: ['DELETE'])]
    public function delete(Teacher $teacher, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($teacher);
        $em->flush();
        return $this->json(null, 204);
    }
}