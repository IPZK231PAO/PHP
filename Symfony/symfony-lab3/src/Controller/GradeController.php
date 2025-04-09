<?php
namespace App\Controller;

use App\Entity\Grade;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/grade')]
class GradeController extends AbstractController
{
    #[Route('/', name: 'grade_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $grades = $em->getRepository(Grade::class)->findAll();
        return $this->json($grades);
    }

    #[Route('/', name: 'grade_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $grade = new Grade();
        $grade->setStudent($em->getRepository(Student::class)->find($data['student_id']));
        $grade->setCourse($em->getRepository(Course::class)->find($data['course_id']));
        $grade->setScore($data['score']);
        $em->persist($grade);
        $em->flush();
        return $this->json($grade, 201);
    }

    #[Route('/{id}', name: 'grade_update', methods: ['PUT'])]
    public function update(Request $request, Grade $grade, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['student_id'])) $grade->setStudent($em->getRepository(Student::class)->find($data['student_id']));
        if (isset($data['course_id'])) $grade->setCourse($em->getRepository(Course::class)->find($data['course_id']));
        if (isset($data['score'])) $grade->setScore($data['score']);
        $em->flush();
        return $this->json($grade);
    }

    #[Route('/{id}', name: 'grade_delete', methods: ['DELETE'])]
    public function delete(Grade $grade, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($grade);
        $em->flush();
        return $this->json(null, 204);
    }
}