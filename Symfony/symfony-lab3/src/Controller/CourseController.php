<?php

namespace App\Controller;

use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/course', name: 'course_index', methods: ['GET'])]
    public function index(): Response
    {
        $courses = $this->entityManager->getRepository(Course::class)->findAll();
        return $this->render('course/index.html.twig', ['courses' => $courses]);
    }

    #[Route('/course/new', name: 'course_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $course = new Course();
        $course->setTitle($request->request->get('title'));
        $course->setCredits($request->request->get('credits'));

        $this->entityManager->persist($course);
        $this->entityManager->flush();

        return $this->redirectToRoute('course_index');
    }

    #[Route('/course/{id}/edit', name: 'course_edit', methods: ['PUT'])]
    public function edit(Request $request, int $id): Response
    {
        $course = $this->entityManager->getRepository(Course::class)->find($id);
        
        if ($course) {
            $course->setTitle($request->request->get('title'));
            $course->setCredits($request->request->get('credits'));
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('course_index');
    }

    #[Route('/course/{id}/delete', name: 'course_delete', methods: ['DELETE'])]
public function delete(int $id): Response
{
    $course = $this->entityManager->getRepository(Course::class)->find($id);
    
    if ($course) {
        $this->entityManager->remove($course);
        $this->entityManager->flush();
        $this->addFlash('success', 'Course deleted successfully');
    }

    return $this->redirectToRoute('course_index');
}
}