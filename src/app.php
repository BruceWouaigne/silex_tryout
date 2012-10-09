<?php

use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/../views'
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->get('/', function () use ($app) {
			return $app['twig']->render('index.html.twig');
		})->bind('index');

$app->get('/list/{content_type_accepted}', function ($content_type_accepted) use ($app) {

			$content_type_requested = '';

			if ($content_type_accepted == 'html') {
				$content_type_requested = 'text/html';
			} elseif ($content_type_accepted == 'xml') {
				$content_type_requested = 'application/xml';
			} elseif ($content_type_accepted == 'json') {
				$content_type_requested = 'application/json';
			}

			$cURL = curl_init();

			curl_setopt($cURL, CURLOPT_URL, "http://localhost/SF2_tryout/web/app_dev.php/");
			curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Accept: ' . $content_type_requested));
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($cURL);
			curl_close($cURL);

			$response = new Response();
			$response->setContent($output);
			$response->headers->set('Content-Type', $content_type_requested);

			return $response;
			
		})->bind('list');

$app->get('/single/{content_type_accepted}/{customer_id}', function ($content_type_accepted, $customer_id) use ($app) {

			$content_type_requested = '';

			if ($content_type_accepted == 'html') {
				$content_type_requested = 'text/html';
			} elseif ($content_type_accepted == 'xml') {
				$content_type_requested = 'application/xml';
			} elseif ($content_type_accepted == 'json') {
				$content_type_requested = 'application/json';
			}

			$cURL = curl_init();

			curl_setopt($cURL, CURLOPT_URL, "http://localhost/SF2_tryout/web/app_dev.php/$customer_id");
			curl_setopt($cURL, CURLOPT_HTTPHEADER, array('Accept: ' . $content_type_requested));
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($cURL);
			curl_close($cURL);

			$response = new Response();
			$response->setContent($output);
			$response->headers->set('Content-Type', $content_type_requested);

			return $response;
			
		})->bind('single');

$app->post('/add', function () use ($app) {

			$post_datas = array(
				'first_name' => $app['request']->request->get('first_name'),
				'last_name' => $app['request']->request->get('last_name'),
				'email_address' => $app['request']->request->get('email_address'),
			);
	
			$cURL = curl_init();

			curl_setopt($cURL, CURLOPT_URL, "http://localhost/SF2_tryout/web/app_dev.php/");
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cURL, CURLOPT_POST, true);
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $post_datas);
			$output = curl_exec($cURL);
			curl_close($cURL);

			$response = new Response();
			$response->setContent($output);

			return $response;
			
		})->bind('add');

$app->post('/edit', function () use ($app) {

			$post_datas = array(
				'id' => $app['request']->request->get('id'),
				'first_name' => $app['request']->request->get('first_name'),
				'last_name' => $app['request']->request->get('last_name'),
				'email_address' => $app['request']->request->get('email_address'),
			);
	
			$post_datas = http_build_query($post_datas);
			
			$cURL = curl_init();

			curl_setopt($cURL, CURLOPT_URL, "http://localhost/SF2_tryout/web/app_dev.php/");
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($cURL, CURLOPT_POSTFIELDS, $post_datas);
			$output = curl_exec($cURL);
			curl_close($cURL);

			$response = new Response();
			$response->setContent($output);

			return $response;
			
		})->bind('edit');

$app->get('/delete/{customer_id}', function ($customer_id) use ($app) {

			$cURL = curl_init();

			curl_setopt($cURL, CURLOPT_URL, "http://localhost/SF2_tryout/web/app_dev.php/$customer_id");
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, 'DELETE');
			$output = curl_exec($cURL);
			curl_close($cURL);

			$response = new Response();
			$response->setContent($output);

			return $response;
			
		})->bind('delete');


$app->run();
