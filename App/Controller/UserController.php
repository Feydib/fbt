<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use App\Model\Entity\Players;


class UserController implements ControllerProviderInterface {

	private $app;

    /**
     * Rout list for this controller
     * @param \Silex\Application $app
     * @return \Silex\Application
     */
    public function connect(Application $app) {
    	$this->app = $app;
        // créer un nouveau controller basé sur la route par défaut
        $user = $app['controllers_factory'];
        $user->match("/login", 'App\Controller\UserController::login')->bind("login");
        $user->get('/signup', array($this,"signup"))->bind('user.signup');
        $user->get('/reset', array($this,"resetpassword"))->bind('user.resetpassword');
        $user->post('/dosignup', array($this,"doSignUp"))->bind('user.dosignup');
        $user->post('/doUpdateAccount', array($this,"doUpdateAccount"))->bind('user.doupdateaccount');
        $user->post('/doreset', array($this,"doresetpassword"))->bind('user.doresetpassword');
        $user->get('/account', array($this,"account"))->bind('user.account');
        $user->get('/check/{salt}', array($this,"doCheckSignUp"))->bind('user.dochecksignup');

        return $user;
    }

    /**
     * This method render the registration form
     * @param \Silex\Application $app
     * @return type
     */
    public function account(Application $app) {
        $playersRepository = $app['em']->getRepository('App\Model\Entity\Players');
        $currentUser = $playersRepository->getUserByUsername($app['security']->getToken()->getUser()->getUsername());

        $registrationForm = $app['form.factory']->create(new \App\Form\RegisterType($currentUser));
        return $app['twig']->render('account.twig', array("registrationForm" => $registrationForm->createView()));
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
     * This method render the password reset form
     * @param \Silex\Application $app
     * @return type
     */
    function resetPassword(Application $app) {
    	$resetPasswordForm = $this->app['form.factory']->create(new \App\Form\ResetPasswordType());
    	return $app['twig']->render('password.twig', array("resetPasswordForm" => $resetPasswordForm->createView()));
    }


    /**
     * This method is used for update a existing user
     * @param \Silex\Application $app
     * @return type
     */
    function doUpdateAccount(Application $app) {
        $userRepository = $app['em']->getRepository('App\Model\Entity\Players');
        $currentUser = $userRepository->getUserByUsername($app['security']->getToken()->getUser()->getUsername());
        //we get the register form
        $registrationForm = $app['form.factory']->create(new \App\Form\RegisterType($currentUser));
        $registrationForm->bind($app['request']);
        //we check if the form is valid
        if ($registrationForm->isValid()){
            $datas = $registrationForm->getData();

            //username must be unique if we try to change it
            if ($currentUser->getUsername() != $datas['username'] && $userRepository->usernameExists($datas['username']) == true){
                $registrationForm->addError(new FormError($app['translator']->trans('username already exists')));
            }
            if ($currentUser->getMail() != $datas['email'] && $userRepository->emailExists($datas['email']) == true){
                $registrationForm->addError(new FormError($app['translator']->trans('email already exists')));
            }

            //if form is always valid after new verifications
            if ( $registrationForm->isValid() && $currentUser->getIdplayers() == $datas['id']){
	        if ($datas['password_repeated'] !== null && $datas['password_repeated'] != "") {
	            $currentUser->setPassword(self::encodePassword($currentUser, null,$datas['password_repeated'],$app));
	        }
                $currentUser->setUsername($datas['username']);
                $currentUser->setMail($datas['email']);
                $currentUser->setFirstname($datas['firstname']);
                $currentUser->setLastname($datas['lastname']);

                //we save the user in BDD
                $userRepository->save($currentUser);
                //add flash success
                $app['session']->getFlashBag()->add('success', $app['translator']->trans('Your account was successfully updated, please logout and login again'));
                return $app['twig']->render('account.twig', array('registrationForm' => $registrationForm->createView()));
            }
      }
      $app['session']->getFlashBag()->add('error', $app['translator']->trans('The form contains errors'));
      return $app['twig']->render('account.twig', array('registrationForm' => $registrationForm->createView()));
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
                $salt = uniqid(mt_rand());
                $user->setSalt($salt);
                $user->setPassword(self::encodePassword($user, $datas['username'],$datas['password_repeated'],$app));
                $user->setMail($datas['email']);
                $user->setFirstname($datas['firstname']);
                $user->setLastname($datas['lastname']);
                $user->setActive(false);
                $user->setRdate(new \DateTime);

                //we save the user in BDD
                $userRepository->save($user);
                //add flash success
                $app['session']->getFlashBag()->add('success', $app['translator']->trans('Your account was successfully created but need to be activated.Please check your mail'));

                /** Send a mail with the salt key for activation **/
				$url = $_SERVER['SERVER_NAME'].$this->app['url_generator']->generate('user.dochecksignup',array('salt' => $salt)); //Server URL to validate account

                $body = "Bonjour ". $datas['firstname']. " " . $datas['lastname'] . ",<br/><br/>"
    			. "Pour activer votre compte, veuillez cliquer sur le lien ci-dessous. </br>"
    			. "<a href='http://" . $url . "'>'http://" . $url . "'</a>"

				. "Rappel de vos informations :"
    			. "Pseudo : ".$datas['username']." </br>"
    			. "Password : Vous le connaissez </br>"

    			. "Ce mail est envoyé automatiquement, merci de ne pas y répondre.<br/><br/>";

    			$message = \Swift_Message::newInstance()
    			->setSubject('Activation de votre compte')
    			->setFrom(array('noreply@brebion.info' => "FBT - Admin"))
    			->setTo($datas['email'])
    			->setBody($body, 'text/html');
    			$this->app['mailer']->send($message);

                return $app->redirect($app['url_generator']->generate('index.index'));
            }
      }
      $app['session']->getFlashBag()->add('error', $app['translator']->trans('The form contains errors'));
      return $app['twig']->render('register.twig', array('registrationForm' => $registrationForm->createView()));
    }

	function doCheckSignUp (Application $app, $salt) {
		$userRepository = $app['em']->getRepository('App\Model\Entity\Players');
		$user = $userRepository->getUser(array('salt' => $salt));
		//Compare $salt with the user salt
		if ( !$user ) {
			$app['session']->getFlashBag()->add('error', $app['translator']->trans('This URL is not valid'));
            return $app->redirect($app['url_generator']->generate('index.index'));
		}
		else {
			$app['session']->getFlashBag()->add('success', $app['translator']->trans('Welcome %username%', array('%username%' =>$user->getUsername())));
			$user->setActive(true);
			$userRepository->save($user);
            return $app->redirect($app['url_generator']->generate('index.index'));
		}
	}

    function doResetPassword (Application $app) {
    	//Generate a new random password
    	$newNonEncodedPassword = '';
    	//Initialize a random desired length
    	$length = rand(8, 12);
    	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    	$newNonEncodedPassword = substr(str_shuffle($chars),0,$length);

    	//we get the register form
    	$resetPasswordForm = $app['form.factory']->create(new \App\Form\ResetPasswordType());
    	$resetPasswordForm->bind($app['request']);
    	$datas = $resetPasswordForm->getData();

    	$userRepository = $app['em']->getRepository('App\Model\Entity\Players');
    	if (!$userRepository->getUserByMail($datas['email'])) {
    		$app['session']->getFlashBag()->add('error', $app['translator']->trans('mail does not exist'));
    	}
    	else {
    		$user = $userRepository->getUserByMail($datas['email']) ;

    		//add flash success
    		$app['session']->getFlashBag()->add('success', $app['translator']->trans('Your password was successfully reset, check your mail'));

    		$user->setPassword(self::encodePassword('', '',$newNonEncodedPassword,$app));
    		//we save the password in BDD
    		$userRepository->save($user);

    		//Send a mail with new password
    		$body = "Bonjour ".$user->getFirstName(). ",<br/><br/>"
    		. "Suite à votre demande de réinitialisation, voici votre nouveau mot de passe. </br>"

    		. "Login : ".$user->getUsername()." </br>"
    		. "Password : ".$newNonEncodedPassword." </br>"

    		. "Ce mail est envoyé automatiquement, merci de ne pas y répondre.<br/><br/>";

    		$message = \Swift_Message::newInstance()
    		->setSubject('Votre nouveau mot de passe')
    		->setFrom(array('noreply@brebion.info' => "FBT - Admin"))
    		->setTo($datas['email'])
    		->setBody($body, 'text/html');
    		$this->app['mailer']->send($message);

    	}
    	return $this->app->redirect($this->app['url_generator']->generate('index.index'));
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
