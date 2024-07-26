<?php

namespace App\Jobs;

use App\Models\EventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class RegisterEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $data, public $userId, public $memberHasRegistered, public $nonMemberHasRegistered)
    {
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $data = $this->data;
        $userId = $this->userId;
        $memberHasRegistered = $this->memberHasRegistered;
        $nonMemberHasRegistered = $this->nonMemberHasRegistered;

        DB::transaction(function () use ($data, $userId, $memberHasRegistered, $nonMemberHasRegistered) {
            if (!$memberHasRegistered && $data['memberRegister']) {
                $oldRegistration = EventRegistration::withTrashed()->where('user_id', $userId)->where('event_id', $data['eventId'])->first();
                if ($oldRegistration) {
                    $oldRegistration->restore();
                } else {
                    EventRegistration::create([
                        'user_id' => $userId,
                        'event_id' => $data['eventId'],
                    ]);
                }
            }

            if (!$nonMemberHasRegistered && $data['nonMemberRegister']) {
                EventRegistration::create([
                    'user_id' => 1,
                    'is_non_member' => true,
                    'non_member_name' => $data['nonMemberName'],
                    'event_id' => $data['eventId'],
                ]);
            }
        });
    }
}
