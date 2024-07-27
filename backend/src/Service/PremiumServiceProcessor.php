<?php
/**
 * Created by PhpStorm.
 * User: dentiman
 * Date: 2019-11-07
 * Time: 21:35
 */

namespace App\Service;

use Dentiman\PaymentBundle\Entity\Order;
use Dentiman\PaymentBundle\ServiceProcessor\AbstractServiceProcessor;
use Longman\TelegramBot\Request as TelegramRequest;
use Longman\TelegramBot\Telegram;
use Symfony\Component\HttpClient\HttpClient;

class PremiumServiceProcessor extends AbstractServiceProcessor
{
    protected function setup(Order $order)
    {
        $user =  $order->getOwner();
        $user->extendPremium( $order->getServiceVariant()->getExpiration()) ;
        $this->sendSystemNotification("new-bill:".$order->getOwner()->getEmail());

    }

    public function isActive()
    {
        return true;
    }


    protected function sendSystemNotification(string $message)
    {
        try {
            $telegram = new Telegram("613211651:AAGZ-wT07vzJDANhbWHzASwTV9aMEs0ftk8", "dentiman_bot");

            $result = TelegramRequest::sendMessage([
                'chat_id' => '-1001370496379',
                'text' => $message
            ]);
        } catch (\Exception $exception) {

        }
    }
}
