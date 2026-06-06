<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Conversation;
use App\Models\System\Message;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    use ApiResponse;

    // Get list of recent conversations
    public function getConversations(Request $request)
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->with(['users' => function($query) use ($user) {
                $query->where('users.id', '!=', $user->id);
            }])
            ->withCount(['messages as unread_count' => function($query) use ($user) {
                $query->where('is_read', false)->where('sender_id', '!=', $user->id);
            }])
            ->get()
            ->map(function ($conversation) {
                $latestMessage = $conversation->messages()->latest()->first();
                $otherUser = $conversation->users->first();
                
                return [
                    'id' => $conversation->id,
                    'other_user' => $otherUser ? [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                        'role' => $otherUser->level ?? 'User',
                    ] : null,
                    'latest_message' => $latestMessage ? [
                        'body' => $latestMessage->body,
                        'created_at' => $latestMessage->created_at->toISOString(),
                        'is_read' => $latestMessage->is_read,
                        'sender_id' => $latestMessage->sender_id,
                    ] : null,
                    'unread_count' => $conversation->unread_count,
                    'updated_at' => $conversation->updated_at->toISOString(),
                ];
            })
            ->sortByDesc('updated_at')
            ->values();

        return $this->successResponse($conversations, 'Berhasil memuat daftar chat');
    }

    // Get messages for a conversation, or start one with a specific user
    public function getMessages(Request $request, $userId)
    {
        $currentUser = $request->user();
        
        // Find existing conversation between currentUser and $userId
        $conversation = Conversation::whereHas('users', function($q) use ($currentUser) {
            $q->where('users.id', $currentUser->id);
        })->whereHas('users', function($q) use ($userId) {
            $q->where('users.id', $userId);
        })->first();

        if (!$conversation) {
            return $this->successResponse([
                'conversation_id' => null,
                'messages' => []
            ], 'Belum ada obrolan');
        }

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'body' => $msg->body,
                    'is_read' => $msg->is_read,
                    'created_at' => $msg->created_at->toISOString(),
                ];
            });

        return $this->successResponse([
            'conversation_id' => $conversation->id,
            'messages' => $messages
        ], 'Berhasil memuat pesan');
    }

    // Send a message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $currentUser = $request->user();
        $receiverId = $request->receiver_id;

        DB::beginTransaction();
        try {
            // Find or create conversation
            $conversation = Conversation::whereHas('users', function($q) use ($currentUser) {
                $q->where('users.id', $currentUser->id);
            })->whereHas('users', function($q) use ($receiverId) {
                $q->where('users.id', $receiverId);
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create(['type' => 'individual']);
                $conversation->users()->attach([$currentUser->id, $receiverId]);
            }

            // Create message
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $currentUser->id,
                'body' => $request->message,
            ]);

            // Update conversation updated_at for sorting
            $conversation->touch();

            DB::commit();

            return $this->successResponse([
                'id' => $message->id,
                'conversation_id' => $conversation->id,
                'sender_id' => $message->sender_id,
                'body' => $message->body,
                'created_at' => $message->created_at->toISOString(),
            ], 'Pesan terkirim');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Gagal mengirim pesan: ' . $e->getMessage(), 500);
        }
    }

    // Get list of contacts (Users to chat with)
    public function getContacts(Request $request)
    {
        $currentUser = $request->user();
        
        // Return users who are not the current user
        // You might want to filter by role (e.g., Siswa can only see Guru, etc.)
        $contacts = User::where('id', '!=', $currentUser->id)
            ->where('is_active', true)
            ->select('id', 'name', 'email', 'status', 'level')
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->level ?? 'User',
                ];
            });

        return $this->successResponse($contacts, 'Berhasil memuat kontak');
    }
}

