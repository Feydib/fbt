<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class IndexController implements ControllerProviderInterface {

	private $app;

    public function index(Application $app) {
		return $app["twig"]->render("index.twig");
    }

    /**
     * This method render the contact form
     * @param \Silex\Application $app
     * @return type
     */
    public function contact(Application $app) {
        $contactForm = $app['form.factory']->create(new \App\Form\ContactType());
        return $app['twig']->render('contact.twig', array("form" => $contactForm->createView()));
    }

    public function doContact(Application $app) {
            //we get the register form
            $contactForm = $app['form.factory']->create(new \App\Form\ContactType());
            $contactForm->bind($app['request']);
            //we check if the form is valid
            if ($contactForm->isValid()){
                $datas = $contactForm->getData();

                $body = "Nom : ".$datas['lastname']."<br/>"
                        . "Prenom : ".$datas['firstname']."<br/>"
                        . "Mail : ".$datas['email']."<br/><br/>"
                        . "Message : <br/>".$datas['comments']."<br/>"
                        ;

                $message = \Swift_Message::newInstance()
                    ->setSubject('Formulaire de contact')
                    ->setFrom(array('noreply@brebion.info'))
                    ->setTo(array($app['config']['mail']['adress']))
                    ->setBody(nl2br($body), 'text/html');

                $app['mailer']->send($message);

                //add flash success
                $this->app['session']->getFlashBag()->add('success', $this->app['translator']->trans('message send'));

                return $app->redirect($app['url_generator']->generate('index.contact'));
          }
          $app['session']->getFlashBag()->add('error', $app['translator']->trans('The form contains errors'));
          return $app['twig']->render('contact.twig', array('form' => $contactForm->createView()));
        }

    /**
     * This method set a cookie with the League ID chosen by the user
     * @param \Silex\Application $app
     * @param integer $lid League ID
     * @return type
     */
    public function league (Application $app, $lid) {
		$this->app['session']->set('idleague',$lid);
		return $app["twig"]->render("index.twig", ['League' => $lid]);
    }

    public function connect(Application $app) {
    	$this->app = $app;
    	// If no id league not set in session, set it with a default value
    	if (!$app['session']->get('idleague')) {
    		// TODO get thet latest League id
    		$this->app['session']->set('idleague',2);
    	}
        // créer un nouveau controller basé sur la route par défaut
        $index = $app['controllers_factory'];
        $index->match("/", 'App\Controller\IndexController::index')->bind("index.index");
        $index->get("/league/{lid}", array($this,"league"))->bind('index.league');
        $index->get('/contact', array($this,"contact"))->bind('index.contact');
        $index->post('/docontact', array($this,"doContact"))->bind('index.docontact');
        return $index;
    }

}
