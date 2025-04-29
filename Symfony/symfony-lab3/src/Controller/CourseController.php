<?php
namespace App\Controller;

use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/course')]

class CourseController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'course_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $queryBuilder = $this->entityManager->getRepository(Course::class)->createQueryBuilder('c');
        
        if ($title = $request->query->get('title')) {
            $queryBuilder->andWhere('c.title LIKE :title')
                ->setParameter('title', '%'.$title.'%');
        }
        
        if ($credits = $request->query->get('credits')) {
            $queryBuilder->andWhere('c.credits = :credits')
                ->setParameter('credits', $credits);
        }

        $itemsPerPage = $request->query->getInt('itemsPerPage', 10);
        $currentPage = $request->query->getInt('page', 1);
        
        $query = $queryBuilder->getQuery();
        $totalItems = count($query->getResult());
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        $query->setFirstResult(($currentPage - 1) * $itemsPerPage)
              ->setMaxResults($itemsPerPage);
        
        $courses = $query->getResult();

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'titleFilter' => $request->query->get('title'),
            'creditsFilter' => $request->query->get('credits')
        ]);
    }

    #[Route('/new', name: 'course_new', methods: ['POST'])]
    public function new(Request $request): Response
    {
        $course = new Course();
        $course->setTitle($request->request->get('title'));
        $course->setCredits($request->request->get('credits'));

        $this->entityManager->persist($course);
        $this->entityManager->flush();

        return $this->redirectToRoute('course_index');
    }

    #[Route('/{id}/edit', name: 'course_edit', methods: ['PUT'])]
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

    #[Route('/{id}/delete', name: 'course_delete', methods: ['DELETE'])]
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