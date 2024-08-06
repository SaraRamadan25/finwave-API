<?php
namespace App\Mail;

use App\Models\Goal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GoalAchievementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $goal;

    public function __construct(Goal $goal)
    {
        $this->goal = $goal;
    }

    public function build()
    {
        return $this->subject('Congratulations! Goal Achieved')
            ->view('emails.goal_achievement')
            ->with([
                'goalName' => $this->goal->name,
                'savedAmount' => $this->goal->saved_amount,
            ]);
    }
}
