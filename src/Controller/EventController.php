<?php


namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Event;
use \DateTime;
use Twig\Extra\Intl\IntlExtension;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventController extends AbstractController
{
      /**
      * @Route("/index" ,methods={"GET"} , name="index")
      */
    public function index(){
            $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
//             var_dump($events);
//             die();
            return $this->render('Event/index.html.twig',[
                "events" => $events
            ]);

    }
    /**
          * @Route("/event/create" , methods={"GET"}, name="create_new_event")
     */
    public function create(){
            return $this->render('Event/new.html.twig');
    }
    /**
              * @Route("/event/add" , methods={"POST"},  name="add_new_event")
     */
    public function store(Request $request,ValidatorInterface $validator){
            $entityManager = $this->getDoctrine()->getManager();
            $event = new Event();
            $event->setName($request->get("name"));
            $event->setDescription($request->get("description"));
            $event->setStartAt(new DateTime($request->get("start_at")));
            $event->setEndAt(new DateTime($request->get("end_at")));
            $event->setLocation($request->get("location"));
            $event->setNumsOfTicket(0);
            if($image = $request->files->get('image')){
                    $newFilename = uniqid().'.'.$image->guessExtension();
                    $request->files->get('image')->move('event',$newFilename);
                    $event->setImageLink("$newFilename");
            }else{
                   $event->setImageLink('default.png');
            }
            $errors = $validator->validate($event);
            if (count($errors) > 0) {
                    $errorsString = (string) $errors;
                    return $this->render('Event/new.html.twig',[
                         "event" => $event,
                         "errors" => $errors
                    ]);
            }
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('index');
    }
    /**
             * @Route("/event/show/{id}" , methods={"get"},  name="show_event")
     */
    public function show($id){
             $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
//              dd($event->getTickets());
             return $this->render('Event/show.html.twig',[
                "event" => $event,
                "tickets" =>$event->getTickets()
             ]);

        }
    /**
            * @Route("/event/edit/{id}" , methods={"GET"},  name="edit_event")
     */
     public function edit($id){
//             return new Response($id);
            $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
            return $this->render('Event/edit.html.twig',[
                "event" => $event
            ]);
      }
      /**
             * @Route("/event/update/{id}" , methods={"PUT"},  name="update_event")
       */
       public function update(Request $request,$id,ValidatorInterface $validator){
           $entityManager = $this->getDoctrine()->getManager();
           $event = $entityManager->getRepository(Event::class)->find($id);
           $event->setName($request->get('name'));
           $event->setDescription($request->get('description'));
           $event->setStartAt(new DateTime($request->get("start_at")));
           $event->setEndAt(new DateTime($request->get("end_at")));
           $event->setLocation($request->get("location"));

           if($image = $request->files->get('image')){
                    $oldFileName = $event->getImageLink();
                    if($oldFileName !== "default.png"){
                        unlink('event/'.$oldFileName);
                    }
                    $newFilename = uniqid().'.'.$image->guessExtension();
                    $request->files->get('image')->move('event',$newFilename);
                    $event->setImageLink("$newFilename");
            }else{
                   $event->setImageLink('default.png');
            }
            $errors = $validator->validate($event);
            if (count($errors) > 0) {
                    $errorsString = (string) $errors;
                    return $this->render('Event/edit.html.twig',[
                         "event" => $event,
                         "errors" => $errors
                    ]);
            }
           $entityManager->persist($event);
           $entityManager->flush();
           return $this->redirectToRoute('show_event',['id' => $event->getId()]);
       }
       /**
           * @Route("/event/delete/{id}" , methods={"delete"},  name="delete_event")
       */
       public function delete($id){
            $entityManager = $this->getDoctrine()->getManager();
            $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
            $oldFileName = $event->getImageLink();
            if($oldFileName !== "default.png"){
                unlink('event/'.$oldFileName);
            }
            $entityManager->remove($event);
            $entityManager->flush();
            return $this->redirectToRoute('index');
       }
}