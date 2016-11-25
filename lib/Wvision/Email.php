<?php

namespace Wvision;

class Email {
    /**
     * send notification email to user and admin
     * @param  string $username email address of user
     * @param  int    $user     user email document ID
     * @param  int    $admin    admin email document ID
     * @return bool             set status to true
     */
    public static function send($email, $user, $admin)
    {
        if ($email) {                                                           // check if email is available
            $data = $this->getAllParams();

            if ($user) {                                                        // check if user mail template is available
                try {                                                           // start try catch
                    $userMail = new \Pimcore\Mail();
                    $userMail->setDocument(\Document::getById($user));
                    $userMail->setParams($data);
                    $userMail->addTo($email);
                    $userMail->send();
                } catch (\Exception $e) {
                    Logger::err($e);

                    $feedback['message'] = "<strong>Fatal Error:</strong> " . $e->getMessage() . " on line " . $e->getLine();
                    $feedback['level'] = "alert-danger";
                }
            }

            if ($admin) {                                                       // check if admin mail template is available
                try {                                                           // start try catch
                    $adminMail = new \Pimcore\Mail();
                    $adminMail->setDocument(\Document::getById($admin));
                    $adminMail->setParams($data);
                    $adminMail->send();
                } catch (\Exception $e) {
                    Logger::err($e);

                    $feedback['message'] = "<strong>Fatal Error:</strong> " . $e->getMessage() . " on line " . $e->getLine();
                    $feedback['level'] = "alert-danger";
                }
            }

			$feedback['message'] = "<strong>Vielen Dank!</strong> E-Mail wurde versendet.";
			$feedback['level'] = "alert-success";
        } else {
            $feedback['message'] = "<strong>Fehler beim E-Mail-Versand.</strong> Bitte versuchen Sie es erneut!";
            $feedback['level'] = "alert-danger";
        }

        $this->view->feedback = $feedback;                                      // send feedback to view
    }
}
