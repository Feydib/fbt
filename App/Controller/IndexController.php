<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;


class IndexController implements ControllerProviderInterface {


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

                return $app->redirect($app['url_generator']->generate('index.contact'));
          } 
          $app['session']->getFlashBag()->add('error', $app['translator']->trans('The form contains errors'));
          return $app['twig']->render('contact.twig', array('form' => $contactForm->createView()));
        }

    
    public function connect(Application $app) {
        // créer un nouveau controller basé sur la route par défaut
        $index = $app['controllers_factory'];
        $index->match("/", 'App\Controller\IndexController::index')->bind("index.index");
        $index->get('/contact', array($this,"contact"))->bind('index.contact');
        $index->post('/docontact', array($this,"doContact"))->bind('index.docontact');
        return $index;
    }

}
