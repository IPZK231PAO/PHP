<?php
namespace App\Controller;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/', name: 'student_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $students = $em->getRepository(Student::class)->findAll();
        return $this->json($students);
    }

    #[Route('/', name: 'student_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $student = new Student();
        $student->setName($data['name']);
        $student->setEmail($data['email']);
        $student->setPhone($data['phone']);
        $em->persist($student);
        $em->flush();
        return $this->json($student, 201);
    }

    #[Route('/{id}', name: 'student_update', methods: ['PUT'])]
    public function update(Request $request, Student $student, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $student->setName($data['name'] ?? $student->getName());
        $student->setEmail($data['email'] ?? $student->getEmail());
        $student->setPhone($data['phone'] ?? $student->getPhone());
        $em->flush();
        return $this->json($student);
    }

    #[Route('/{id}', name: 'student_delete', methods: ['DELETE'])]
    public function delete(Student $student, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($student);
        $em->flush();
        return $this->json(null, 204);
    }
}