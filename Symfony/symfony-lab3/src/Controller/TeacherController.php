<?php
namespace App\Controller;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/teacher')]
class TeacherController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $queryBuilder = $em->getRepository(Teacher::class)->createQueryBuilder('t');
        
        // Фільтрація
        if ($name = $request->query->get('name')) {
            $queryBuilder->andWhere('t.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }
        
        if ($department = $request->query->get('department')) {
            $queryBuilder->andWhere('t.department LIKE :department')
                ->setParameter('department', '%'.$department.'%');
        }

        // Пагінація
        $itemsPerPage = $request->query->getInt('itemsPerPage', 10);
        $currentPage = $request->query->getInt('page', 1);
        
        $query = $queryBuilder->getQuery();
        $totalItems = count($query->getResult());
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        $query->setFirstResult(($currentPage - 1) * $itemsPerPage)
              ->setMaxResults($itemsPerPage);
        
        $teachers = $query->getResult();

        return $this->render('teacher/index.html.twig', [
            'teachers' => $teachers,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'nameFilter' => $request->query->get('name'),
            'departmentFilter' => $request->query->get('department')
        ]);
    }

    #[Route('/new', name: 'teacher_new', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]  // Доступ лише для адміністраторів
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
    #[IsGranted('ROLE_ADMIN')]  // Доступ лише для адміністраторів
    public function edit(Request $request, Teacher $teacher, EntityManagerInterface $em): Response
    {
        $teacher->setName($request->request->get('name'));
        $teacher->setDepartment($request->request->get('department'));
        $em->flush();

        return $this->redirectToRoute('teacher_index');
    }

    #[Route('/{id}/delete', name: 'teacher_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]  // Доступ лише для адміністраторів
    public function delete(Teacher $teacher, EntityManagerInterface $em): Response
    {
        $em->remove($teacher);
        $em->flush();
        return $this->redirectToRoute('teacher_index');
    }
}
