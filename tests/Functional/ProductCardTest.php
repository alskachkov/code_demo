<?php

namespace FunctionalTests;

class ProductCardTest extends WebTestCase
{
    /**
     * Карточка товара - новая запчасть
     */
    public function testIndexNew()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/part/new/2761247/');
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('Амортизатор передний левый Metaco 4810-002L', $crawler->filter('#content h1')->text());
    }

    /**
     * Карточка товара - новая запчасть (AJAX загрузка)
     */
    public function testAjaxIndexNew()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/part/new/2761247/', [], [], $this->ajax_request_header);
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertJsonResultOK($response, ['id' => '0-0-2761247-0']);
    }

    /**
     * Карточка товара - Поиск аналогов (Индикаторы/ Нов. и б/у аналоги/Под Заказ) (AJAX загрузка)
     */
    public function testAjaxSearchAnalogParts()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/part/search/metaco/4810002l?except_ids=0-0-2761247-0', [], [], $this->ajax_request_header);
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertJsonResultOK($response, ['result' => true, 'message' => 'OK']);

        // Получаем tv_id для б/у теста
        $responseArr = json_decode($response->getContent(), true);
        $tv_id = $responseArr['data']['used_products'][0]['tv_id'];

        return $tv_id;
    }

    /**
     * Карточка товара - б/у запчасть
     *
     * @depends testAjaxSearchAnalogParts
     */
    public function testIndexUsed($tv_id)
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', sprintf('/part/used/%s/', $tv_id));
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('Амортизатор передний левый', $crawler->filter('#content h1')->text());
    }

    /**
     * Карточка товара - Стоимость замены (AJAX загрузка)
     */
    public function testAjaxReplacementPartPrice()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/part/replacement/1076', [], [], $this->ajax_request_header);
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertJsonResultOK($response, ['result' => true]);
    }

    /**
     * Карточка товара - Задать вопрос по товару в мессенджере
     */
    public function testAskQuestionInMessenger()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/part/support/ask_a_question/tg/0-0-2761247-0');
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirect(), 'Ожидался редирект в канал мессенджера');
    }

}
