<?php

namespace App\Controller ;
use App\Entity\User ;
use App\Entity\Offer;
use App\Entity\RequestOffer;
use \DateTime ;
use App\Service\Recaptcha ;

use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\Routing\Annotation\Route ;
use Symfony\Bundle\FrameworkBundle\Controller\Controller ;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException ;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException ;
use Symfony\Component\HttpKernel\Exception\HttpException ;
use Symfony\Component\Finder\Exception\AccessDeniedException ;
/**
 * @ Route("/benevigne/")
 * la classe a le nom du fichier .php
 */
class MainController extends Controller {
    /**
     * @Route("/", name="index")
     * donner une URL & un nom
     */
    public function index() {

        
        return ($this->render("index.html.twig")) ;

    }

    /**
     * @Route("/indexVolunteer/", name="indexVolunteer")
     * donner une URL & un nom
     */
    public function indexVolunteer() {

        // dump($user->nu
        return ($this->render("indexVolunteer.html.twig")) ;
    }

    /**
     * @Route("/indexWinemaker/", name="indexWinemaker")
     * donner une URL & un nom
     */
    public function indexWinemaker() {

        // dump($user->nu
        return ($this->render("indexWinemaker.html.twig")) ;
    }

    /**
     * @Route("/indexAdmin/", name="indexAdmin")
     * donner une URL & un nom
     */
    public function indexAdmin() {

        // dump($user->nu
        return ($this->render("indexAdmin.html.twig")) ;
    }

    /**
     * @Route("/registerWinemaker/", name="registerWinemaker")
     * chemin pour trouver cette page
     */
    public function registerWinemaker() {
        
        return $this->render("registerWinemaker.html.twig") ;
    }

    /**
     * @Route("/registerVolunteer/", name="registerVolunteer")
     * chemin pour trouver cette page
     */
    public function registerVolunteer() {
        
        return $this->render("registerVolunteer.html.twig") ;
    }

    
    /**
     * @Route("/connect/", name="connect")
     */
    public function connect() {
        
        return $this->render("connect.html.twig") ;
    }

    /**
     * @Route("/disconnect/", name="disconnect")
     */
    public function disconnect() {
        
        return $this->render("disconnect.html.twig") ;

    }

    /**
     * @Route("/activate/{token}", name="activate" , requirements={"token"="[a-zA-Z\d]{32}"} )
     * chemin pour trouver cette page
     */
    public function activate($token) {
        return $this->render("activate.html.twig") ;
    }

    /**
     * @Route("/remove/{id}", name="remove" , requirements={"id"="\d{1,11}"})
     */
    public function remove($id) {
        return $this->render("remove.html.twig") ;
    }

    /**
     * @Route("/reinit/", name="reinit")
     */
    public function reinit() {

        return $this->render("reinit.html.twig") ;

    }
    /**
     * @Route("/winemakerOffers/", name="winemakerOffers")
     */
    public function winemakerOffers() {

        return $this->render("winemakerOffers.html.twig") ;

    }
    /**
     * @Route("/volunteerOffers/", name="volunteerOffers")
     */
    public function volunteerOffers() {

        return $this->render("volunteerOffers.html.twig") ;

    }
    /**
     * @Route("/adminPage/", name="adminPage")
     */
    public function adminPage() {

        return $this->render("adminPage.html.twig") ;

    }
    /**
     * @Route("/adminListWinemaker/", name="adminListWinemaker")
     */
    public function adminListWinemaker() {

        return $this->render("adminListWinemaker.html.twig") ;

    }
    /**
     * @Route("/adminListVolunteer/", name="adminListVolunteer")
     */
    public function adminListVolunteer() {

        return $this->render("adminListVolunteer.html.twig") ;

    }
    /**
     * @Route("/adminVolunteer/", name="adminVolunteer")
     */
    public function adminVolunteer() {

        return $this->render("adminVolunteer.html.twig") ;

    }
    /**
     * @Route("/adminWinemaker/", name="adminWinemaker")
     */
    public function adminWinemaker() {

        return $this->render("adminWinemaker.html.twig") ;

    }

}
