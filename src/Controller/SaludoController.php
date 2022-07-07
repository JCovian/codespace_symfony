<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SaludoController extends AbstractController {

    /**
     *  @Route("/hola", name="hola")
     */

    public function saludo(): Response {
        $name = "Jose";

        return new Response('<html><body>Hola, '.$name.'</body></html>');
    }

    /**
     *  @Route("/adios", name="adios")
     */

    public function despedida(): Response {
        $name = "Jose";

        return new Response('<html><body>Adiós, '.$name.'</body></html>');
    }

    //Parametrizar rutas

    /**
     *  @Route("/employees/edit/{id}", name="employees_edit", requirements={"id"="\d+"})
     */

    public function edit($id): Response {

        return new Response("<html><body>Editando empleado: $id</body></html>");
    }

    /**
     * @Route("/employees/list", name="employees_list")
     */

    public function orderedList(Request $request): Response {
       $orderBy = $request->query->get("orderby", "name");
       $page = $request->query->get("page", 1);
       //print_r($_SERVER);
       //print_r($request->server);
       
       $people = [
           ['name' => 'Carlos', 'email' => 'carlos@correo.es', 'age' => 20, 'city' => 'Benalmádena'],
           ['name' => 'Carmen', 'email' => 'carmen@correo.es', 'age' => 15, 'city' => 'Fuengirola'],
           ['name' => 'Carmelo', 'email' => 'carmelo@correo.es', 'age' => 17, 'city' => 'Sevilla'],
           ['name' => 'Carolina', 'email' => 'carolina@correo.es', 'age' => 18, 'city' => 'Málaga']
       ];
       //return new Response("<html><body>List ordered by: $orderBy, page: $page</body></html>");
       return new JsonResponse($people);
    }

        /**
     *  @Route("/employees/delete/{id}", name="employees_delete", requirements={"id"="\d+"})
     */

    public function delete(int $id): Response {

        return $this->render('employees/delete.html.twig', [
           'id' => $id, 
        ]);
    }

}