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
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/grade')]
class GradeController extends AbstractController
{
    #[Route('/', name: 'grade_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $queryBuilder = $em->getRepository(Grade::class)->createQueryBuilder('g')
            ->leftJoin('g.student', 's')
            ->leftJoin('g.course', 'c');

        if ($studentId = $request->query->get('student_id')) {
            $queryBuilder->andWhere('g.student = :student')
                ->setParameter('student', $studentId);
        }
        
        if ($courseId = $request->query->get('course_id')) {
            $queryBuilder->andWhere('g.course = :course')
                ->setParameter('course', $courseId);
        }
        
        if ($score = $request->query->get('score')) {
            $queryBuilder->andWhere('g.score = :score')
                ->setParameter('score', $score);
        }

        $itemsPerPage = $request->query->getInt('itemsPerPage', 10);
        $currentPage = $request->query->getInt('page', 1);
        
        $query = $queryBuilder->getQuery();
        $totalItems = count($query->getResult());
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        $query->setFirstResult(($currentPage - 1) * $itemsPerPage)
              ->setMaxResults($itemsPerPage);
        
        $grades = $query->getResult();

        return $this->render('grade/index.html.twig', [
            'grades' => $grades,
            'students' => $em->getRepository(Student::class)->findAll(),
            'courses' => $em->getRepository(Course::class)->findAll(),
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'studentFilter' => $request->query->get('student_id'),
            'courseFilter' => $request->query->get('course_id'),
            'scoreFilter' => $request->query->get('score')
        ]);
    }

    #[Route('/new', name: 'grade_new', methods: ['PUT'])]
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

    #[Route('/{id}/edit', name: 'grade_edit', methods: ['DELETE'])]
    public function edit(Request $request, Grade $grade, EntityManagerInterface $em): Response
    {
        $grade->setScore($request->request->get('score'));
        $em->flush();

        return $this->redirectToRoute('grade_index');
    }

    #[Route('/{id}/delete', name: 'grade_delete', methods: ['POST'])]
    public function delete(Grade $grade, EntityManagerInterface $em): Response
    {
        $em->remove($grade);
        $em->flush();
        return $this->redirectToRoute('grade_index');
    }
}