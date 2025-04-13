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
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $queryBuilder = $em->getRepository(Student::class)->createQueryBuilder('s');
        
        if ($name = $request->query->get('name')) {
            $queryBuilder->andWhere('s.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }
        
        if ($email = $request->query->get('email')) {
            $queryBuilder->andWhere('s.email LIKE :email')
                ->setParameter('email', '%'.$email.'%');
        }

        if ($phone = $request->query->get('phone')) {
            $queryBuilder->andWhere('s.phone LIKE :phone')
                ->setParameter('phone', '%'.$phone.'%');
        }

        $itemsPerPage = $request->query->getInt('itemsPerPage', 10);
        $currentPage = $request->query->getInt('page', 1);
        
        $query = $queryBuilder->getQuery();
        $totalItems = count($query->getResult());
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        $query->setFirstResult(($currentPage - 1) * $itemsPerPage)
              ->setMaxResults($itemsPerPage);
        
        $students = $query->getResult();

        return $this->render('student/index.html.twig', [
            'students' => $students,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'nameFilter' => $request->query->get('name'),
            'emailFilter' => $request->query->get('email'),
            'phoneFilter' => $request->query->get('phone')
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
        $grades = $em->getRepository(Grade::class)->findBy(['student' => $student]);
        foreach ($grades as $grade) {
            $em->remove($grade);
        }
        
        $enrollments = $em->getRepository(Enrollment::class)->findBy(['student' => $student]);
        foreach ($enrollments as $enrollment) {
            $em->remove($enrollment);
        }
        
        $em->remove($student);
        $em->flush();
        
        return $this->redirectToRoute('student_index');
    }
}