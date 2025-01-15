<?php

namespace App\Controller;

use App\Entity\EmailCampaign;
use App\Form\EmailCampaignType;
use App\Service\EmailCampaignService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EmailCampaignRepository;
use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailCampaignController extends AbstractController {

    #[Route('/email-campaign/list', name: 'app_email_campaign', methods: ['GET'])]
    public function list(EmailCampaignRepository $emailCampaignRepository): Response
    {
        $user = $this->getUser();

        if(!$user) {
            return $this->json(['message' => 'USer not found', Response::HTTP_BAD_REQUEST]);
        }

        $campaigns = $emailCampaignRepository->findBy(['user' => $user]);

        return $this->json($campaigns, Response::HTTP_OK, context: ['groups' => 'emailcampaign:read']);
    }


    #[Route('/email-campaign', name: 'email_campaigns_create', methods: ['POST'])]
    public function create(Request $request, EmailCampaignService $emailCampaignService, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if(!$user) {
            return $this->json(['message' => 'User not found'], Response::HTTP_BAD_REQUEST);
        }

        $form = $this->createForm(EmailCampaignType::class, new EmailCampaign());
        $form->submit($request->toArray());

        if($form->isSubmitted() && !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var EmailCampaign $campaign */
        $campaign = $form->getData();
        $campaign->setUser($user);

        $campaign = $emailCampaignService->createCampaign($campaign);

        $em->persist($campaign);
        $em->flush();

        return $this->json($campaign, Response::HTTP_CREATED, context: ['groups' => 'emailcampaign:read']);

    }

    #[Route('/email-campaign/{id}/shedule', name: 'email_campaigns_schedule', methods: ['POST'])]
    public function shedule(Request $request, EmailCampaign $campaign, EmailCampaignService $emailCampaignService): Response
    {
        $data = $request->toArray();

        $campaign  = $emailCampaignService->shedule($campaign, $data['sendDate'] ?? null);

        return $this->json($campaign, Response::HTTP_OK, context: ['groups' => 'emailcampaign:read']);

    }
}
