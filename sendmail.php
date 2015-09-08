<?php
require 'init.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

do {
        $sqlBuilder = new \Simplon\Mysql\Manager\SqlQueryBuilder();

        $sqlBuilder
        ->setQuery('SELECT * FROM listafalsa WHERE script = :script AND state = :state AND unsubscribe = :unsubscribe')
        ->setConditions(array(
            'script' => $argv[1],
            'state' => '0',
            'unsubscribe' => '0'
            ));
        $select = $sqlManager->fetchRow($sqlBuilder);

        print_r($select['id']);

        $data = array(
        'state' => '1',
        );

        $sqlBuilder = new \Simplon\Mysql\Manager\SqlQueryBuilder();

        $sqlBuilder
        ->setTableName('listafalsa')
        ->setConditions(array('id' => $select['id']))
        ->setConditionsQuery("id = :id")
        ->setData($data);

        $update = $sqlManager->update($sqlBuilder);

        sendEmail($select['email'], $sqlManager);

        $data = array(
        'state' => '2',
        );

        $sqlBuilder = new \Simplon\Mysql\Manager\SqlQueryBuilder();

        $sqlBuilder
        ->setTableName('listafalsa')
        ->setConditions(array('id' => $select['id']))
        ->setConditionsQuery("id = :id")
        ->setData($data);

        $update = $sqlManager->update($sqlBuilder);
    } while ($select);


function sendEmail($email, $sqlManager)
{

    $mandrill = new Mandrill(getenv('APP_KEY'));
    $message = array(
        'html' => file_get_contents('newsletter.html', true),
        'subject' => 'Promotia Canon Back to school',
        'from_email' => 'office@canon.ro',
        'from_name' => 'Canon Romania',
        'to' => array(
                    array(
                        'email' => $email,
                        'type' => 'to'
                    )
                ),
        'track_opens' => true,
        'track_clicks' => true,
        'async' => true,
        'tags' => array(
                    'canon-back-to-school'
                ),
        'images' => array(
                        array(
                            'type' => 'image/png',
                            'name' => 'back-to-school',
                            'content' => '
'
                            )
                    )
    );
    $result = $mandrill->messages->send($message);
    print_r($result);
}