<?php

namespace App\Service;

use RuntimeException;
use Mailjet\Resources;
use App\Entity\EmailCampaign;
use App\Repository\EmailCampaignRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Exception;

class EmailCampaignService
{
    public function __construct(
        private readonly MailjetService $mailjetService,
        private readonly EntityManagerInterface $em,
        private readonly EmailCampaignRepository $emailCampaignRepository,
    ) {}

    public function createCampaign(EmailCampaign $campaign): EmailCampaign
    {
        $client = $this->mailjetService->getClient();
        $body = [
            'Locale' => 'fr_FR',
            'SenderEmail' => 'maxencedubois22@gmail.com', // $campaign->getUser()->getEmail()
            'Sender' => 2749232,
            'Title' => $campaign->getName(),
            'Subject' => $campaign->getSubject(),
            'ContactsListID' => 10504040, //  $campaign->getUser()->getContactListId()
            "EditMode" => "html2",
            'TemplateID' => 6639148,
            'IsTextPartIncluded' => true
        ];

        $response = $client->post(Resources::$Campaigndraft, ['body' => $body]);

        if(!$response->success()) {
            throw new RuntimeException('Mailjet API error: ' . $response->getReasonPhrase());
        }

        $data = $response->getData();

        $campaign->setMailjetId($data[0]['ID']);
        $campaign->setStatus($data[0]['Status']);

        $templateDetails = $this->getTemplateDetails($body['TemplateID']);
        $this->setUpCampaignDetails($templateDetails, $campaign->getMailjetId());

        return $campaign;
    }

    public function shedule(EmailCampaign $campaign, ?string $sendDate): EmailCampaign
    {
        $client = $this->mailjetService->getClient();

        $body = [
            'Date' => $sendDate === null ? 'Now' : (new \DateTime($sendDate))->format('Y-m-d\TH:i:sP'),
        ];

        $response = $client->post(Resources::$CampaigndraftSchedule, [
            'id' => $campaign->getMailjetId(),
            'body' => $body
        ]);

        if(!$response->success()) {
            throw new \RuntimeException('Mailjet API error: ' . $response->getReasonPhrase());
        }

        $campaign->setScheduledAt(new \DateTime($body['Date']));

        if (new \DateTime($body['Date']) < new \DateTime()) {
            $campaign->setStatus(2);
        } else {
            $campaign->setStatus(1);
        }

        $this->em->persist($campaign);
        $this->em->flush();

        return $campaign;
    }

    private function getTemplateDetails(int $templateId): array
    {
        $client = $this->mailjetService->getClient();

        $response = $client->get(Resources::$TemplateDetailcontent, ['id' => $templateId]);

        if(!$response->success()) {
            throw new Exception('Template not found');
        }

        return $response->getData();
    }

    private function setUpCampaignDetails(array $templateDetails, int $campaignMailjetId): void
    {
        $client = $this->mailjetService->getClient();

        $body = [
            'Headers' => $templateDetails[0]['Headers'],
            'Html-part' => $templateDetails[0]['Html-part'],
            'MJMLContent' => "",
            'Text-part' => $templateDetails[0]['Text-part']
          ];
        
        $response = $client->post(Resources::$CampaigndraftDetailcontent, ['id' => $campaignMailjetId, 'body' => $body]);

        if(!$response->success()) {
            throw new Exception("Error happened");
        }
    }
}

