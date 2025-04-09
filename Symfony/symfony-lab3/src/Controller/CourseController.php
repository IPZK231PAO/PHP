<?php
namespace App\Controller;

use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/course')]
class CourseController extends AbstractController
{
    #[Route('/', name: 'course_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $courses = $em->getRepository(Course::class)->findAll();
        return $this->json($courses);
    }

    #[Route('/', name: 'course_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $course = new Course();
        $course->setTitle($data['title']);
        $course->setCredits($data['credits']);
        $em->persist($course);
        $em->flush();
        return $this->json($course, 201);
    }

    #[Route('/{id}', name: 'course_update', methods: ['PUT'])]
    public function update(Request $request, Course $course, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $course->setTitle($data['title'] ?? $course->getTitle());
        $course->setCredits($data['credits'] ?? $course->getCredits());
        $em->flush();
        return $this->json($course);
    }

    #[Route('/{id}', name: 'course_delete', methods: ['DELETE'])]
    public function delete(Course $course, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($course);
        $em->flush();
        return $this->json(null, 204);
    }
}