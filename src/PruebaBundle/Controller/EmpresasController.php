<?php

namespace PruebaBundle\Controller;

use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Proxies\__CG__\PruebaBundle\Entity\Sector;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Response;
use PruebaBundle\Entity\Empresa;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmpresasController extends Controller
{
    public function cargarEmpresasAction($message, Request $request)
    {
        $repository=$this->getDoctrine()->getRepository('PruebaBundle:Empresa');

        $searchEmpresa = new Empresa();
        $searchForm = $this->createForm('PruebaBundle\Form\EmpresaSearchType', $searchEmpresa);
        $searchForm->handleRequest($request);

        $page = ($searchForm->isSubmitted()) ? 1 : $request->get('page', 1);
        $queryBuilder=$repository->cargarSectorEmpresa($searchEmpresa->getNombre(), $searchEmpresa->getSector());
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
//        $pagerfanta->setMaxPerPage(1);
        $pagerfanta->setCurrentPage($page);

        return $this->render('PruebaBundle:Empresas:lista.html.twig', array(
            "empresas"=>$pagerfanta->getCurrentPageResults(),
            "message"=>$message,
            "pager" => $pagerfanta,
            "formbuscador"=>$searchForm->createView()
        ));
    }
    public function annadirEmpresaAction(Request $request){
        $repository=$this->getDoctrine()->getRepository('PruebaBundle:Sector');
        $sectores=$repository->comprobarSectorVacio();
        if($sectores[0][1]==0){
            return $this->redirect('/empresas/error');
        }
        return $this->formularioAction(false, $request, 'Añadir Empresa');
    }

    public function modificarEmpresaAction($id, Request $request){
        return $this->formularioAction($id, $request, 'Editar Empresa');
    }

    public function eliminarEmpresaAction($id){
        $repository = $this->getDoctrine()->getRepository('PruebaBundle:Empresa');
        $empresa=$repository->find($id);
        return $this->render('PruebaBundle:Empresas:eliminar.html.twig', array("empresa"=>$empresa));
    }

    public function eliminarEmpresaSuccessAction($id){
        $repository = $this->getDoctrine()->getRepository('PruebaBundle:Empresa');
        $empresa=$repository->find($id);
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($empresa);
        $em->flush();
        return $this->redirectToRoute('prueba_empresas', array('message'=>'eliminado_exito'));
    }

    public function formularioAction($id=false, Request $request, $titulo){
        $empresa = new Empresa();
        if($id){
            $repository = $this->getDoctrine()->getRepository('PruebaBundle:Empresa');
            $empresa=$repository->find($id);
        }
        $form = $this->createForm('PruebaBundle\Form\EmpresaType', $empresa);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $empresa = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $em = $this->getDoctrine()->getManager();
            $em->persist($empresa);
            $em->flush();

            return $this->redirect('/empresas/success');
        }

        return $this->render('PruebaBundle:Empresas:add.html.twig', array(
            'form' => $form->createView(),'titulo'=>$titulo
        ));
    }
}
