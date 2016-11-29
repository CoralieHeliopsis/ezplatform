<?php

namespace RepolistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    /** Action pour lister les repos dans la vue / api github */
    /**
     * @Route("/", name="default_repoList")
     */
    public function repoListAction()
    {
        return $this->render('full/repolist.html.twig');


    }
}


/** repoListAction()
 * utiliser l'api github pour accéder au compte de l'organisation heliopsis
 * utiliser le container de service pour aller chercher le service?
 *
 *
 *
 *
 *
 *
 *
 *
 *
 * var_dump(file_get_contents('https://api.github.com/users/CoralieHeliopsis/repos'));
 */