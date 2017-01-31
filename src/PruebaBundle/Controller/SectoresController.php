<?php

namespace PruebaBundle\Controller;

use PruebaBundle\Entity\Sector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SectoresController extends Controller
{
    public function cargarSectoresAction($message)
    {
        $repository=$this->getDoctrine()->getRepository('PruebaBundle:Sector');
        $sectores=$repository->findAll();
        return $this->render('PruebaBundle:Sectores:lista.html.twig',array('sectores'=>$sectores,'message'=>$message));
    }

    public function annadirSectorAction(Request $request)
    {
        return $this->formularioAction(false, $request, 'Añadir Sector');
    }

    public function modificarSectorAction($id, Request $request)
    {
        return $this->formularioAction($id, $request, 'Editar Sector');
    }

    public function formularioAction($id=false, Request $request, $titulo)
    {
        $sector=new Sector();
        if($id){
            $repository = $this->getDoctrine()->getRepository('PruebaBundle:Sector');
            $sector=$repository->findOneById($id);
        }
        $form=$this->createFormBuilder($sector)
            ->add('nombre', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sector= $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            if($this->comprobarSectorRepetidoAction($sector->getNombre())){
                return $this->redirect('/sectores/error');
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($sector);
            $em->flush();

            return $this->redirect('/sectores/success');
        }
        return $this->render('PruebaBundle:Sectores:add.html.twig', array(
            'form' => $form->createView(),'titulo'=>$titulo
        ));

    }

    public function comprobarSectorRepetidoAction($nombreSector)
    {
        $repository = $this->getDoctrine()->getRepository('PruebaBundle:Sector');
        $sectorNom=$repository->findOneByNombre($nombreSector);
        if($sectorNom)
        {
            return true;
        }

        else
        {
            return false;
        }

    }

    public function eliminarSectorAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('PruebaBundle:Sector');
        $sector=$repository->findOneById($id);
        return $this->render('PruebaBundle:Sectores:eliminar.html.twig',array('sector'=>$sector));
    }
    public function eliminarSectorSuccessAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('PruebaBundle:Sector');
        $empresa=$repository->findOneById($id);
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($empresa);
        $em->flush();
        return $this->redirectToRoute('prueba_sectores', array('message'=>'eliminado_exito'));
    }

}
