<?php
// app/Mail/ReportStatusUpdated.php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function build()
    {
        return $this->subject('Your Report Status Updated - Fareway Bohol')
                    ->view('emails.report-status-updated');
    }
}