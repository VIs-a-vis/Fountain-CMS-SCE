<?php

/**
 * Fountain CMS Site Card Edition
 * 
 * Lightweight content management system for site cards and minisites
 * 
 * @author    Kanat Gailimov <gailimov@gmail.com>
 * @copyright Copyright (c) Kanat Gailimov (http://gailimov.info) 2011
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License v3
 */


namespace app\plugins\feedback\controllers;

use core\PluginController,
    core\PluginInterface,
    core\Config,
    core\Translator,
    app\models\ConfigModel,
    app\plugins\feedback\models\ManagerModel;

/**
 * Feedback plugin's controller
 * 
 * @author Kanat Gailimov <gailimov@gmail.com>
 */
final class FeedbackController extends PluginController implements PluginInterface
{
    /**
     * Singleton instance
     * 
     * @var app\plugins\feedback\controllers\FeedbackController
     */
    private static $_instance;

    /**
     * Plugin name
     * 
     * @var string
     */
    private $_plugin = 'feedback';

    /**
     * Config
     * 
     * @var array
     */
    private $_config;

    /**
     * Language
     * 
     * @var string
     */
    private $_language;

    public function __construct()
    {
        parent::__construct($this->_plugin);
        $this->_config = Config::load('application');
        $this->_language = Translator::load('feedback', $this->_config['language'], 'feedback');
    }

    private function __clone()
    {}

    /**
     * Get singleton instance
     * 
     * @return app\plugins\feedback\controllers\FeedbackController
     */
    public static function getInstance()
    {
        if (!self::$_instance)
            self::$_instance = new self();
        return self::$_instance;
    }

    public function run()
    {
        $managerModel = new ManagerModel();
        $configModel = new ConfigModel();
        $manager = $managerModel->get();
        $settings = $configModel->get();

        // Errors
        $errors = array();
        // Success message
        $success = null;

        if ($this->getRequest()->isPost()) {
            $request = $this->getRequest()->getPost('contactForm');

            // Validation
            if (empty($request['message']))
                $errors[] = $this->_language['writeAMessagePlease'];
            if (empty($request['name']))
                $errors[] = $this->_language['pleaseIntroduceYourself'];
            if (empty($request['email']))
                $errors[] = $this->_language['enterTheEmailPlease'];
            if (!empty($request['email']) && filter_var($request['email'], FILTER_VALIDATE_EMAIL) == false)
                $errors[] = $this->_language['enterAValidEmailPlease'];

            if (empty($errors)) {
                // Sending mail using Swift Mailer

                require_once VENDOR_PATH . 'SwiftMailer' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'swift_required.php';

                $transport = \Swift_SmtpTransport::newInstance()->setHost($this->_config['smtp']['host'])
                                                                ->setPort($this->_config['smtp']['port'])
                                                                ->setEncryption($this->_config['smtp']['encryption'])
                                                                ->setUsername($this->_config['smtp']['username'])
                                                                ->setPassword($this->_config['smtp']['password']);

                $mailer = \Swift_Mailer::newInstance($transport);

                $message = \Swift_Message::newInstance()->setSubject('Письмо с сайта «' . $settings['title'] . '»')
                                                        ->setFrom(htmlspecialchars(trim($request['email'])))
                                                        ->setTo($manager['email'])
                                                        ->setBody(nl2br('<p>Автор: ' . htmlspecialchars(trim($request['name'])) . '</p><p>Email: ' . htmlspecialchars(trim($request['email'])) . '</p><p>Текст письма:</p><p>' . htmlspecialchars(trim($request['message'])) . '</p>'), 'text/html')
                                                        ->addPart(nl2br("Автор: " . htmlspecialchars(trim($request['name'])) . "\r\nТекст письма:\r\n" . htmlspecialchars(trim($request['message']))), 'text/plain');

                if ($mailer->send($message))
                    $success = $this->_language['messageSended'];
            }
        }

        $this->_smarty->assign('lang', $this->_language);
        $this->_smarty->assign('errors', $errors);
        $this->_smarty->assign('success', $success);
        if (isset($request)) $this->_smarty->assign('post', $request);
        return $this->_smarty->fetch('feedback.tpl');
    }
}
