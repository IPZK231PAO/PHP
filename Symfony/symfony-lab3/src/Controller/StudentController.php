<?php
namespace App\Controller;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/', name: 'student_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('student/index.html.twig', [
            'students' => $em->getRepository(Student::class)->findAll()
        ]);
    }

    #[Route('/new', name: 'student_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $student = new Student();
        $student->setName($request->request->get('name'));
        $student->setEmail($request->request->get('email'));
        $student->setPhone($request->request->get('phone'));

        $em->persist($student);
        $em->flush();

        return $this->redirectToRoute('student_index');
    }

    #[Route('/{id}/edit', name: 'student_edit', methods: ['PUT'])]
    public function edit(Request $request, Student $student, EntityManagerInterface $em): Response
    {
        $student->setName($request->request->get('name'));
        $student->setEmail($request->request->get('email'));
        $student->setPhone($request->request->get('phone'));
        $em->flush();

        return $this->redirectToRoute('student_index');
    }

    #[Route('/{id}/delete', name: 'student_delete', methods: ['DELETE'])]
    public function delete(Student $student, EntityManagerInterface $em): Response
    {
        $em->remove($student);
        $em->flush();
        return $this->redirectToRoute('student_index');
    }
}