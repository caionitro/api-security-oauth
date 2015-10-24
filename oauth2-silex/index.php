<?php
  require_once __DIR__ . '/vendor/autoload.php';

  use Silex\Application,
      SilexOpauth\OpauthExtension;

  $app = new Application();

  $app['debug'] = true;

  $app['opauth'] = array(
    'login' => '/auth/login', // Generates a path /auth/login/{strategy}
    'callback' => '/auth/callback',
    'config' => array(
      'security_salt' => '_SECURE_RANDOM_SALT_',
      'Strategy' => array(
          'Google' => array( 
             'client_id' => '325188231326-1dr92lquheau2evbsqpk553imv27k4q8.apps.googleusercontent.com',
             'client_secret' => 'W5Or6_3cWC_EjnpVC0zqhP4r'
           ),
      )
    )
  );

  $app->register(new OpauthExtension($app));

  // Listen for events
  $app->on(OpauthExtension::EVENT_ERROR, function($e) {
      $this->log->error('Auth error: ' . $e['message'], ['response' => $e->getSubject()]);
      $e->setArgument('result', $this->redirect('/'));
  });

  $app->on(OpauthExtension::EVENT_SUCCESS, function($e) use($app){
      $response = $e->getSubject();
      echo '<pre>'.print_r($response,1).'</pre>';
      //dando erro ao redirecionar para o root
      //$e->setArgument('result', $app->redirect('/'));
  });

  $app->run();