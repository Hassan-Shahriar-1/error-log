<?php

namespace Shahriar\ErrorLog\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Shahriar\ErrorLoger\Contracts\ErrorLoggerInterface;
use Illuminate\Support\Facades\Mail;

class ErrorLog extends Model implements ErrorLoggerInterface
{
    protected $fillable = ['message', 'file_location', 'line_number', 'status'];

    protected $table = 'error_logs';

    /**
     * storing error log information
     * @param string $message
     * @param string $line
     * @param string $file
     * @return void
     */
    public function logError(string $message, string $line, string $fileLocation): void
    {
        try {
            $this->storingErrorLog($message, $line, $fileLocation);
            if (Config('errorlog.SENDING_ERROR_MAILER') && Config('errorlog.MAIL_TO_ADDRESS')) {
                $this->sendingMailToDev();
            }
        } catch (Exception $e) {
            $this->storingErrorLog($e->getMessage(), $e->getLine(), $e->getFile());
        }
    }


    protected function sendingMailToDev()
    {
        $email = config('errorlog.MAIL_TO_ADDRESS');

        $view = 'acolyte.errorlog.emails.error_exception_email';

        $subject = 'Error Notification';

        if (config('errorlog.MAIL_CC_ADDRESS')) {
            $email = array($email, config('errorlog.MAIL_CC_ADDRESS'));
        }
        Mail::send($view, $data, function ($message) use ($email, $subject) {
            $message->from(config('errorlog.MAIL_FROM_ADDRESS'), config('errorlog.MAIL_FROM_NAME'));

            $message->to($email)->subject($subject);
        });
    }

    protected function storingErrorLog(string $message, string $line, string $fileLocation)
    {
        ErrorLog::create([
            'message' => $message,
            'file_location' => $fileLocation,
            'line_number' => $line,
            'status' => 'unsolved'
        ]);
    }
}
