<?php
namespace  App\Controller;




use App\Entity\Category;
use App\Entity\Item;
use App\Entity\User;
use App\Entity\UserSearch;
use App\Entity\Zone;
use App\Form\CategoryType;
use App\Form\ItemType;
use App\Form\UserSearchType;
use App\Form\UserType;
use App\Form\ZoneType;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Repository\ZoneRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;


class AdminController extends  AbstractController
{

    /**
     * @var Environment
     */
    private $render;

    private $itemsRepository;

    private $zonesRepository;

    private $categoriesRepository;

    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em,UserRepository $userRepository,Environment $render,ZoneRepository $zonesRepository,CategoryRepository $categoriesRepository,ItemRepository $itemsRepository)
    {
        $this->itemsRepository = $itemsRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->zonesRepository = $zonesRepository;
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->render = $render;
    }

    public function index() {
        return $this->render("pages/admin/admin.home.html.twig");
    }


    public function addItem(Request $request) {
        $item = new Item();


        if ($request->isMethod("post")) {

            $slug = new Slugify();
            $item->setName($slug->slugify($request->request->get("item_name")));
            $this->em->persist($item);
            $this->em->flush();

            $this->addFlash('success',"Item crée avec succès");
            return $this->redirectToRoute('admin.items.show');

        }

        return $this->render("pages/admin/item/admin.create.item.html.twig",[
        ]);
    }

    public function removeItem(Item $item,Request $request) {

        if ($this->isCsrfTokenValid('remove' . $item->getId(),$request->get("_token"))) {

            $this->em->remove($item);
            $this->em->flush();
            $this->addFlash('success',"Item Supprimée Avec Succès");
            return $this->redirectToRoute('admin.home');
        }

        return $this->redirectToRoute('admin.home');
    }

    public function editItem(Item $item,Request $request) {

        $form = $this->createForm(ItemType::class,$item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = new Slugify();
            $item->setName($slug->slugify($item->getName()));
            $this->em->flush();

            $this->addFlash('success',"Item edite avec succès");
            return $this->redirectToRoute('admin.home');
        }

        return $this->render("pages/admin/item/admin.edit.item.html.twig");

    }

    public function  showItems(Request  $request,PaginatorInterface $paginator) {


        $page = $request->get('page',1);
        $items = $paginator->paginate($this->itemsRepository->findAll(),$page,10);

        return $this->render("pages/admin/item/admin.list.items.html.twig",[
            'items' => $items
        ]);
    }

    /////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////


    public function activateCompany(Request $request,User $company) {
        if ($this->isCsrfTokenValid('activate' . $company->getId(),$request->get("_token"))) {

            $company->setActivated(true);
            $this->em->flush();
            $this->addFlash('success',"Entreprise activée Avec Succès");
            return $this->redirectToRoute('admin.companies.show');
        }

        return $this->redirectToRoute('admin.home');
    }

    public function showCompanies(Request $request,PaginatorInterface $paginator) {

        $search = new UserSearch();


        $zones = $this->zonesRepository->findAll();
        $categories = $this->categoriesRepository->findAll();

        if ($request->isMethod("post")) {
            $category = $this->categoriesRepository->find($request->request->get("filtre_category"));
            if ($category != null ) {$search->setCategory($category);}

            $zone = $this->zonesRepository->find($request->request->get("filtre_zone"));
            if ($zone != null ) {$search->setZone($zone);}
        }

        $page = $request->get('page',1);
        $companies = $paginator->paginate($this->userRepository->findCompaniesBySearch($search),$page,10);

        return $this->render("pages/admin/admin.companies.show.html.twig",[
            "zones" => $zones,
            "categories" => $categories,
            'companies' => $companies
        ]);
    }

    public function editCompany(User $company,Request $request) {

        $logoName = $company->getLogoName();

        $form = $this->createForm(UserType::class,$company,["validation_groups" => "edit"]);


        $form->handleRequest($request);


        if ( $form->isSubmitted() && $form->isValid()) {
            $logoImage = $form->get('logoName')->getData();
            if ($logoImage) {
                $logoImage= $this->moveUploadedImages([$logoImage],$company);
                $company->setLogoName($logoImage[0]);
            }

            $this->em->flush();
            $this->addFlash('success',"Compte Entreprise Edité avec succees");
            return $this->redirectToRoute('admin.companies.show');
        }

        return $this->render('pages/admin/user/admin.company.edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////

    public function createZone(Request $request) {

        $zone = new Zone();
//        $form = $this->createForm(ZoneType::class,$zone);
//        $form->handleRequest($request);

        if ($request->isMethod("post")) {
            $zone->setName($request->request->get("zone_name"));

            $this->em->persist($zone);
            $this->em->flush();
            $this->addFlash('success',"Zone/Wilaya crée avec succès");

            return $this->redirectToRoute('admin.zones.show');
        }

        return $this->render("pages/admin/zone/admin.zone.create.html.twig",[
        ]);
    }

    public function editZone(Zone $zone,Request $request) {

        $form = $this->createForm(ZoneType::class,$zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success',"Zone/Wilaya edité avec succès");

            return $this->redirectToRoute('admin.home');
        }

        return $this->render("pages/admin/zone/admin.zone.edit.html.twig");
    }

    public function showZones(Request $request,PaginatorInterface $paginator) {

        $page = $request->get('page',1);
        $zones = $paginator->paginate($this->zonesRepository->findAll(),$page,10);

        return $this->render("pages/admin/zone/admin.zones.show.html.twig",[
            'zones' => $zones
        ]);
    }

    public function removeZone(Zone $zone,Request $request) {

            if ($this->isCsrfTokenValid('remove' . $zone->getId(),$request->get("_token"))) {

                $this->em->remove($zone);
                $this->em->flush();
                $this->addFlash('success',"Zone Supprimée Avec Succès");
                return $this->redirectToRoute('admin.home');
            }

            return $this->redirectToRoute('admin.home');
        }


    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////


    public function removeCategory(Category $category,Request $request) {

        if ($this->isCsrfTokenValid('remove' . $category->getId(),$request->get("_token"))) {

            $this->em->remove($category);
            $this->em->flush();
            $this->addFlash('success',"Catégorie Supprimée Avec Succès");
            return $this->redirectToRoute('admin.home');
        }

        return $this->redirectToRoute('admin.home');
    }

    public function editCategory (Category $category,Request $request) {

        $form = $this->createForm(ZoneType::class,$category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success',"Catégorie edité avec succès");

            return $this->redirectToRoute('admin.home');
        }

        return $this->render("pages/admin/category/admin.category.edit.html.twig");
    }

    public function addCategory(Request $request) {

        $category = new Category();


        if ($request->isMethod("post")) {
            $category->setName($request->request->get("category_name"));
            $this->em->persist($category);
            $this->em->flush();
            $this->addFlash('success',"Catégorie crée avec succès");
            return $this->redirectToRoute('admin.categories.show');
        }
        return $this->render("pages/admin/category/admin.category.create.html.twig",[

        ]);
    }




    public function showCategories(Request $request,PaginatorInterface $paginator) {

        $page = $request->get('page',1);
        $categories = $paginator->paginate($this->categoriesRepository->findAll(),$page,10);

        return $this->render("pages/admin/category/admin.categories.show.html.twig",[
            'categories' => $categories
        ]);
    }

    private function moveUploadedImages(Array $array,User $user): array
    {
        $slugger = new Slugify();
        $return_array = [];

        foreach ($array as $imageData) {

            $safeFilename = $slugger->slugify($user->getUsername());
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageData->guessExtension();


            try {
                $imageData->move(
                    $this->getParameter('users_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                throw  new ErrorException("Error Uploading file");
            }

            array_push($return_array,$newFilename);
            //   }
        }
        return $return_array;

    }

}





?>