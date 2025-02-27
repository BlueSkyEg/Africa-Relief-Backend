<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication
require __DIR__ . '/auth.php';

// Stripe
require __DIR__ . '/stripe.php';

// Posts
require __DIR__ . '/posts.php';

// Firebase Notification
require __DIR__ . '/firebase-notification.php';

// Job Application
require __DIR__ . '/job-application.php';

// Contact
require __DIR__ . '/contact.php';

// Volunteers
require __DIR__ . '/volunteer.php';

// Newsletter
require __DIR__ . '/newsletter.php';

// QuickBooks
require __DIR__ . '/quickbooks.php';

// Salesforce
require __DIR__ . '/salesforce.php';

// User
require __DIR__ . '/user.php';

// Dashboard
require __DIR__ . '/dashboard.php';
