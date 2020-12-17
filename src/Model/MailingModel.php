<?php

namespace Model;

class MailingModel
{

    /**
     * Cервис работы с рассылокамм
     *
     * @var null
     */
    private $mailingService = null;


    public function __construct(\Euroauto\Service\Mailing $mailingService)
    {
        $this->mailingService = $mailingService;
    }

    /**
     * @return null
     *
     * @throws \Exception
     */
    protected function getMailingServise()
    {
        return $this->mailingService;
    }

    /**
     * Список подписок с проставленными галочками для определенного клие
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getGroups()
    {

        return $this->getMailingServise()->getGroups();
    }

    /**
     * Список каналов
     *
     * @param null $name
     * @return mixed
     *
     * @throws \Exception
     */
    public function getChannels($name = NULL)
    {
        return $this->getMailingServise()->getChannels($name);
    }

    /**
     * Нотификации для конкретного пользователя
     *
     * @param $person_id
     * @param null $channel_id
     * @return mixed
     *
     * @throws \Exception
     */
    public function getPersonRules($person_id, $channel_id = NULL)
    {
        return $this->getMailingServise()->getPersonRules($person_id, $channel_id);
    }

    /**
     * ID приоритеного канала пользователя
     *
     * @param $person_id
     * @param null $priority
     *
     * @return int|null
     */
    public function getPersonPriorityChannelId($person_id, $priority = NULL)
    {
        try{
            $channel_priority = (new \Euroauto\Service\NS())->get_person_channel_priority($person_id, $priority);
            return (isset($channel_priority['id'])) ? $channel_priority['id'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Сохраняем данные
     *
     * @param $person_id
     * @param $mailings
     * @return mixed
     *
     * @throws \Exception
     */
    public function setPersonRules($person_id, $mailings)
    {
        return $this->getMailingServise()->setPersonRules($person_id, $mailings);
    }

    /**
     * Сохраняем данные для определенной group
     *
     * @param $person_id
     * @param $mailings
     * @param $group
     * @return mixed
     *
     * @throws \Exception
     */
    public function setPersonGroupRules($person_id, $mailings, $group)
    {
        return $this->getMailingServise()->setPersonGroupRules($person_id, $mailings, $group);
    }

    /**
     * Удаляем ВСЕ для пользователя
     *
     * @param $person_id
     * @return mixed
     *
     * @throws \Exception
     */
    public function deletePersonRules($person_id)
    {
        return $this->getMailingServise()->deletePersonRules($person_id);
    }
}
