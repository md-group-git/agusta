<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\CallRequest;
use App\Entity\ClientRequest;
use App\Entity\OrderRequest;
use App\Entity\RideRequest;
use App\Entity\ServiceRequest;
use App\Service\EmailNotificationService;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DebugEmailNotificationsCommand extends Command
{
    /**
     * @var EmailNotificationService
     */
    private $notificationService;

    /**
     * DebugEmailNotificationsCommand constructor.
     *
     * @param EmailNotificationService $notificationService
     */
    public function __construct(EmailNotificationService $notificationService)
    {
        parent::__construct();

        $this->notificationService = $notificationService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:debug:client-request-notifications')
            ->setDescription('Debug client request email notifications')
            ->addArgument('email', InputArgument::REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getArgument('email');
        $this->notificationService->setRequestRecipients([$email]);

        $this->sendCallRequestNotification();
        $this->sendOrderRequestNotification();
        $this->sendRideRequestNotification();
        $this->sendServiceRequestNotification();

        $io->success(sprintf('Notification emails was sent to %s', $email));
    }

    private function sendCallRequestNotification()
    {
        /** @var CallRequest $request */
        $request = $this->createRequest(new CallRequest());
        $request->setMessage('Test call request message');

        $this->notificationService->sendClientRequestNotificationEmail($request);
    }

    private function sendOrderRequestNotification()
    {
        /** @var OrderRequest $request */
        $request = $this->createRequest(new OrderRequest());
        $request->setEmail('example@example.com');
        $request->setModel(null);

        $this->notificationService->sendClientRequestNotificationEmail($request);
    }

    private function sendRideRequestNotification()
    {
        /** @var RideRequest $request */
        $request = $this->createRequest(new RideRequest());
        $request->setDate(new DateTime());
        $request->setEmail('example@example.com');
        $request->setLicensed(true);
        $request->setModel(null);

        $this->notificationService->sendClientRequestNotificationEmail($request);
    }

    private function sendServiceRequestNotification()
    {
        /** @var ServiceRequest $request */
        $request = $this->createRequest(new ServiceRequest());
        $request->setMessage('Test service request message');

        $this->notificationService->sendClientRequestNotificationEmail($request);
    }

    /**
     * @param ClientRequest $request
     *
     * @return ClientRequest
     */
    private function createRequest(ClientRequest $request)
    {
        $request->setFirstName('First user name');
        $request->setLastName('Last user name');
        $request->setPhone('+0(123)456-7890');
        $request->setCreatedAt(new DateTime());

        return $request;
    }
}
