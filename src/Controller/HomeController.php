<?php
namespace  App\Controller;



use App\Entity\Catalog;
use App\Entity\Contact;
use App\Form\CatalogType;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;


class HomeController extends  AbstractController
{

    /**
     * @var Environment
     */
    private $render;
    /**
     * @var Security
     */
    private $security;

    public function __construct(Environment $render,Security $security)
    {
            $this->render = $render;
        $this->security = $security;
    }


    public function index(Request $request) {

        $contact = new Contact();
        $form_contact = $this->createForm(ContactType::class,$contact);



        if ($form_contact->isSubmitted() && $form_contact->isValid()) {



            /*$email = new Email();

            $render_path = $this->render->render('emails/contact.html.twig',[
                'contact' => $contact
            ]);

            $email
                ->from($contact->getEmail())
                ->to($this->container->get('contact_mail'))
                ->subject( " Contact  ")
                ->html($render_path,'utf-8');

            try {
                $mailer->send($email);
                $this->addFlash('success',"Mail envoyé avec succès");
                return $this->redirectToRoute('home', ['section' => "#contact"]);
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('error',"Erreur lors de l'envoie du mail");

            }*/

        }



        return $this->render("pages/home.html.twig",[
            "form_contact" => $form_contact->createView(),
        ]);
    }
}





?>