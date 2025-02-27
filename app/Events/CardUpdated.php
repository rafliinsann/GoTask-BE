<?php
namespace App\Events;

use App\Models\Card;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CardUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    public function broadcastOn()
    {
        return new Channel('cards');
    }

    public function broadcastAs()
    {
        return 'card.updated';
    }
}
