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

class OldPremiumServiceProcessor extends AbstractServiceProcessor
{
    protected function setup(Order $order)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://www.stock-watcher.com/secret_user_update.php?email='.$order->getOwner()->getEmail());

        $statusCode = $response->getStatusCode();
        $content = $response->getContent();
        $this->sendSystemNotification("Bill from: ".$order->getOwner()->getEmail());

    }

    public function isActive()
    {
        return false;
    }


    protected function sendSystemNotification(string $message)
    {
        try {
            $telegram = new Telegram("613211651:AAGZ-wT07vzJDANhbWHzASwTV9aMEs0ftk8", "dentiman_bot");

            $result = TelegramRequest::sendMessage([
                'chat_id' => '@dentiman_chat',
                'text' => $message
            ]);
        } catch (\Exception $exception) {

        }
    }
}
