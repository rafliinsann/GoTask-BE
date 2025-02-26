<?php
namespace App\Events;

use App\Models\Workspace;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class WorkspaceUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $workspace;

    public function __construct(Workspace $workspace)
    {
        $this->workspace = $workspace;
    }

    public function broadcastOn()
    {
        return new Channel('workspaces');
    }

    public function broadcastAs()
    {
        return 'workspace.updated';
    }
}
