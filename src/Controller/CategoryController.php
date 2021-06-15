<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProgramController
 * @Route("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * Correspond à la route /categories/ et au name "category_index"
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index():Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }
    /**
     * Add form pour Category
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {   
        //on crée un nouvel category object
        $category = new Category();
        //on crée le form associé
        $form = $this->createForm(CategoryType::class, $category);
        //Prendre la data du HTTP request
        $form->handleRequest($request);
        //le form est il soumis ?
        if ($form->isSubmitted() && $form->isValid()) {
            //s'occuper de la data et le persister et flush l'entitié et ajouter un redirect a une route pour afficher le resultat
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_index');
        }

        //Renvoie le form a un view
        return $this->render('categories/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * Getting a category by Name
     *
     * @Route("/{categoryName}", name="show")
     * @return Response
     */
    public function show(string $categoryName):Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No program with category : '.$categoryName.' found in category\'s table.'
            );
        }

        if ($category) {
            $programs = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findBy(['category' => $category], 
                         ['id' => 'DESC'], 3
                    );

            return $this->render('categories/show.html.twig', [
                'category' => $category,
                'programs' => $programs
            ]);
        }
    }
}