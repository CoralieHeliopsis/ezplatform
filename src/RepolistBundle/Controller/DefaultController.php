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
        $content = http_build_query(array(
            'html_url' => 'html_url',
            'description' => 'description',
            'id' => 'id'
        ));

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Content-type: application/x-www-form-urlencoded\r\n',
                'user_agent'=> $_SERVER['HTTP_USER_AGENT'],
                'content' => $content
            )
        ));

        $repositories = json_decode(file_get_contents('https://api.github.com/search/repositories?q=user:CoralieHeliopsis', NULL, $context));

       var_dump(file_get_contents('https://api.github.com/search/repositories?q=user:CoralieHeliopsis', NULL, $context ));

        return $this->render('full/repolist.html.twig', array(
            'repositories' => $repositories,
        ));


    }
}