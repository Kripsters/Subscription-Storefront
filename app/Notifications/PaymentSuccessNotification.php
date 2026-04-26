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
    
    public function __construct(float $amount, ?string $billing_name, ?array $billing_address, ?array $shipping_address)
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
        $billing_address = $this->billing_address ?? [];
        $billing_parts = array_filter([
            $billing_address['line1'] ?? null,
            $billing_address['line2'] ?? null,
            $billing_address['city'] ?? null,
            $billing_address['state'] ?? null,
            $billing_address['postal_code'] ?? null,
            $billing_address['country'] ?? null,
        ]);
        $billing_formatted = $billing_parts ? implode(', ', $billing_parts) : 'N/A';

        $shipping_raw = $this->shipping_address ?? [];
        $shipping_parts = array_filter([
            $shipping_raw['line1'] ?? null,
            $shipping_raw['line2'] ?? null,
            $shipping_raw['city'] ?? null,
            $shipping_raw['state'] ?? null,
            $shipping_raw['postal_code'] ?? null,
            $shipping_raw['country'] ?? null,
        ]);
        $shipping_formatted = $shipping_parts ? implode(', ', $shipping_parts) : 'Same as billing address';

         return (new MailMessage)
             ->subject('Payment Successful')
             ->greeting('Hello ' . ($notifiable->name ?? 'there'))
             ->line('Your payment was successful.')
             ->line('Amount: $' . number_format($this->amount, 2))
             ->line('Billing name: ' . ($this->billing_name ?? 'N/A'))
             ->line('Billing address: ' . $billing_formatted)
             ->line('Shipping address: ' . $shipping_formatted)
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


