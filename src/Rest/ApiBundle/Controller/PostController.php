<?php

namespace Rest\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Util\Codes;

use Symfony\Component\HttpFoundation\Request;

use Rest\ApiBundle\Entity\Post;
use Rest\ApiBundle\Form\PostType;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PostController extends FOSRestController
{

	/**
	 * Get entity instance
	 * @var integer $id Id of the entity
	 * @return Post
	 */
	protected function queryPost($id)
	{
	    $em = $this->getDoctrine()->getManager();

	    $post = $em->getRepository('RestApiBundle:Post')->find($id);

	    if (!$post) {
	        throw $this->createNotFoundException('Unable to find post for id '.$id);
	    }

	    return $post;
	}

    /**
     * List all posts.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
	 * @return array
	 *
	 * @Rest\View()
	 */
	public function allPostsAction()
	{
	    $em = $this->getDoctrine()->getManager();

	    $posts = $em->getRepository('RestApiBundle:Post')->findAll();

	    return array(
	        'posts' => $posts,
	    );
	}

	/**
	 * Get single Post.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Post for a given id",
     *   output = "Rest\ApiBundle\Entity\Post",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the page is not found"
     *   }
     * )
	 * Get action
	 * @var integer $id Id of the entity
	 * @return array
	 *
	 * @Rest\View()
	 */
	public function getPostAction($id)
	{
	    $post = $this->queryPost($id);

	    return array(
	            'post' => $post,
	            );
	}

	/**
	 * Create a Post from the submitted data.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Creates a new post from the submitted data.",
     *   input = "Rest\ApiBundle\Form\PostType",
     *   statusCodes = {
     *     201 = "Returned when post is created",
     *     400 = "Returned when the form has errors"
     *   }
     * )
     * 
	 * Collection post action
	 * @var Request $request
	 * @return View|array
	 */
	
	public function newPostAction(Request $request)
	{
	    $entity = new Post();
	    $form = $this->createForm(new PostType(), $entity);
	    $form->bind($request);

	    if ($form->isValid()) {
	        $em = $this->getDoctrine()->getManager();
	        $em->persist($entity);
	        $em->flush();

	        return $this->redirectView(
	                $this->generateUrl(
	                    'rest_get_post',
	                    array('id' => $entity->getId())
	                    ),
	                Codes::HTTP_CREATED
	                );
	    }

	    return array(
	        'form' => $form,
	    );
	}

	/**
	 * Update existing post from the submitted data or create a new post at a specific location.
     *
     * @ApiDoc(
     *   resource = true,
     *   input = "Rest\ApiBundle\Form\PostType",
     *   statusCodes = {
     *     204 = "Returned when edited successfully",
     *     400 = "Returned when the form has errors"
     *   }
     * )
	 * Put action
	 * @var Request $request
	 * @var integer $id Id of the entity
	 * @return View|array
	 */
	public function editPostAction(Request $request, $id)
	{
	    $post = $this->queryPost($id);
	    $form = $this->createForm(new PostType(), $post);
	    $form->bind($request);

	    if ($form->isValid()) {
	        $em = $this->getDoctrine()->getManager();
	        $em->persist($post);
	        $em->flush();

	        return $this->view(null, Codes::HTTP_NO_CONTENT);
	    }

	    return array(
	        'form' => $form,
	    );
	}

	/**
	 * Deletes the Post of submitted id.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete a Post for a given id",
     *   output = "Rest\ApiBundle\Entity\Post",
     *   statusCodes = {
     *     204 = "Returned when deleted successfully",
     *     404 = "Returned when the post is not found"
     *   }
     * )
	 * Delete action
	 * @var integer $id Id of the entity
	 * @return View
	 */
	public function deletePostAction($id)
	{
	    $post = $this->queryPost($id);

	    $em = $this->getDoctrine()->getManager();
	    $em->remove($post);
	    $em->flush();

	    return $this->view(null, Codes::HTTP_NO_CONTENT);
	}

}
