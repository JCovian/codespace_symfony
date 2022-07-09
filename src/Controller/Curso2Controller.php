<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Curso;
use App\Repository\CursoRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Curso2Controller extends AbstractController
{
    /**
     * @Route("/curso2", name="curso2")
     */
    public function index(CursoRepository $repository): Response
    {
        $cursos = $repository->findAll();

        return $this->render('curso2/index.html.twig', [
            'controller_name' => 'CursoController',
            'cursos' => $cursos,
        ]);
    }

    /**
     * @Route("/curso2/add", name="curso2_add")
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


        return new RedirectResponse('../curso2');
    }

    /**
     * @Route("/curso2/{id}", name="curso2_show", requirements={"id"="\d+"})
     */
    public function show($id, CursoRepository $repository): Response
    {
        $curso = $repository->find($id);

        return $this->render('curso2/show.html.twig', [
            'item' => $curso,
        ]);
    }

    /**
     * @Route("/curso2/filter/{idioma}", name="curso2_filter")
     */
    public function filter($idioma, CursoRepository $repository): Response
    {
        $cursos = $repository->findByIdioma($idioma);
        $curso = $repository->findByIdioma($idioma); // Encontraría el primero
        $diezCursos = $repository->findBy(['idioma' => $idioma, 'nivel' => 2], ['nombre' => 'ASC'], 10, 0);
        // SELECT * FROM Curso WHERE idioma = 'Chino' AND nivel = 2 ORDER BY nombre ASC LIMIT 10 SKIP 3
        
        return $this->render('curso2/index.html.twig', [
            'cursos' => $cursos,
        ]);
    }

    /**
     * @Route("/curso2/{id}", name="curso2_update", requirements={"id"="\d+"})
     */
    public function update($id, CursoRepository $repository, EntityManagerInterface $em): Response
    {
        $curso = $repository->find($id);

        $curso->setNivel(1);   // Actualizamos nivel
        $curso->setIdioma('Frances'); // Actualizamos idioma
        $em->persist($curso);
        $em->flush();

        return $this->render('curso2/show.html.twig', [
            'item' => $curso,
        ]);
    }

        /**
     * @Route("/curso2/{id}", name="curso2_update", requirements={"id"="\d+"})
     */
    public function delete($id, CursoRepository $repository, EntityManagerInterface $em): Response
    {
        $curso = $repository->find($id);

        $curso->setNivel(1);   // Actualizamos nivel
        $curso->setIdioma('Frances'); // Actualizamos idioma
        $em->delete($curso);
        $em->flush();

        return $this->render('curso2/show.html.twig', [
            'item' => $curso,
        ]);
    }
}
