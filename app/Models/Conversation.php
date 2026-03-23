<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages()
    {
        return $this->hasMany(ConversationMessage::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(ConversationMessage::class)->latestOfMany();
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($innerQuery) use ($userId) {
            $innerQuery->where('user_one_id', $userId)
                ->orWhere('user_two_id', $userId);
        });
    }

    public static function normalizeParticipants(int $firstUserId, int $secondUserId): array
    {
        return $firstUserId <= $secondUserId
            ? [$firstUserId, $secondUserId]
            : [$secondUserId, $firstUserId];
    }

    public static function betweenUsers(int $firstUserId, int $secondUserId)
    {
        [$userOneId, $userTwoId] = static::normalizeParticipants($firstUserId, $secondUserId);

        return static::query()
            ->where('user_one_id', $userOneId)
            ->where('user_two_id', $userTwoId);
    }

    public static function findOrCreateBetweenUsers(int $firstUserId, int $secondUserId): self
    {
        [$userOneId, $userTwoId] = static::normalizeParticipants($firstUserId, $secondUserId);

        return static::firstOrCreate([
            'user_one_id' => $userOneId,
            'user_two_id' => $userTwoId,
        ]);
    }

    public function otherParticipantFor(int $userId): ?User
    {
        if ((int) $this->user_one_id === $userId) {
            return $this->relationLoaded('userTwo') ? $this->userTwo : $this->userTwo()->first();
        }

        if ((int) $this->user_two_id === $userId) {
            return $this->relationLoaded('userOne') ? $this->userOne : $this->userOne()->first();
        }

        return null;
    }
}
