<?php

namespace Controller\Cabinet;

use Euroauto\Service\Passport;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Euroauto\Auth;
use Controller\CabinetController;

class MailingController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $cabinetPassportController = $app['controllers_factory'];

        $cabinetPassportController->get('/', array($this, 'actionIndex'))
            ->before(function() use ($app) {
                return CabinetController::auth_check($app);
            })->bind('mailing');
        ;

        $cabinetPassportController->get('/delete/', array($this, 'actionDelete'))
            ->before(function() use ($app) {
                return CabinetController::auth_check($app);
            })->bind('mailing_delete');
        ;

        $cabinetPassportController->post('/', array($this, 'actionSave'))
            ->before(function() use ($app) {
                return CabinetController::auth_check($app);
            });

        return $cabinetPassportController;
    }

    /**
     * Отображание данных
     *
     * @param Application $app
     * @param Request $request
     * @return string
     *
     * @throws \Exception
     */
    public function actionIndex(Application $app)
    {
        $passportService = $app['ea.service.passport'];
        $modelMailing = $app['ea.model.mailing'];

        $data = $app['ea.model.messenger']->getMessengersData($app['user']->get_phone());
        $passport_id = Auth::instance($app)->get_passport_id();

        $groups = $modelMailing->getGroups();
        $channels = $modelMailing->getChannels();
        // Скрываем подключение к Facebook (DEVEA-12883)
        foreach ($channels as $key => $channel) {
            if ($channel['name'] === 'facebook') {
                unset($channels[$key]);
            }
        }

        $person_rules = $modelMailing->getPersonRules($passport_id);

        // соберем автопдписки сюда, что-бы сохранить в БД
        $auto_subscribe = array();
        // автоподписка, проверяем прилетела ли хоть одна запись с пустым channel_id. Если да, то обращаемся за приоритным каналом, куда можно автоподписать пользователя
        if (count($person_rules) > 0) {
            $person_priority_channel_id = null;
            foreach ($person_rules as $k => $person_rule) {
                if (!$person_rule['channel_id']) {
                    if(!$person_priority_channel_id) {
                        $person_priority_channel_id = $modelMailing->getPersonPriorityChannelId($passport_id);
                    }
                    $person_rules[$k]['channel_id'] = $person_priority_channel_id;
                    $auto_subscribe[] = array(
                        "group_id" => $person_rule['group_id'],
                        "channel_id" => $person_priority_channel_id,
                        "status" => 1,
                        "source" =>"auto_subscribe"
                    );
                }
            }
        }

        if($auto_subscribe) {
            // сохраняем даныне об автопдписке для данного клиента
            $app['ea.model.mailing']->setPersonRules($passport_id, $auto_subscribe);
        }

        $passportData = $passportService->get_data_by_id($passport_id);
        $view = \View::factory('twig');
        $content = $app['twig']->render(
            'layouts/cabinet/mailing/index.twig',
            array(
                'passport_id'     => $passport_id,
                'groups'          => $groups,
                'channels'        => $channels,
                'person_rules'    => $person_rules,
                'messengers'      => (isset($data['data_view'])? $data['data_view'] : array()),
                'email_confirmed' => (isset($passportData['email_confirmed'])) ? $passportData['email_confirmed'] : NULL,
            )
        );

        $plainBottom[] = '<script src="/js/jquery.custom-scroll.js"></script>';
        $plainBottom[] = '<script src="/js/cabinet/mailing.js"></script>';
        $plainBottom[] = "<link rel='stylesheet' type='text/css' href='/css/jquery.custom-scroll.css' />";
        $plainBottom = implode("\n", $plainBottom);
        $view->plainBottom($plainBottom);

        $plainHead[] = "<link rel='stylesheet' type='text/css' href='/css/ui-components/ui-kit-checkbox.css' />";
        $plainHead[] = "<link rel='stylesheet' type='text/css' href='/css/cabinet/mailing.css' />";
        $plainHead = implode("\n", $plainHead);
        $view->plainHead($plainHead);

        $view->setViewPath($content);
        $view->setTitle('Рассылки и уведомления');
        return $view->render();
    }

    /**
     * Cохранение данных
     *
     * @param Application $app
     * @param Request $request
     * @return string
     *
     * @throws \Exception
     */
    public function actionSave(Application $app, Request $request)
    {
        $passportId = Auth::instance($app)->get_passport_id();
        $rulesData = $request->get('mailing');

        if(is_array($rulesData) AND count($rulesData) > 0) {
            $rules = array();
            foreach ($rulesData as $item) {
                $rules[] = array(
                    "group_id" => (int)key($item),
                    "channel_id" => (int)array_shift($item),
                    "status" => 1,
                    "source" =>"lk_form_euroauto.ru"
                );
            }

            $app['ea.model.mailing']->setPersonRules($passportId, $rules);
        } else {
            // все удаляем
            $app['ea.model.mailing']->deletePersonRules($passportId);
        }

        return $app->redirect($app['url_generator']->generate('mailing'));
    }

    /**
     * Удаляем ВСЕ подписки клиента
     *
     * @param Application $app
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function actionDelete(Application $app, Request $request)
    {
        $passportId = Auth::instance($app)->get_passport_id();
        $app['ea.model.mailing']->deletePersonRules($passportId);
        return $app->redirect($app['url_generator']->generate('mailing'));
    }
}