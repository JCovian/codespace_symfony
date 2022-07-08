<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Curso;
use App\Repository\CursoRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CursoController extends AbstractController
{
    /**
     * @Route("/curso", name="curso")
     */
    public function index(CursoRepository $repository): Response
    {
        $cursos = $repository->findAll();

        return $this->render('curso/index.html.twig', [
            'controller_name' => 'CursoController',
            'cursos' => $cursos,
        ]);
    }

    /**
     * @Route("/curso/add", name="curso_add")
     */
    public function add(EntityManagerInterface $em): Response
    {
        $curso = new Curso();
        $curso->setNombre('Curso de inglés');
        $curso->setIdioma('Inglés');
        $curso->setNivel(1);

        $curso2 = new Curso();
        $curso2->setNombre('Curso de Chino');
        $curso2->setIdioma('Chino');
        $curso2->setNivel(1);

        $alumno = new \App\Entity\Alumno();
        $alumno->setName('Hugo');
        $alumno->setEmail('hugo@correo.es');

        $curso->addAlumno($alumno);

        $em->persist($curso);
        $em->persist($curso2);
        $em->persist($alumno);

        $em->flush();


        return new RedirectResponse('../curso');
    }

    /**
     * @Route("/curso/{id}", name="curso_show")
     */
    public function show($id, CursoRepository $repository): Response
    {
        $curso = $repository->find($id);

        return $this->render('curso/show.html.twig', [
            'item' => $curso,
        ]);
    }

    /**
     * @Route("/curso/filter/{idioma}", name="curso_filter")
     */
    public function filter($idioma, CursoRepository $repository): Response
    {
        $curso = $repository->findByIdioma($idioma);

        return $this->render('curso/index.html.twig', [
            'cursos' => $curso,
        ]);
    }
}
