<?php

namespace WebCrawler\InicioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\DomCrawler\Crawler;

// for the forms
use Symfony\Component\HttpFoundation\Request;

//Databases
use WebCrawler\InicioBundle\Entity\wrc_searches;

class DefaultController extends Controller
{
    //private static $url = 'https://www.linkedin.com/vsearch/f?type=all&keywords=Symfony2+Gliwice';
    private static $url_view_by = 'https://www.linkedin.com/wvmx/profile?trk=hb_ntf_VIEWED_YOUR_PROFILE';
    
    private static $search = 'php jorge';
    private static $url = 'https://www.linkedin.com/vsearch/f?type=all&keywords=php+jorge';

        /**
     * Generate the fields of the form
     * @param type $search
     * @return type
     */
    private function form($search = null){
        
        $formulario = $this->createFormBuilder($search)
            ->add('search', 'text')
            ->add('url', 'text' )    
            ->add('send', 'submit')
            ->add('reset', 'reset')
            ->getForm();
        
         return $formulario;
    }
    
    public function indexAction(Request $request)
    {
        
        $form = self::form();
        
        //El método handleRequest() detecta que el formulario no se ha enviado y por tanto, no hace nada
        //The method handleRequest() detect that the form hasn't sent and so that, it doesn't work
        $form->handleRequest($request);

        //Si no es válido, el método isValid() devuelve false otra vez, por lo que se vuelve a mostrar el formulario
        //Si solamente quieres comprobar si el formulario se ha enviado, independientemente de si es válido o no, utiliza el método isSubmitted().
        if($form->isValid()) {
            //To pick up data from form: it will arrive in one array ( $data['name'] (in the input 'name'), ...)
            //Para recoger los datos del formulario: Llegará en forma de array ( $datos['name'] (en el input 'name'), $datos['password'] (en el input 'password'), ...)
            // data is an array with parameters 'name'...
            $data = $form->getData();
//            echo '$datos = ';
//            var_dump($datos);
                              
            $search = $data['search'];
            $url = $data['url'];
            
            $html = html($url);   //I don't know how
            
            $crawler = new Crawler($html);

            foreach ($crawler as $domElement) {
                print $domElement->nodeName;
                //self::createSearch($search, $profile);  //static
                $this->createSearch($search, $profile); //no static
            }
            
        }
        
        return $this->render('WebCrawlerInicioBundle:Default:index.html.twig', array('form' => $form->createView(),));
       
    }
    
    private function createSearch( $search, $profile ){
        
        $search = new wrc_searches();   // Si no lo tuvieramos puesto arriba: \WebCrawler\InicioBundle\Entity\wrc_searches();    
        $search->setSearch($search);
        $search->setProfile($profile);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($search);
        $em->flush();
        
        return $this->redirect($this->generateUrl('todolisttasks_homepage'));
        //return new Response('Created search id '.$search->getId());
    }
    
}
