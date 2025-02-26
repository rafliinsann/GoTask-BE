<?php
namespace App\Events;

use App\Models\Board;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class BoardUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $board;

    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    public function broadcastOn()
    {
        return new Channel('boards');
    }

    public function broadcastAs()
    {
        return 'board.updated';
    }
}
