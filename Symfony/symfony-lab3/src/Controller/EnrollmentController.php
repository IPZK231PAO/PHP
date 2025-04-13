<?php
namespace App\Controller;

use App\Entity\Enrollment;
use App\Entity\Student;  
use App\Entity\Course; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/enrollment')]
class EnrollmentController extends AbstractController
{
    #[Route('/', name: 'enrollment_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $queryBuilder = $em->getRepository(Enrollment::class)->createQueryBuilder('e')
            ->leftJoin('e.student', 's')
            ->leftJoin('e.course', 'c');
   
        if ($studentId = $request->query->get('student_id')) {
            $queryBuilder->andWhere('e.student = :studentId')
                ->setParameter('studentId', $studentId);
        }
        
        if ($courseId = $request->query->get('course_id')) {
            $queryBuilder->andWhere('e.course = :courseId')
                ->setParameter('courseId', $courseId);
        }
        
        if ($semester = $request->query->get('semester')) {
            $queryBuilder->andWhere('e.semester LIKE :semester')
                ->setParameter('semester', '%'.$semester.'%');
        }

        $itemsPerPage = $request->query->getInt('itemsPerPage', 10);
        $currentPage = $request->query->getInt('page', 1);
        
        $query = $queryBuilder->getQuery();
        $totalItems = count($query->getResult());
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        $query->setFirstResult(($currentPage - 1) * $itemsPerPage)
              ->setMaxResults($itemsPerPage);
        
        $enrollments = $query->getResult();

        return $this->render('enrollment/index.html.twig', [
            'enrollments' => $enrollments,
            'students' => $em->getRepository(Student::class)->findAll(),
            'courses' => $em->getRepository(Course::class)->findAll(),
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'studentFilter' => $request->query->get('student_id'),
            'courseFilter' => $request->query->get('course_id'),
            'semesterFilter' => $request->query->get('semester')
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