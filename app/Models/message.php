<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Defines which fields can be filled
     */
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'is_read',
        'admin_reply',
    ];

    /**
     * The attributes that should be cast.
     * Converts is_read to boolean
     */
    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Relationship: Message belongs to a user
     * Each message is sent by one customer
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark message as read (when admin opens it)
     * @return void
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Check if message has been replied to
     * @return bool
     */
    public function hasReply(): bool
    {
        return !is_null($this->admin_reply);
    }

    /**
     * Scope: Get unread messages only
     * Usage: Message::unread()->get()
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Get messages without admin reply
     * Usage: Message::unanswered()->get()
     */
    public function scopeUnanswered($query)
    {
        return $query->whereNull('admin_reply');
    }
}