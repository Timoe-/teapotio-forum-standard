<?php

/**
 * Copyright (c) Thomas Potaire
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Teapot
 * @package    ForumBundle
 * @author     Thomas Potaire
 */

namespace Teapot\ForumBundle\Controller;

use Teapot\ForumBundle\Entity\Board;
use Teapot\ForumBundle\Entity\Topic;
use Teapot\ForumBundle\Entity\Message;
use Teapot\ForumBundle\Form\CreateMessageType;

class MessageController extends BaseController
{
    public function newAction($boardSlug, $topicSlug)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED') === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $board = $this->getBoard();

        $topic = $this->getTopic();

        $request = $this->get('request');

        $message = new Message();

        $user = $this->getUser();

        $message->setUser($user);

        if ($this->get('teapot.forum.access_permission')->canCreateMessage($user, $board) === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $form = $this->createForm(new CreateMessageType(), $message);

        if ($request->getMethod() === 'POST') {
            $form->bind($request);
            if ($form->isValid() === true) {
                $message->setTopic($topic);
                $this->get('teapot.forum.message')->save($message);

                /**
                 * Redirect to Topic
                 */
                return $this->redirect(
                    $this->get('teapot.forum')->forumPath('ForumListMessagesByTopic', $topic)
                    ."#". $this->get('translator')->trans('bottom')
                );
            }
        }

        return $this->render('TeapotBaseForumBundle:Message:new.html.twig', array(
            'form'          => $form->createView(),
            'currentBoard'  => $board,
            'topic'         => $topic,
            'message'       => $message,
        ));
    }

    public function editAction($boardSlug, $topicSlug, $messageId)
    {
        $message = $this->getMessage();

        $form = $this->createForm(new CreateMessageType(), $message);

        $board = $this->getBoard();

        $params = array(
            'form'          => $form->createView(),
            'currentBoard'  => $board,
            'message'       => $message
        );

        $request = $this->get('request');

        if ($request->getMethod() === 'POST') {

            $form->bind($request);

            if ($form->isValid() === true) {
                $this->get('teapot.forum.message')->save($message);

                return $this->redirect(
                    $this->get('teapot.forum')->forumPath('ForumListMessagesByTopic', $message->getTopic())
                    . '#message-' . $message->getPosition()
                );
            }
        }

        if ($this->get('request')->isXmlHttpRequest() === true) {
            return $this->renderJson(array(
                'html' => $this->renderView('TeapotBaseForumBundle:Message:raw/edit.html.twig', $params),
            ));
        }

        return $this->render('TeapotBaseForumBundle:Message:edit.html.twig', $params);
    }

    public function flagAction($boardSlug, $topicSlug, $messageId)
    {
        return $this->manipulateMessage('flag', $boardSlug, $topicSlug, $messageId);
    }

    public function deleteAction($boardSlug, $topicSlug, $messageId)
    {
        $message = $this->getMessage();
        $user = $this->getUser();

        if ($this->get('teapot.forum.access_permission')->canDelete($user, $message) === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        return $this->manipulateMessage('delete', $boardSlug, $topicSlug, $messageId);
    }

    public function starAction($boardSlug, $topicSlug, $messageId)
    {
        return $this->manipulateMessage('star', $boardSlug, $topicSlug, $messageId);
    }

    public function unstarAction($boardSlug, $topicSlug, $messageId)
    {
        return $this->manipulateMessage('unstar', $boardSlug, $topicSlug, $messageId);
    }

    private function manipulateMessage($action, $boardSlug, $topicSlug, $messageId)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        $board = $this->getBoard();
        $topic = $this->getTopic();

        if (true !== $response = $this->isUrlValid($board, $topic, $boardSlug, $topicSlug)) {
            return $response;
        }

        $message = $this->getMessage();

        switch ($action) {
            case 'delete':
                if ($message->isDeleted() === true) {
                    $this->get('teapot.forum.message')->undelete($message, true); // true: bubble up
                }
                else {
                    $this->get('teapot.forum.message')->delete($message, true); // true: bubble up
                }
                break;
            case 'flag':
                if ($message->isTopicBody() === true) {
                    $this->get('teapot.forum.flag')->flag($message->getTopic(), $this->get('security.context')->getToken()->getUser());
                }
                else {
                    $this->get('teapot.forum.flag')->flag($message, $this->get('security.context')->getToken()->getUser());
                }
                break;
            case 'star':
                    $this->get('teapot.forum.message_star')->star($message, $this->get('security.context')->getToken()->getUser());
                break;
            case 'unstar':
                    $this->get('teapot.forum.message_star')->unstar($message, $this->get('security.context')->getToken()->getUser());
                break;
        }

        if ($this->get('request')->isXmlHttpRequest() === true) {
            return $this->renderJson(array('success' => 1));
        }

        return $this->redirect(
            $this->get('request')->headers->get('referer')
        );
    }

    public function listAction($boardSlug, $topicSlug)
    {
        $board = $this->getBoard();
        $topic = $this->getTopic();
        $user = $this->getUser();

        if (true !== $response = $this->isUrlValid($board, $topic, $boardSlug, $topicSlug)) {
            return $response;
        }

        if ($this->get('teapot.forum.access_permission')->canView($user, $topic) === false) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }

        /**
         * Building the Message Form
         */
        $message = new Message();
        $message->setUser($user);
        $message->setBody($this->renderView(
            'TeapotBaseForumBundle:Modules:rules.html.twig',
            array('prefix' => $this->get('translator')->trans('Add.a.new.message'))
        ));
        $form = $this->createForm(new CreateMessageType(), $message, array('new_entry' => true))->createView();

        $messagesPerPage = $this->get('teapot.forum')->getTotalMessagesPerPage();
        $page = ($this->get('request')->get('page') === null) ? 1 : $this->get('request')->get('page');
        $offset = ($page - 1) * $messagesPerPage;

        $messages = $this->get('teapot.forum.message')->getMessagesByTopic($topic, $offset, $messagesPerPage);

        $stars = $this->get('teapot.forum.message_star')->getStarsByMessages($messages);
        $userStars = $this->get('teapot.forum.message_star')->getUserStarsByMessages($messages);
        $flags = $this->get('teapot.forum.flag')->getByMessages($messages, $board);

        foreach ($messages as $message) {
            $this->get('teapot.forum.message')->parseBody($message);
        }

        $title = $this->generateTitle('Messages.in.%title%', array('%title%' => $topic->getTitle()));

        $params = array(
            'messages_per_page' => $messagesPerPage,
            'messages'          => $messages,
            'flags'             => $flags,
            'stars'             => $stars,
            'currentBoard'      => $board,
            'topic'             => $topic,
            'message'           => $form->vars['value'],
            'form'              => $form,
            'page_title'        => $title
        );

        if ($this->get('request')->isXmlHttpRequest() === true) {
            return $this->renderJson(array(
                'html'   => $this->renderView('TeapotBaseForumBundle:Message:raw/listWithPagination.html.twig', $params),
                'title'  => $title
            ));
        }

        return $this->render('TeapotBaseForumBundle:Message:list.html.twig', $params);
    }
}
