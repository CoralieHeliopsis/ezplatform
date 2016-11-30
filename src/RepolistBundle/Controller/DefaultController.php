<?php

namespace RepolistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
//    /**
//     * @Route("/hello/{name}")
//     * @Template()
//     */
//    public function indexAction($name)
//    {
//        return array('name' => $name);
//    }




    /** Action pour lister les repos dans la vue / api github */
    /**
     * @Route("/", name="default_repoList")
     */
    public function repoListAction()
    {



//        return $this->render('full/repolist.html.twig');


        $token = 'a62cbd287b8cd973cfb06c501ff04d45567029df';

        $repositories = json_decode(file_get_contents('https://api.github.com/search/repositories?q=user:CoralieHeliopsis' . $token ), true);

        return $this->render('full/repolist.html.twig', array(
            'repositories' => $repositories,
        ));


    }
}


/** repoListAction()
 * utiliser l'api github pour accéder au compte de l'organisation heliopsis
 * utiliser le container de service pour aller chercher le service?
 *
 *
 *
 *
 *https://api.github.com/search/users?q=tom+repos:%3E42+followers:%3E1000

 *
 *
 *https://api.github.com/search/repositories?q=tetris+language:assembly&sort=stars&order=desc
 *
 * var_dump(file_get_contents('https://api.github.com/users/CoralieHeliopsis/repos'));
 * dump(file_get_contents('https://api.github.com/search/users?q=CoralieHeliopsis'));
 */