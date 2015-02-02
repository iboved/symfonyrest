<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\View as RestView;

class UserController extends Controller
{
    /**
     * @RestView
     */
    public function allAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        return array('users' => $users);
    }

    /**
     * @RestView
     */
    public function getAction($id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        if (!$user instanceof User) {
            throw $this->createNotFoundException('User not found');
        }

        return array('user' => $user);
    }

    public function newAction(Request $request)
    {
        return $this->processForm(new User(), $request);
    }

    public function editAction(User $user, Request $request)
    {
        return $this->processForm($user, $request);
    }

    private function processForm(User $user, Request $request)
    {
        $form = $this->createForm(new UserType(), $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $response = new Response();

            $response->headers->set('Location',
                $this->generateUrl(
                    'app_user_get', array('id' => $user->getId()),
                    true
                )
            );

            return $response;
        }

        return View::create($form, 400);
    }

    /**
     * @RestView(statusCode=204)
     */
    public function removeAction(User $user)
    {
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();
    }

    public function getFriendsAction(User $user)
    {
        return array('friends' => $user->getFriends());
    }
}
