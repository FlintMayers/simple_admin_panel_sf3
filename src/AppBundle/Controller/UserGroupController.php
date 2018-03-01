<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserGroupController extends Controller
{
    /**
     * Manage conncetions between user entity and category entity
     *
     * @Route("/user-groups/{id}", name="user_group_list")
     * @Method({"GET", "POST"})
     */
    public function userGroupAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Category');

        $userCategories = $repository->findCategoriesByUserId($id);
        $categoriesUserNotIn = $repository->findCategoriesUserNotInByUserId($id);

        $user = $em->getRepository('AppBundle:User')->findOneById($id);

        return $this->render('user/user-groups.html.twig', array(
            'user'           => $user,
            'userCategories' => $userCategories,
            'categoriesUserNotIn' => $categoriesUserNotIn
        ));
    }

    /**
     * Remove connection between user entity and category entity
     *
     * @Route("/user/{userId}/category/{categoryId}", name="user_category_remove")
     * @Method("DELETE")
     */
    public function removeUserCategoryAction($userId, $categoryId)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneById($userId);
        $category = $em->getRepository('AppBundle:Category')->findOneById($categoryId);
        $user->removeCategory($category);

        $em->persist($user);
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * Add connection between user entity and category entity
     *
     * @Route("/user/{userId}/category/{categoryId}", name="user_category_add")
     * @Method("POST")
     */
    public function addUserCategoryAction($userId, $categoryId)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneById($userId);
        $category = $em->getRepository('AppBundle:Category')->findOneById($categoryId);
        $user->addCategory($category);
        $em->persist($user);
        $em->flush();

        return new Response(null, 204);
    }
}