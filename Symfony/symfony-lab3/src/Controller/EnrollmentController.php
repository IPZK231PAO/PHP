<?php
namespace App\Controller;

use App\Entity\Enrollment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/enrollment')]
class EnrollmentController extends AbstractController
{
    #[Route('/', name: 'enrollment_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $enrollments = $em->getRepository(Enrollment::class)->findAll();
        return $this->json($enrollments);
    }

    #[Route('/', name: 'enrollment_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $enrollment = new Enrollment();
        $enrollment->setStudent($em->getRepository(Student::class)->find($data['student_id']));
        $enrollment->setCourse($em->getRepository(Course::class)->find($data['course_id']));
        $enrollment->setSemester($data['semester']);
        $em->persist($enrollment);
        $em->flush();
        return $this->json($enrollment, 201);
    }

    #[Route('/{id}', name: 'enrollment_update', methods: ['PUT'])]
    public function update(Request $request, Enrollment $enrollment, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (isset($data['student_id'])) $enrollment->setStudent($em->getRepository(Student::class)->find($data['student_id']));
        if (isset($data['course_id'])) $enrollment->setCourse($em->getRepository(Course::class)->find($data['course_id']));
        if (isset($data['semester'])) $enrollment->setSemester($data['semester']);
        $em->flush();
        return $this->json($enrollment);
    }

    #[Route('/{id}', name: 'enrollment_delete', methods: ['DELETE'])]
    public function delete(Enrollment $enrollment, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($enrollment);
        $em->flush();
        return $this->json(null, 204);
    }
}