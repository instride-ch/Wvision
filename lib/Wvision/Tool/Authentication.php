<?php
/**
 * w-vision
 *
 * LICENSE
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that is distributed with this source code.
 *
 * @copyright  Copyright (c) 2016 Woche-Pass AG (http://www.w-vision.ch)
 */

namespace Wvision\Tool;

use Wvision\Model\Configuration;
use Wvision\Tool\Email;
use Pimcore\Mail;
use Pimcore\Tool;
use Pimcore\Tool\Session;
use Pimcore\Model\Object;
use Pimcore\Model\Document;
use Pimcore\Model;
use Pimcore\Logger;

class Authentication
{
    /**
     * @var Object\ClassDefinition
     */
    protected $class;

    /**
     * @param null $classId
     * @throws \Exception
     */
    public function __construct($classId = null)
    {
        $class = null;
        if (is_string($classId)) {
            $class = Object\ClassDefinition::getByName($classId);
        } elseif (is_int($classId)) {
            $class = Object\ClassDefinition::getById($classId);
        } elseif ($classId !== null) {
            throw new \Exception("No valid class identifier given (class name or ID)");
        }

        if ($class instanceof Object\ClassDefinition) {
            $this->setClass($class);
        }
    }

    /**
     * get session
     *
     * @return Session
     */
    public function getSession() {
        return Session::get('wvision');
    }

    /**
     * checks if user is authenticated
     *
     * @return boolean | \Pimcore\Model\Object\AbstractObject
     */
    public function isAuthenticated() {
        $session = $this->getSession();

        if (isset($session->user)) {
            $class = $this->getClassName();
            $user = $class::getById($session->user);

            if (is_a($user, $class)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getClassName()
    {
        return "\\Pimcore\\Model\\Object\\" . ucfirst($this->getClass()->getName());
    }

    /**
     * @param array $params
     * @return bool
     */
    public function checkParams($params)
    {
        if (!array_key_exists("email", $params)) {
            return false;
        }

        if (strlen($params["email"]) < 6 || !strpos($params["email"], "@") || !strpos($params["email"], ".")) {
            return false;
        }

        return true;
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function register($params)
    {
        $onlyCreateVersion = false;
        $className = $this->getClassName();
        $object = new $className;
        $published = Configuration::get('APPLICATION.AUTH.PUBLISH_DIRECTLY');

        // check for existing e-mail
        $existingObject = $className::getByEmail($params['email'], 1);
        if ($existingObject) {
            $object = $existingObject;
            $onlyCreateVersion = true;
        }

        if (!array_key_exists('email', $params)) {
            throw new \Exception('key "email" is a mandatory parameter');
        }

        $object->setValues($params);

        if (!$object->getParentId()) {
            $object->setParentId(1);
        }

        $object->setCreationDate(time());
        $object->setModificationDate(time());
        $object->setUserModification(0);
        $object->setUserOwner(0);
        $object->setPublished($published);
        $object->setKey(\Pimcore\File::getValidFilename($object->getEmail() . "~" . substr(uniqid(), -3)));

        if (!$onlyCreateVersion) {
            $object->save();
        }

        // generate token
        $token = base64_encode(\Zend_Json::encode([
            'salt' => md5(microtime()),
            'email' => $object->getEmail(),
            'id' => $object->getId()
        ]));
        $token = str_replace('=', '~', $token);                                 // base64 can contain = which isn't safe in URL's
        $object->setProperty('token', 'text', $token);

        if (!$onlyCreateVersion) {
            $object->save();
        } else {
            $object->saveVersion(true, true);
        }

        $this->addNoteOnObject($object, "register");

        return $object;
    }

    /**
     * @param $object
     * @param $mailDocument
     * @param array $params
     * @throws \Exception
     */
    public function sendConfirmationMail($object, $mailDocument, $params = [])
    {
        $defaultParameters = [
            'gender' => $object->getGender(),
            'firstname' => $object->getFirstname(),
            'lastname' => $object->getLastname(),
            'email' => $object->getEmail(),
            'token' => $object->getProperty('token'),
            'object' => $object
        ];

        $params = array_merge($defaultParameters, $params);

        $mail = new Mail();
        $mail->addTo($object->getEmail());
        $mail->setDocument($mailDocument);
        $mail->setParams($params);
        $mail->send();
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function updateObject($object, $params)
    {
        $className = $this->getClassName();
        $emailExists = false;

        if ($object->getEmail() != $params['email']) {
            $emailExists = $className::getByEmail($params['email'], 1);
        }

        if ($object && !$emailExists) {
            if (!array_key_exists('email', $params)) {
                throw new \Exception('key "email" is a mandatory parameter');
            }

            $object->setValues($params);
            $object->setModificationDate(time());

            $object->save();

            $this->addNoteOnObject($object, "update");

            return $object;
        }

        return false;
    }

    /**
     * @param $token
     * @return bool
     * @throws \Zend_Json_Exception
     */
    public function getObjectByToken($token)
    {
        $originalToken = $token;
        $token = str_replace("~", "=", $token);                                 // base64 can contain = which isn't safe in URL's

        $data = \Zend_Json::decode(base64_decode($token));
        if ($data) {
            if ($object = Object::getById($data["id"])) {
                if ($version = $object->getLatestVersion()) {
                    $object = $version->getData();
                }

                if ($object->getProperty("token") == $originalToken) {
                    if ($object->getEmail() == $data["email"]) {
                        return $object;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function confirm($token)
    {
        $object = $this->getObjectByToken($token);
        $published = Configuration::get('APPLICATION.AUTH.PUBLISH_DIRECTLY');

        if ($object && !$object->getEmailConfirmed()) {
            if ($version = $object->getLatestVersion()) {
                $object = $version->getData();
                $object->setPublished($published);
            }

            $object->setEmailConfirmed(true);
            $object->save();

            $this->addNoteOnObject($object, 'confirm');

            return true;
        }

        return false;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function unregisterByToken($token)
    {
        $object = $this->getObjectByToken($token);
        if ($object) {
            return $this->unregister($object);
        }

        return false;
    }

    /**
     * @param string $email
     * @return bool
     */
    public function unregisterByEmail($email)
    {
        $className = $this->getClassName();
        $objects = $className::getByEmail($email);
        if (count($objects)) {
            foreach ($objects as $object) {
                $this->unregister($object);
            }

            return true;
        }

        return false;
    }

    /**
     * @param $object
     *
     * @return bool
     */
    public function unregister($object)
    {
        $delete = Configuration::get("APPLICATION.AUTH.DELETE_AFTER_UNREGISTER");

        if ($object) {
            if ($delete) {
                $object->delete();
            } else {
                $object->setUnpublished(true);
                $object->save();

                $this->addNoteOnObject($object, "unregister");
            }

            return true;
        }

        return false;
    }

    /**
     * @param $object
     * @param $title
     */
    public function addNoteOnObject($object, $title)
    {
        $note = new Model\Element\Note();
        $note->setElement($object);
        $note->setDate(time());
        $note->setType('authentication');
        $note->setTitle($title);
        $note->setUser(0);
        $note->setData([
            "ip" => [
                "type" => "text",
                "data" => Tool::getClientIp()
            ]
        ]);
        $note->save();
    }

    /**
     * Checks if e-mail address already
     * exists in the database.
     *
     * @param array $params
     * @return bool
     */
    public function isEmailExists($params)
    {
        $className = $this->getClassName();
        $existingObject = $className::getByEmail($params["email"], 1);
        if ($existingObject) {
            return true;
        }

        return false;
    }

    /**
     * @param Object\ClassDefinition $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return Object\ClassDefinition
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param $email
     * @param $password
     * @return null|User
     */
    public function authenticate($email, $password)
    {
        $className = $this->getClassName();
        $user = $className::getByEmail($email, 1);

        // user needs to be active, needs a password and an ID (do not allow system user to login, ...)
        if ($this->isValidUser($user)) {
            if ($this->verifyPassword($user, $password)) {
                $this->addNoteOnObject($user, "login");

                return $user;
            } else {
                throw new \Exception("Password could not be verified");
            }
        } else {
            throw new \Exception("User " . $user . " is not valid");
        }

        return null;
    }

    /**
     * unset user session
     */
    public function unsetUser()
    {
        $session = $this->getSession();

        if (isset($session->user)) {
            unset($session->user);

            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param $password
     * @return bool
     */
    public function verifyPassword($user, $password)
    {
        if ($user->getPassword()) {                                             // do not allow logins for users without a password
            if (password_verify($password, $user->getPassword())) {
                if (password_needs_rehash($user->getPassword(), PASSWORD_DEFAULT)) {
                    $user->setPassword($this->getPasswordHash($user->getEmail(), $password));
                    $user->save();
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @param $user
     * @return bool
     */
    public function isValidUser($user)
    {
        $className = $this->getClassName();

        if ($user instanceof $className && $user->getEmailConfirmed() && $user->getId() && $user->getPassword()) {
            return true;
        }

        return false;
    }

    /**
     * @param $username
     * @param $plainTextPassword
     * @return bool|false|string
     * @throws \Exception
     */
    public function getPasswordHash($username, $plainTextPassword)
    {
        $hash = password_hash($this->preparePlainTextPassword($plainTextPassword), PASSWORD_DEFAULT);
        if (!$hash) {
            throw new \Exception("Unable to create password hash for user: " . $username);
        }

        return $hash;
    }

    /**
     * send email to user in order to reset password
     *
     * @param  string $username  the user's email
     * @param  string $resetPath redirect to this page
     * @return bool              true|false
     * @throws \Exception
     */
    public function lostPassword($username, $emailDocument)
    {
        if ($username) {
            $user = Object\Kunde::getByEmail($username, ['limit' => 1, 'unpublished' => true]);

            if (!$user instanceof \Pimcore\Model\Object\Kunde) {
                return false;
            } else {
                if ($user->isPublished() && $user->getEmailConfirmed()) {
                    $params =[
                        'id' => $user->getId(),
                        'o_key' => $user->getO_key(),
                        'token' => $user->getProperty('token'),
                        'object' => $user
                    ];

                    $success = Email::send($user->getEmail(), $params, $emailDocument);

                    if ($success) {
                        return true;
                    }
                } else {
                    throw new \Exception("email " . $username . " has not been verified");
                }
            }
        }

        return false;
    }

    /**
     * resets password of the user object
     *
     * @param object $user   user object
     * @param array  $params all params
     */
    public function resetPassword($user, $params)
    {
        $plainTextPassword = $params['password'];

        if ($plainTextPassword == $params['confirmPassword']) {
            $hashedPassword = password_hash($plainTextPassword, PASSWORD_DEFAULT);

            $user->setPassword($hashedPassword);
            $user->save();

            return true;
        }

        return false;
    }
}
