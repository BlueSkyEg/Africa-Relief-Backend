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
require __DIR__ . '/api/auth.php';

// Stripe
require __DIR__ . '/api/stripe.php';

// Posts
require __DIR__ . '/api/posts.php';

// Firebase Notification
require __DIR__ . '/api/firebase-notification.php';

// Job Application
require __DIR__ . '/api/job-application.php';

// Contact
require __DIR__ . '/api/contact.php';

// Volunteers
require __DIR__ . '/api/volunteer.php';

// Newsletter
require __DIR__ . '/api/newsletter.php';

// QuickBooks
require __DIR__ . '/api/quickbooks.php';

// Salesforce
require __DIR__ . '/api/salesforce.php';

// User
require __DIR__ . '/api/user.php';
