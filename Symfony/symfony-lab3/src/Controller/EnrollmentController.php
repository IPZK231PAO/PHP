<?php
namespace App\Controller;

use App\Entity\Enrollment;
use App\Entity\Student;  // Add this import
use App\Entity\Course;  // Add this import
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/enrollment')]
class EnrollmentController extends AbstractController
{
    #[Route('/', name: 'enrollment_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('enrollment/index.html.twig', [
            'enrollments' => $em->getRepository(Enrollment::class)->findAll(),
            'students' => $em->getRepository(Student::class)->findAll(),
            'courses' => $em->getRepository(Course::class)->findAll()
        ]);
    }

    #[Route('/new', name: 'enrollment_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $enrollment = new Enrollment();
        $enrollment->setStudent($em->getRepository(Student::class)->find($request->request->get('student_id')));
        $enrollment->setCourse($em->getRepository(Course::class)->find($request->request->get('course_id')));
        $enrollment->setSemester($request->request->get('semester'));

        $em->persist($enrollment);
        $em->flush();

        return $this->redirectToRoute('enrollment_index');
    }

    #[Route('/{id}/edit', name: 'enrollment_edit', methods: ['PUT'])]
    public function edit(Request $request, Enrollment $enrollment, EntityManagerInterface $em): Response
    {
        $enrollment->setStudent($em->getRepository(Student::class)->find($request->request->get('student_id')));
        $enrollment->setCourse($em->getRepository(Course::class)->find($request->request->get('course_id')));
        $enrollment->setSemester($request->request->get('semester'));
        $em->flush();

        return $this->redirectToRoute('enrollment_index');
    }

    #[Route('/{id}/delete', name: 'enrollment_delete', methods: ['DELETE'])]
    public function delete(Enrollment $enrollment, EntityManagerInterface $em): Response
    {
        $em->remove($enrollment);
        $em->flush();
        return $this->redirectToRoute('enrollment_index');
    }
}