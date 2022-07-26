<?php

namespace App\Controller;

use App\Entity\Curso;
use App\Form\CursoType;
use App\Repository\CursoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/api/curso")
 */
class ApiCursoController extends AbstractController
{
    /**
     * @Route("/", name="api_curso_index", methods={"GET"})
     */
    public function index(CursoRepository $cursoRepository): Response
    {
        $cursos = $cursoRepository->findAll();
        $cursosArray = [];

        foreach($cursos as $curso){
            
            $cursosArray[] = $this->serializeCurso($curso);
        }
        return new JsonResponse($cursosArray);
    }

    private function serializeCurso(Curso $curso) {
        $alumnosArray=[];

        $alumnos=$curso->getAlumnos();   // Array de entidades alumno
        foreach($alumnos as $alumno) {
            $alumnoArray=[
                'id'=>$alumno->getId(),
                'nombre'=>$alumno->getName(),
                'email'=>$alumno->getEmail(),
            ];
            $alumnosArray[] = $alumnoArray;
        }
        $cursoArray = [
            'id'=>$curso->getId(),
            'nombre'=>$curso->getNombre(),
            'idioma'=>$curso->getIdioma(),
            'nivel'=>$curso->getNivel(),
            'alumnos'=>$alumnosArray,
        ];
        return $cursoArray;
    }

    /**
     * @Route("/new", name="api_curso_new", methods={"POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $bodyRequest = $request->getContent();
        $cursoArray = json_decode($bodyRequest);

        $curso = new Curso();
        $curso->setNombre($cursoArray->nombre);
        $curso->setIdioma($cursoArray->idioma);
        $curso->setNivel($cursoArray->nivel);

        $em->persist($curso);
        $em->flush();

        $respuesta = [
            'id'=>$curso->getId()
        ];

        return new JsonResponse([$respuesta]);
    }

    /**
     * @Route("/{id}", name="api_curso_show", methods={"GET"})
     */
    public function show($id, CursoRepository $cursoRepository): Response
    {
        $curso = $cursoRepository->find($id);

        if($curso === null) {
            throw $this->createNotFoundException('El curso no existe');
        }

        $cursosArray = $this->serializeCurso($curso);

        return new JsonResponse($cursosArray); // array_push($alumnosArray, $alumnoArray)

    }

    /**
     * @Route("/{id}", name="api_curso_edit", methods={"PUT"})
    */
    public function edit($id, Request $request, CursoRepository $cursoRepository, EntityManagerInterface $em): Response
    {

        $bodyRequest = $request->getContent();
        $cursoArray = json_decode($bodyRequest);

        $curso = $cursoRepository->find($id);
        $curso->setNombre($cursoArray->nombre);
        $curso->setIdioma($cursoArray->idioma);
        $curso->setNivel($cursoArray->nivel);

        $em->persist($curso);
        $em->flush();

        $respuesta = [
            'id'=>$curso->getId()
        ];
        return new JsonResponse($respuesta);
    }

    /**
     * @Route("/{id}", name="api_curso_delete", methods={"DELETE"})
     */
    public function delete($id, Request $request, CursoRepository $cursoRepository, EntityManagerInterface $em): Response
    {
        $curso = $cursoRepository->find($id);

        $em->remove($curso);
        $em->flush();

        $respuesta = [
            'mensaje'=> 'Curso borrado'
        ];
        return new JsonResponse($respuesta);
    }
}
