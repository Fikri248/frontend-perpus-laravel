<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Books::index');
$routes->get('/books', 'Books::index');
$routes->get('/books/create', 'Books::create');
$routes->post('/books', 'Books::store');
$routes->get('/books/(:num)/edit', 'Books::edit/$1');
$routes->post('/books/(:num)', 'Books::update/$1');
$routes->get('/books/(:num)', 'Books::show/$1');
$routes->get('/books/(:num)/delete', 'Books::delete/$1');

$routes->post('/books/(:num)/borrow', 'Books::borrow/$1');
$routes->post('/books/(:num)/return', 'Books::return/$1');

$routes->get('/api-front/books', 'Books::listingAjax');

