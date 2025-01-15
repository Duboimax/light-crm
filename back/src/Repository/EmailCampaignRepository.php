<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\EmailCampaign;
use App\Service\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<EmailCampaign>
 */
class EmailCampaignRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private MailerService $mailjetService
    )
    {
        parent::__construct($registry, EmailCampaign::class);
    }
}
