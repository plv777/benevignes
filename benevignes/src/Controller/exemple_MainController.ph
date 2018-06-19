<?php

namespace App\Controller ;
use App\Entity\User ;
use App\Entity\Connect;
use \DateTime ;
use App\Service\Recaptcha ;
// use App\Service\UserInfos ;

use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\Routing\Annotation\Route ;
use Symfony\Bundle\FrameworkBundle\Controller\Controller ;
// pour créer erreur 403 :
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException ;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException ;
use Symfony\Component\HttpKernel\Exception\HttpException ;
use Symfony\Component\Finder\Exception\AccessDeniedException ;
/**
 * @ Route("/maincontroller")
 * rajoute le préfixe /maincontroller devant toutes les routes
 * de la classe MainController
 * 
 * la classe a le nom du fichier .php
 */
class MainController extends Controller {
    /**
     * @Route("/", name="accueil")
     * donner une URL & un nom
     */
    public function accueil() {

        // dump($user->number()) ;
        $this->get("session")->remove("account") ;

        return ($this->render("accueil.html.twig")) ;
    }

    /**
     * @Route("/contact/", name="contact")
     * chemin pour trouver cette page
     */
    public function contact() {
        
        return $this->render("contact.html.twig") ;
    }

    /**
     * @Route("/register/", name="register")
     */
    public function register(Request $request, Recaptcha $recap, \Swift_Mailer $mailer) {

        if ($this->get("session")->has("account")) {
            throw new AccessDeniedHttpException("Vous êtes connecté !") ;
        }

        // pour récupérer le paramètre défini dans le fichier config\services.yaml
        $token = $this->getParameter("google_private_token") ;

        $nameUser = $request->request->get("name") ?? null ;
        $emailUser = $request->request->get("email") ?? null ;
        $passwordUser = $request->request->get("password") ?? null ;
        $password1User = $request->request->get("password1") ?? null ;
        $recaptchaUser = $request->request->get("g-recaptcha-response") ;
        
        if (isset($nameUser) && isset($emailUser) && isset($passwordUser) 
            && isset($password1User) && isset($recaptchaUser)) {

            if (!preg_match("#^[a-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ'\-\s ]{3,60}$#i",$nameUser)) {
                $msgErrors[] = "Le nom n'est pas valide (3 à 60 caractères) !" ;
            }
            if (!filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
                $msgErrors[] = "Le courriel n'est pas valide !" ;
            }
            if (!preg_match("#^[a-z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ'*ù!:;,&_-]{3,60}$#i",$passwordUser)) {
                $msgErrors[] = "Le mot de passe n'est pas valide (3 à 60 caractères) !" ;
            }
            if ($passwordUser != $password1User) {
                $msgErrors[] = "Les mots de passe ne correspondent pas !" ;
            }
            if (!$recap->isValid($recaptchaUser, $request->server->get("REMOTE_ADDR"))) {
                $msgErrors[] = "Le captcha est invalide !" ;
            }
            if (!isset($msgErrors)) {
                // dump($request->request) ;

                // vérification de l'existence du courriel dans la BdD
                $user = new User() ;
                $userRepository = $this->getDoctrine()->getRepository(User::class) ;
                $userMailCheck = $userRepository->findOneByEmail($emailUser) ;
                // dump($userMailCheck) ;
                // si le courriel n'existe pas dans la BdD :
                if (empty($userMailCheck)) {
                    // hydratation de l'objet $user avec les infos fournies dans le formulaire
                    $user->setName($nameUser) ;
                    $user->setEmail($emailUser) ;
                    $user->setRegisterDate(new DateTime()) ;
                    $user->setPassword(password_hash($passwordUser,PASSWORD_BCRYPT)) ;
                    // dump(md5(random_bytes(10))) ;
                    $user->setToken(md5(random_bytes(10))) ;
                    $user->setActivation(false) ;
                    // enregistrement dans la BdD
                    $entityManager = $this->getDoctrine()->getManager() ;
                    $entityManager->persist($user) ;
                    $entityManager->flush() ;
                    // pour envoyer un courriel
                    $message = (new \Swift_Message("Activation de compte utilisateur"))
                        // ->setFrom("adresse de l'expéditeur")
                        ->setFrom("no-reply@gmail.com")
                        // ->setTo("adresse du destinataire")
                        ->setTo($emailUser)
                        ->setBody($this->renderView("mail/activation.html.twig", 
                            array(
                                "token" => $user->getToken(),
                                "name" => $nameUser)),"text/html")
                        ->addPart($this->renderView("mail/activation.txt.twig",
                            array(
                                "token" => $user->getToken(),
                                "name" => $nameUser)),"text/plain")
                    ;
                    $mailer->send($message) ;

                    $msgSuccess[] = "Vous allez recevoir un courriel, merci de cliquer sur le lien pour vous activer !" ;
                    $msgSuccess[] = "Vous avez bien été enregistré(e) !" ;
                }
                else {
                    $msgErrors[] = "Le courriel " . $emailUser . " existe déjà !" ;
                    // dump($msgErrors) ;
                }
            }
        }

        if (isset($msgErrors)) {
            return $this->render("register.html.twig", array("errors" => $msgErrors)) ;
        }
        
        if (isset($msgSuccess)) {
            return $this->render("register.html.twig", array("success" => $msgSuccess)) ;
        }

        return $this->render("register.html.twig") ;
    }
    
    /**
     * @Route("/connect/", name="connect")
     */
    public function connect(Request $request) {
        
        if ($this->get("session")->has("account")) {
            throw new AccessDeniedHttpException("Vous n'êtes pas connecté(e) !") ;
        }
        $maxTries = $this->getParameter("number_tries") ;
        $maxSeconds = $this->getParameter("number_seconds") ;

        $testRepository = $this->getDoctrine()->getRepository(Connect::class) ;
        $test = $testRepository->findOneBy(array(
            'ip_address' => $request->server->get("REMOTE_ADDR")
        )) ;
        if (empty($test)) {
            $test = new Connect ;
            $test->setFirstDate(new DateTime()) ;
            $test->setTries(0) ;
            // dump($request->server->get("REMOTE_ADDR")) ;
            $test->setIpAddress($request->server->get("REMOTE_ADDR")) ;
            // enregistrement dans la BdD
            $entityManager = $this->getDoctrine()->getManager() ;
            $entityManager->persist($test) ;
            $entityManager->flush() ;
        }
        $numberTries = $test->getTries() ;
        // dump($numberTries) ;

        $emailUser = $request->request->get("email") ?? null ;
        $passwordUser = $request->request->get("password") ?? null ;

        // pour le blocage
        if ($numberTries > $maxTries) {
            $currentTime = new DateTime() ;
            // dump($currentTime->format("U")) ;
            // dump($test->getFirstDate()->format("U")) ;
            // dump($currentTime->format("U") - $test->getFirstDate()->format("U")) ;
            if (($currentTime->format("U") - $test->getFirstDate()->format("U")) < $maxSeconds) {
                dump($test->getFirstDate()->format("U")) ;
                $msgErrors[] = "Vous avez atteint le nombre maximal de tentatives de connexion !" ;
                $msgErrors[] = "Veuillez patienter ..." ;
            }
            else {
                $entityManager = $this->getDoctrine()->getManager() ;
                $entityManager->remove($test) ;
                $entityManager->flush() ;
            }
        }

        if (isset($emailUser) && isset($passwordUser) && ($numberTries < $maxTries)) {
            if (!filter_var($emailUser, FILTER_VALIDATE_EMAIL)) {
                $msgErrors[] = "Le courriel n'est pas valide !" ;
                $numberTries = 1 ;
            }
            if (!preg_match("#^[a-z0-9áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ'*ù!:;,&_-]{3,60}$#i",$passwordUser)) {
                $msgErrors[] = "Le mot de passe n'est pas valide (3 à 60 caractères) !" ;
                $numberTries = 1 ;
            }
            // si pas d'erreurs de frappe/syntaxe
            if (!isset($msgErrors)) {
                // recherche de l'utilisateur selon son courriel (unique dans la base)
                $userRepository = $this->getDoctrine()->getRepository(User::class) ;
                $userCheck = $userRepository->findOneByEmail($emailUser) ;
                // dump($userCheck) ;
                // si le courriel existe dans la BdD:
                if (!empty($userCheck)) {
                    // vérification de son mot de passe
                    if (password_verify($passwordUser, $userCheck->getPassword()))  {
                        if ($userCheck->getActivation() == true) {
                            // l'utilisateur existe bien dans la BdD
                            $session = $this->get("session") ;
                            // dump($session) ;
                            // ajout des infos de l'utilisateur dans la session, paramètre account
                            $session->set("account", $userCheck) ;

                            // dump($userCheck) ;
                            // dump($session->get("account")) ;
                            // dump($this->get("session")) ;
                            $msgSuccess[] = "Vous êtes bien connecté, merci à Dounia !" ;
                            $numberTries = 0 ;
                        }
                        else {
                            $msgErrors[] = "Il faut d'abord vous activer !" ;
                            $numberTries = 1 ;
                        }
                    }
                    else {
                        $msgErrors[] = "Le mot de passe n'est pas valide !" ;
                        $numberTries = 1 ;
                        // dump($numberTries) ;
                    }
                }
                else {
                    $msgErrors[] = "Le courriel " . $emailUser . " n'existe pas !" ;
                    $numberTries = 1 ;
                }
            }    
        }
        if ($numberTries) {
            $numberTries = $test->getTries() + 1 ;
            $test->setTries($numberTries) ;
            $entityManager = $this->getDoctrine()->getManager() ;
            $entityManager->persist($test) ;
            $entityManager->flush() ;
            // dump($test->getTries()) ;
        }
        // si erreurs, affichage 
        if (isset($msgErrors)) {
            return $this->render("connect.html.twig", array("errors" => $msgErrors)) ;
        }
        if (isset($msgSuccess)) {
            return $this->render("connect.html.twig", array("success" => $msgSuccess)) ;
        }

        return $this->render("connect.html.twig") ;
    }    
    /**
     * @Route("/disconnect/", name="disconnect")
     * chemin pour trouver cette page
     */
    public function disconnect() {
        if (!$this->get("session")->has("account")) {
            throw new AccessDeniedHttpException("Vous n'êtes pas connecté(e) !") ;
        }
        // suppression de la session de l'utilisateur
        $this->get("session")->remove("account") ; 
        
        return $this->render("disconnect.html.twig") ;

    }
    /**
     * @Route("/profile/", name="profile")
     * chemin pour trouver cette page
     */
    public function profile() {
        if (!$this->get("session")->has("account")) {
            throw new AccessDeniedHttpException("Vous n'êtes pas connecté(e) !") ;
        }
        // dump($this->get("session")->get('account')) ;
        // en paramètre, les infos de l'utilisateur connecté
        return $this->render("profile.html.twig", array("profileUser" => $this->get("session")->get('account'))) ;
    }
    /**
     * @Route("/activate/{myToken}/", name="activate", requirements = {"myToken"="[0-9a-zA-Z]{32}"})
     */
    public function activate($myToken) {
        if ($this->get("session")->has("account")) {
            throw new AccessDeniedHttpException("Vous êtes connecté(e) !") ;
        }
        dump($myToken) ;
        $userRepository = $this->getDoctrine()->getRepository(User::class) ;
        $userCheck = $userRepository->findOneByToken($myToken) ;
        // si le token existe dans la BdD:
        if (!empty($userCheck)) {
            if ($userCheck->getActivation() == false) {
                $userCheck->setActivation(true) ;
                // enregistrement dans la BdD
                $entityManager = $this->getDoctrine()->getManager() ;
                $entityManager->persist($userCheck) ;
                $entityManager->flush() ;
                $msgSuccess[] = "Vous êtes bien activé !" ;
                $msgSuccess[] = "Vous pouvez maintenant vous connecter ..." ;
            }
            else {
                $msgErrors[] = "Vous êtes déjà activé(e) !" ;
            }
        }
        else {
            throw new AccessDeniedException("Mauvais token !") ;
        }
        // si erreurs, affichage 
        if (isset($msgErrors)) {
            return $this->render("activate.html.twig", array("errors" => $msgErrors)) ;
        }
        if (isset($msgSuccess)) {
            return $this->render("activate.html.twig", array("success" => $msgSuccess)) ;
        }
    }
    /**
     * @Route("/reinit/", name="reinit")
     * chemin pour trouver cette page
     */
    public function reinit(Request $request) {
        if ($this->get("session")->has("account")) {
            throw new AccessDeniedHttpException("Vous êtes connecté(e) !") ;
        }

        $token = md5(random_bytes(10)) ;

        $userRepository = $this->getDoctrine()->getRepository(User::class) ;
        $userCheck = $userRepository->findOneByEmail($request->request->get("email")) ;


        $message = (new \Swift_Message("Activation de compte utilisateur"))
        // ->setFrom("adresse de l'expéditeur")
        ->setFrom("no-reply@gmail.com")
        // ->setTo("adresse du destinataire")
        ->setTo($request->request->get("email"))
        ->setBody($this->renderView("mail/reinit.html.twig", 
            array(
                "token" => $token,
                "id" => $userCheck)),"text/html")
        ->addPart($this->renderView("mail/reinit.txt.twig",
            array(
                "token" => $token,
                "id" => $userCheck)),"text/plain")
        ;
        $mailer->send($message) ;

        $msgSuccess[] = "Vous allez recevoir un courriel, merci de cliquer sur le lien pour ré-initialiser votre MdP !" ;
        $msgSuccess[] = "Vous avez bien été enregistré(e) !" ;



        return $this->render("reinit.html.twig", array("success" => $msgSuccess)) ;

    }
/**
     * @Route("/reinitForm/", name="reinitform")
     * chemin pour trouver cette page
     */
    public function reinitForm(Request $request) {
        if ($this->get("session")->has("account")) {
            throw new AccessDeniedHttpException("Vous êtes connecté(e) !") ;
        }

        



        return $this->render("reinitform.html.twig") ;

    }


}
