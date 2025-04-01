<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CallRequest;
use App\Entity\ClientRequest;
use App\Entity\OrderRequest;
use App\Entity\RideRequest;
use App\Entity\ServiceRequest;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailNotificationService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $mailerNoReply;

    /**
     * @var array
     */
    private $mailerRequestRecipients;

    /**
     * EmailNotificationService constructor.
     *
     * @param MailerInterface     $mailer
     * @param LoggerInterface     $logger
     * @param TranslatorInterface $translator
     * @param string              $mailerNoReply
     * @param array               $mailerRequestRecipients
     */
    public function __construct(
        MailerInterface $mailer,
        LoggerInterface $logger,
        TranslatorInterface $translator,
        string $mailerNoReply,
        array $mailerRequestRecipients
    ) {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->translator = $translator;
        $this->mailerNoReply = $mailerNoReply;
        $this->mailerRequestRecipients = $mailerRequestRecipients;
    }

    /**
     * @param array $recipients
     */
    public function setRequestRecipients(array $recipients)
    {
        $this->mailerRequestRecipients = $recipients;
    }

    /**
     * @param ClientRequest $request
     */
    public function sendClientRequestNotificationEmail(ClientRequest $request)
    {
        if ($request instanceof CallRequest) {
            $template = 'email/call.html.twig';
            $subject = $this->translator->trans('message.call_request');
        } elseif ($request instanceof OrderRequest) {
            $template = 'email/order.html.twig';
            $subject = $this->translator->trans('message.order_request');
        } elseif ($request instanceof RideRequest) {
            $template = 'email/ride.html.twig';
            $subject = $this->translator->trans('message.ride_request');
        } elseif ($request instanceof ServiceRequest) {
            $template = 'email/service.html.twig';
            $subject = $this->translator->trans('message.service_request');
        } else {
            return;
        }

        try {
            $email = (new TemplatedEmail())
                ->to(...$this->mailerRequestRecipients)
                ->from(new Address($this->mailerNoReply, 'MV Agusta Russia'))
                ->subject($subject)
                ->htmlTemplate($template)
                ->context(['request' => $request])
            ;

            $email
                ->getHeaders()
                    ->addTextHeader('X-Auto-Response-Suppress', 'OOF, DR, RN, NRN, AutoReply')
            ;

            $this->mailer->send($email);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        } catch (TransportExceptionInterface $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
