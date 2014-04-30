<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use App\Model\Entity\Players;


class UserController implements ControllerProviderInterface {
    
    
    /**
     * Rout list for this controller
     * @param \Silex\Application $app
     * @return \Silex\Application
     */
    public function connect(Application $app) {
        // créer un nouveau controller basé sur la route par défaut
        $user = $app['controllers_factory'];
        $user->match("/login", 'App\Controller\UserController::login')->bind("login");
        $user->get('/signup', array($this,"signup"))->bind('user.signup');
        $user->post('/dosignup', array($this,"doSignUp"))->bind('user.dosignup');

        return $user;
    }

    /**
     * This method render the lgogin form
     * @param \Silex\Application $app
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return type
     */
   public function login(Application $app, Request $request) {
        return $app['twig']->render('login.twig', array(
                    'error' => $app['security.last_error']($request),
                    'last_username' => $app['session']->get('_security.last_username'),
                ));
    }
    

    /**
     * This method render the registration form
     * @param \Silex\Application $app
     * @return type
     */
    function signUp(Application $app) {
        $registrationForm = $app['form.factory']->create(new \App\Form\RegisterType());
        return $app['twig']->render('register.twig', array("registrationForm" => $registrationForm->createView()));
    }

    /**
     * This method is used for register a new user 
     * @param \Silex\Application $app
     * @return type
     */
    function doSignUp(Application $app) {
        //we get the register form
        $registrationForm = $app['form.factory']->create(new \App\Form\RegisterType());
        $registrationForm->bind($app['request']);
        //we check if the form is valid
        if ($registrationForm->isValid()){
            $datas = $registrationForm->getData();
            $userRepository = $app['em']->getRepository('App\Model\Entity\Players');
            //username must be unique
            if ($userRepository->usernameExists($datas['username']) == true){
                 $registrationForm->addError(new FormError($app['translator']->trans('username already exists')));
            }
            //email must be unique
            if ($userRepository->emailExists($datas['email']) == true){
                $registrationForm->addError(new FormError($app['translator']->trans('email already exists')));
            }
            //if form is always valid after new verifications
            if ( $registrationForm->isValid() ){
                $user = new Players();
                $user->setUsername($datas['username']);
                $user->setRole("ROLE_USER");
                $user->setSalt(uniqid(mt_rand()));
                $user->setPassword(self::encodePassword($user, $datas['username'],$datas['password_repeated'],$app));
                $user->setMail($datas['email']);
                $user->setFirstname($datas['firstname']);
                $user->setLastname($datas['lastname']);
                $user->setActive(true);
                $user->setRdate(new \DateTime);

                //we save the user in BDD
                $userRepository->save($user);
                //add flash success
                $app['session']->getFlashBag()->add('success', $app['translator']->trans('Your account was successfully created, please login'));
                return $app->redirect($app['url_generator']->generate('index.index'));
            }  
      } 
      $app['session']->getFlashBag()->add('error', $app['translator']->trans('The form contains errors'));
      return $app['twig']->render('register.twig', array('registrationForm' => $registrationForm->createView()));
    }

    /**
     * Encode a password
     * @return string
     */
    static function encodePassword($user, $username, $nonEncodedPassword,$app){
      //$user = new  AdvancedUser($username, $nonEncodedPassword);
      //$encoder = $app['security.encoder.digest']->getEncoder($user);
      $encodedPassword = $app['security.encoder.digest']->encodePassword($nonEncodedPassword, "");
      return $encodedPassword;
    }

}
