<?php
namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Student;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/grade')]
class GradeController extends AbstractController
{
    #[Route('/', name: 'grade_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('grade/index.html.twig', [
            'grades' => $em->getRepository(Grade::class)->findAll(),
            'students' => $em->getRepository(Student::class)->findAll(),
            'courses' => $em->getRepository(Course::class)->findAll()
        ]);
    }

    #[Route('/new', name: 'grade_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $grade = new Grade();
        $grade->setStudent($em->getRepository(Student::class)->find($request->request->get('student_id')));
        $grade->setCourse($em->getRepository(Course::class)->find($request->request->get('course_id')));
        $grade->setScore($request->request->get('score'));

        $em->persist($grade);
        $em->flush();

        return $this->redirectToRoute('grade_index');
    }

    #[Route('/{id}/edit', name: 'grade_edit', methods: ['PUT'])]
    public function edit(Request $request, Grade $grade, EntityManagerInterface $em): Response
    {
        $grade->setScore($request->request->get('score'));
        $em->flush();

        return $this->redirectToRoute('grade_index');
    }

    #[Route('/{id}/delete', name: 'grade_delete', methods: ['DELETE'])]
    public function delete(Grade $grade, EntityManagerInterface $em): Response
    {
        $em->remove($grade);
        $em->flush();
        return $this->redirectToRoute('grade_index');
    }
}