<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;
use App\Entity\Event;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TicketController extends AbstractController
{
    /**
     * @Route("/event/{id}/ticket/create", name="create_new_ticket")
     */
    public function create($id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        return $this->render('Ticket/new.html.twig',[
            'event' => $event
        ]);
    }
    /**
     * @Route("/event/{id}/ticket/new", name="add_new_ticket")
     */
    public function store(Request $request, $id,ValidatorInterface $validator){
        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(Event::class)->find($id);
        $ticket = new Ticket();
        $ticket->setName($request->get("name"));
        $ticket->setDescription($request->get("description"));
        $ticket->setStartAt(new DateTime($request->get("start_at")));
        $ticket->setEndAt(new DateTime($request->get("end_at")));
        $ticket->setPrice(floatval($request->get("price")));
        $ticket->setEvent($event);
        $ticket->setEventId($id);
        $ticket->setNumsOfTicket(intval($request->get("nums_of_ticket")));
        $event->setNumsOfTicket($event->getNumsOfTicket() + $ticket->getNumsOfTicket());
        $errors = $validator->validate($ticket);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return $this->render('Ticket/new.html.twig',[
                "ticket" => $ticket,
                "errors" => $errors,
                "event" => $event
            ]);
        }
        $entityManager->persist($ticket);
        $entityManager->flush();
        return $this->redirectToRoute('show_event',['id' => $id]);
    }
    /**
     * @Route("/event/{id}/ticket/edit", name="edit_ticket")
     */
    public function edit($id)
    {
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($id);
        return $this->render('Ticket/edit.html.twig',[
            'ticket' => $ticket
        ]);
    }
    /**
     * @Route("/event/{id}/ticket/update", name="update_ticket")
     */
    public function update(Request $request,$id,ValidatorInterface $validator)
    {
//        dd($request);
        $entityManager = $this->getDoctrine()->getManager();
        $ticket = $entityManager->getRepository('App:Ticket')->find($id);
        $event = $ticket->getEvent();
        $ticket->setName($request->get("name"));
        $ticket->setDescription($request->get("description"));
        $ticket->setStartAt(new DateTime($request->get("start_at")));
        $ticket->setEndAt(new DateTime($request->get("end_at")));
        $ticket->setPrice(floatval($request->get("price")));
        $event->setNumsOfTicket($event->getNumsOfTicket()-$ticket->getNumsOfTicket());
        $ticket->setNumsOfTicket($request->get("nums_of_ticket"));
        $event->setNumsOfTicket($event->getNumsOfTicket()+$ticket->getNumsOfTicket());
        $errors = $validator->validate($ticket);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return $this->render('Ticket/new.html.twig',[
                "ticket" => $ticket,
                "errors" => $errors,
                "event" => $event
            ]);
        }
        $entityManager->persist($ticket);
        $entityManager->persist($event);
        $entityManager->flush();
        return $this->redirectToRoute('show_event',['id' => $event->getId()]);
    }
    /**
     * @Route("/ticket/delete/{id}" , methods={"delete"},  name="delete_ticket")
     */
    public function delete($id){
        $entityManager = $this->getDoctrine()->getManager();
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($id);
        $event = $ticket->getEvent();
        $event->setNumsOfTicket($event->getNumsOfTicket() - $ticket->getNumsOfTicket());
        $entityManager->remove($ticket);
        $entityManager->flush();
        return $this->redirectToRoute('show_event',['id' => $event->getId()]);
    }
    /**
     * @Route("/test" , methods={"GET"},  name="test")
     */
    public function test(){
        $form = $this->createForm(TicketType::class);
        return $this->render('Ticket/test.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/tests" , methods={"POST"},  name="tests")
     */
    public function test2(Request $request){
        dd($request);
    }
}
