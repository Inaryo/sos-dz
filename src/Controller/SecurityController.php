<?php
namespace  App\Controller;




use App\Entity\Inventories;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Zone;
use App\Form\ProductType;
use App\Form\UserType;
use App\Repository\ItemRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;


class SecurityController extends  AbstractController
{

    private $encoder;
    private $em;
    /**
     * @var Security
     */
    private $security;
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository,Security $security,Environment $render,UserPasswordEncoderInterface $encoder,EntityManagerInterface $em)
    {
        $this->encoder = $encoder;
        $this->em = $em;
        $this->security = $security;
        $this->itemRepository = $itemRepository;
    }



    public function profile(Request $request) {
        $user = $this->getUser();

        if ($user != null) {
            $items = $this->itemRepository->findAll();
            $inventory = $user->getInventory();

            if ($request->isMethod('POST')) {

                $array = [];
                foreach ($items as $item) {
                    $value =  (int) $request->get($item->getName());
                    if ($value < 0) {$value = 0;}
                    $array[$item->getId()] = $value;
                }

                $inventory->setContent($array);
                $this->em->flush();
                $this->addFlash('success',"Inventaire Entreprise edité avec succès");

                return $this->redirectToRoute('user.profile');

            } else {

                $items_array = [];

                foreach ($items as $item) {
                    $items_array[$item->getName()] = 0;
                }


                $json = $inventory->getContent();
                $inventory = $json;

                foreach ($inventory as $key => $value) {
                    $item = $this->itemRepository->find($key);
                    if ($item != null) {
                        $items_array[$item->getName()] = $value;
                    }

                }

                return $this->render("pages/profile.html.twig",[
                    "items" => $items_array
                ]);
            }


        }
        $this->addFlash('success',"Compte Entreprise non-activé");
        return $this->redirectToRoute('user.profile');
    }




    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function createChoice(Request $request) {
        return $this->render('pages/create_user_choice.html.twig');
    }

    public function createCompany(Request $request) {

        $company = new User();
        $form = $this->createForm(UserType::class,$company);
        $form->add('password',PasswordType::class,[
            "label" => "Mot De Passe"
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            $logoImage = $form->get('logoName')->getData();
            if ($logoImage != null) {
                $logoImage = $this->moveUploadedImages([$logoImage],$company);
                $company->setLogoName($logoImage[0]) ;
            } else {
                $company->setLogoName('default.png');
            }


            //TODO GeoLocalisation
            $company->setLatitude(0);
            $company->setLongitude(0);
            $company->setActivated(false);
            $company->setRoles(["ROLE_COMPANY_DEACTIVATED"]);

            $inventory = new Inventories();
            $inventory->setCompanyName($company);
            $inventory->setContent([]);
            $this->em->persist($inventory);


            $company->setInventory($inventory);

            $company
                ->setPassword($this->encoder->encodePassword($company,$company->getPassword()));

            $this->em->persist($company);
            $this->em->flush();
            $this->addFlash('success',"Compte Entreprise crée avec succès");

            return $this->redirectToRoute('home');

        }
        return $this->render('pages/create_company.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function removeCompany(User $company,Request $request) {

        if ($this->isCsrfTokenValid('remove' . $company->getId(),$request->get("_token"))) {

            $this->em->remove($company->getInventory());
            $this->em->remove($company);
            $this->em->flush();
            $this->addFlash('success',"Entreprise Supprimée Avec Succès");
            return $this->redirectToRoute('admin.home');
        }

        return $this->redirectToRoute('admin.companies.show');
    }

    public function  logout() {
        throw new \Exception('this should not be reached!');
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