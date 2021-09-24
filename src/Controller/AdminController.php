<?php
namespace  App\Controller;




use App\Entity\Catastrophe;
use App\Entity\Category;
use App\Entity\Item;
use App\Entity\Plan;
use App\Entity\User;
use App\Entity\UserSearch;
use App\Entity\Zone;
use App\Form\CatastropheType;
use App\Form\CategoryType;
use App\Form\ItemType;
use App\Form\UserSearchType;
use App\Form\UserType;
use App\Form\ZoneType;
use App\Repository\CatastropheRepository;
use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use App\Repository\PlanRepository;
use App\Repository\UserRepository;
use App\Repository\ZoneRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
    private $catastrophesRepository;
    /**
     * @var PlanRepository
     */
    private $planRepository;

    public function __construct(PlanRepository $planRepository,CatastropheRepository $catastrophesRepository,EntityManagerInterface $em,UserRepository $userRepository,Environment $render,ZoneRepository $zonesRepository,CategoryRepository $categoriesRepository,ItemRepository $itemsRepository)
    {
        $this->itemsRepository = $itemsRepository;
        $this->categoriesRepository = $categoriesRepository;
        $this->zonesRepository = $zonesRepository;
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->render = $render;
        $this->catastrophesRepository = $catastrophesRepository;
        $this->planRepository = $planRepository;
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

        if ($request->isMethod("post") ) {
            $slug = new Slugify();
            $item->setName($slug->slugify($request->request->get("item_name")));
            $this->em->flush();

            $this->addFlash('success',"Item edite avec succès");
            return $this->redirectToRoute('admin.items.show');
        }


        return $this->render("pages/admin/item/admin.edit.item.html.twig",[
            "name" => $item->getName()
        ]);

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
        //TODO EDIT Form
        //TODO EDIT Extincteur
        //TODO EDIT Catastrophe
        //TODO Removes*

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

        if ($request->isMethod("post")) {
            $name = $request->request->get("zone_name");
            $zone->setName($name);
            $this->em->flush();
            $this->addFlash('success',"Zone/Wilaya edité avec succès");

            return $this->redirectToRoute('admin.zones.show');
        }


        return $this->render("pages/admin/zone/admin.zone.edit.html.twig",[
            "name" => $zone->getName()
        ]);
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

        if ($request->isMethod("post")){
            $name = $request->request->get("category_name");
            $category->setName($name);

            $this->em->flush();
            $this->addFlash('success',"Catégorie edité avec succès");

            return $this->redirectToRoute('admin.categories.show');
        }



        return $this->render("pages/admin/category/admin.category.edit.html.twig",[
            "name" => $category->getName()
        ]);
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


    private function moveUploadedImagesCatastrophe(Array $array,Catastrophe $catastrophe): array
    {
        $slugger = new Slugify();
        $return_array = [];

        foreach ($array as $imageData) {

            $safeFilename = $slugger->slugify($catastrophe->getName());
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageData->guessExtension();


            try {
                $imageData->move(
                    $this->getParameter('catastrophes_directory'),
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





    //////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////

    public function activatePlanChoice() {

        $catastrophes = $this->catastrophesRepository->findAll();


        return $this->render("pages/admin/catastrophes/admin.catastrophe.choice.html.twig",[
            "catastrophes" => $catastrophes
        ]);
    }

    public function activatePlan(Catastrophe $catastrophe,Request $request) {



                $return_array = [];
                $array =  $this->getData($catastrophe);

                foreach ($array as $id => $val) {
                    $item = $this->itemsRepository->find($id);
                    $return_array[$item->getName()] = $val;
                }
                dump($return_array);


                return  $this->render("pages/admin/catastrophes/admin.catastrophe.activate.html.twig",[
                    "items" => $return_array,
                    "catastrophe_id" => $catastrophe->getId()
                ]);

    }

    public function activatePlanConfirmed(Catastrophe $catastrophe,Request $request) {

        if ($this->isCsrfTokenValid('activation_confirmed' . $catastrophe->getId(),$request->get("_token"))) {

            $zone = $this->getUser()->getZone();
            $plan = new Plan();
            $besoins = $this->getData($catastrophe);


            $plan
                ->setActivate(true)
                ->setZone($zone)
                ->setBesoins($besoins)
                ->setCatastrophe($this->catastrophesRepository->find($catastrophe->getId()))
                ->setDate(new \DateTime());
            $this->em->persist($plan);
            $this->em->flush();

            $this->addFlash("success", "Plan Activé et Email Envoyé à Toutes Les Entreprises ");
            return $this->redirectToRoute("admin.plans.show");
        }


    }

    private  function getData(Catastrophe $catastrophe) {

        $besoinsArray = $catastrophe->getBesoins();


        $zone = $this->zonesRepository->find($this->getUser()->getZone());
        $companies = $this->userRepository->findCompaniesByZone($zone);



        for ($i = 0; $i < count($besoinsArray) ;$i += 1) {
            $count = 0;
            $item = $besoinsArray->get($i);

            for ($j = 0; $j < count($companies); $j += 1) {

                $company = $companies[$j];
                $inventory = $company->getInventory();

                $content = $inventory->getContent();
                if (key_exists($item->getId(), $content)) {
                    $value = $content[$item->getId()];
                    $count += $value;
                }
            }
            $return_array[$item->getId()] = $count;
        }

        return $return_array;

    }



    public function addCatastrophe(Request $request) {

        $catastrophe = new Catastrophe();

        $form = $this->createForm(CatastropheType::class,$catastrophe);
        $form->add('besoins',EntityType::class,[
            'label' => "Besoins",
            'class' => Item::class,
            'choice_label' => 'name',
            'choices' => $this->itemsRepository->findAll(),
            'multiple' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logo = $form->get('logo')->getData();
            if ($logo != null) {
                $logo = $this->moveUploadedImagesCatastrophe([$logo],$catastrophe);
                $catastrophe->setLogo($logo[0]) ;
            } else {
                $catastrophe->setLogo('default.jpg');
            }

            $this->em->persist($catastrophe);
            $this->em->flush();
            $this->addFlash('success',"Catastrophe crée avec succès");
            return $this->redirectToRoute('admin.catastrophe.show');
        }

        return $this->render("pages/admin/catastrophes/admin.catastrophe.create.html.twig",[
            "form" => $form->createView(),
            "besoins" => $this->itemsRepository->findAll()
        ]);

    }


    public function showCatastrophes()
    {
            $catastrophes = $this->catastrophesRepository->findAll();


            return $this->render("pages/admin/catastrophes/admin.catastrophes.show.html.twig",[
                "catastrophes" => $catastrophes,

            ]);
    }

    public function removeCatastrophe(Catastrophe $catastrophe,Request $request)
    {
        if ($this->isCsrfTokenValid('remove' . $catastrophe->getId(),$request->get("_token"))) {
                $this->em->remove($catastrophe);
                $this->em->flush();
                $this->addFlash('success',"Catastrophe Supprimée Avec Succès");
                return $this->redirectToRoute('admin.catastrophe.show');
            }

        return $this->redirectToRoute('admin.catastrophe.show');
    }

    public function editCatastrophe(Catastrophe $catastrophe,Request $request)
    {

        $form = $this->createForm(CatastropheType::class,$catastrophe);
        $form->add('besoins',EntityType::class,[
            'label' => "Besoins",
            'class' => Item::class,
            'choice_label' => 'name',
            'choices' => $this->itemsRepository->findAll(),
            'multiple' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logo = $form->get('logo')->getData();
            if ($logo != null) {
                $logo = $this->moveUploadedImagesCatastrophe([$logo],$catastrophe);
                $catastrophe->setLogo($logo[0]) ;
            } else {
                $catastrophe->setLogo('default.jpg');
            }

            $this->em->flush();
            $this->addFlash("success","Catastrophe edité avec succès");
            return $this->redirectToRoute("admin.catastrophe.show");
        }
        return $this->render("pages/admin/catastrophes/admin.catastrophe.edit.html.twig",[
            "form" => $form->createView()
        ]);
    }

    ///////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////

    public function showPlans()
    {
            $plans = $this->planRepository->findAllByOrder();
            $besoins = [];

            forEach ($plans as $plan) {
                $array = $plan->getBesoins();
                $plan_besoins = [];
                foreach ($array as $key => $value) {
                    $name = ($this->itemsRepository->find($key))->getName();
                    $plan_besoins[$name] = $value;
                }
                array_push($besoins,$plan_besoins);


            }


            return $this->render("pages/admin/plans/admin.plans.show.html.twig",[
                "plans" => $plans,
                "besoins" => $besoins
            ]);
    }

    public function desactivatePlan(Plan $plan,Request $request)
    {
        if ($this->isCsrfTokenValid('desactivate' . $plan->getId(),$request->get("_token"))) {

           $plan->setActivate(false);
            $this->em->flush();
            $this->addFlash('success',"Plan Desactivé Avec Succès, Emails Envoyés à toutes les Entreprises");
            return $this->redirectToRoute('admin.plans.show');
        }

        return $this->redirectToRoute('admin.plans.show');
    }






}





?>