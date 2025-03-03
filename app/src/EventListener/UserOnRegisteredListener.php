<?php

namespace App\EventListener;

use App\Event\UserOnRegisteredEvent;
use App\Service\KafkaService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserOnRegisteredListener
{
    public function __construct(
        readonly private LoggerInterface $logger,
        readonly private KafkaService $kafkaService,
        readonly private ParameterBagInterface $parameterBag
    ) {
        // Do nothing!
    }

    public function __invoke(UserOnRegisteredEvent $event): void
    {
        $user = $event->getUser();

        $this->logger->info('User registered: ' . $user->getEmail());

        $this->kafkaService->producer()->produce($this->parameterBag->get('kafka.topic.users'), json_encode([
            'type' => $user->getPhone() ? 'sms' : 'email',
            'userPhone' => $user->getPhone(),
            'userEmail' => $user->getEmail(),
            'promoId' => '',
        ]));
    }
}
