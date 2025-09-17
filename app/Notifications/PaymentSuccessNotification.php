<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification
{
    use Queueable;

    protected $amount;
    protected $billing_name;
    protected $billing_address;
    protected $shipping_address;
    
    /**
     * Create a new notification instance.
     *
     * @param float $amount
     * @param string $billing_name
     * @param string $billing_address
     * @param string $shipping_address
     */
    public function __construct(float $amount, string $billing_name, string $billing_address, string $shipping_address)
    {
        $this->amount = $amount;
        $this->billing_name = $billing_name;
        $this->billing_address = $billing_address;
        $this->shipping_address = $shipping_address;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */

     public function toMail($notifiable)
     {
        if (!$this->shipping_address) {
            $shipping_address = 'Same as billing address';
        } else {
            $shipping_address = $this->shipping_address;
        }
        $billing_address = json_decode($this->billing_address, true);
                           
         return (new MailMessage)
             ->subject('Payment Successful')
             ->greeting('Hello ' . $notifiable->name)
             ->line('Your payment was successful.')
             ->line('Amount: $' . number_format($this->amount, 2))
             ->line('Billing name: ' . $this->billing_name)
             ->line('Billing address: ' . $billing_address['line1'] . ', ' . 
                    ($billing_address['line2'] ? $billing_address['line2'] . ', ' : '') .
                    $billing_address['city'] . ', ' . 
                    ($billing_address['state'] ? $billing_address['state'] . ', ' : '') .
                    $billing_address['postal_code'] . ', ' . 
                    $billing_address['country'])
             ->line('Shipping address: ' . $shipping_address)
             ->line('Thank you for your purchase!');
     }
     

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'amount' => $this->amount,
            'billing_name' => $this->billing_name,
            'billing_address' => $this->billing_address,
            'shipping_address' => $this->shipping_address,
        ];
    }
}


