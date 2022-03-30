<?php

namespace App\Controller\Company;

use App\Entity\Company;
use App\Form\CompanyFromType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class CompanyController extends AbstractController
{
    #[Route('/company', name: 'app_company')]
    public function index(Request $request, EntityManagerInterface $entityManager)
    {
        $company = new Company();
        $form = $this->createForm(CompanyFromType::class, $company,[
            'user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user= $this->get('security.context')->getToken()->getUser(); // get the current user
            $company->setOwner($user);
            $entityManager->persist($company);
            $entityManager->flush();
        }




        return $this->render('company/index.html.twig', [
            'companyForm' => $form->createView(),

        ]);
    }
}
