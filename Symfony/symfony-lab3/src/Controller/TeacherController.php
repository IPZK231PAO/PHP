<?php
namespace App\Controller;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/teacher')]
class TeacherController extends AbstractController
{
    #[Route('/', name: 'teacher_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('teacher/index.html.twig', [
            'teachers' => $em->getRepository(Teacher::class)->findAll()
        ]);
    }

    #[Route('/new', name: 'teacher_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $teacher = new Teacher();
        $teacher->setName($request->request->get('name'));
        $teacher->setDepartment($request->request->get('department'));

        $em->persist($teacher);
        $em->flush();

        return $this->redirectToRoute('teacher_index');
    }

    #[Route('/{id}/edit', name: 'teacher_edit', methods: ['PUT'])]
    public function edit(Request $request, Teacher $teacher, EntityManagerInterface $em): Response
    {
        $teacher->setName($request->request->get('name'));
        $teacher->setDepartment($request->request->get('department'));
        $em->flush();

        return $this->redirectToRoute('teacher_index');
    }

    #[Route('/{id}/delete', name: 'teacher_delete', methods: ['DELETE'])]
    public function delete(Teacher $teacher, EntityManagerInterface $em): Response
    {
        $em->remove($teacher);
        $em->flush();
        return $this->redirectToRoute('teacher_index');
    }
}